<?php
function generate_settings_page()
{
  if (!current_user_can('manage_options')) {
    wp_die(__("You don't have sufficient permissions to access the page."));
  }
  $options = get_option('wordpress_cdn_options');
  if ($options && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
    if ($_POST['type'] == 'options_update') {
      $options = array(
        'platform' => (isset($_POST['platform'])) ? sanitize_text_field(trim(stripslashes($_POST['platform']))) : '',
        'accessKeyId' => (isset($_POST['accessKeyId'])) ? sanitize_text_field(trim(stripslashes($_POST['accessKeyId']))) : '',
        'accessKeySecret' => (isset($_POST['accessKeySecret'])) ? sanitize_text_field(trim(stripslashes($_POST['accessKeySecret']))) : '',
        'endpoint' => (isset($_POST['endpoint'])) ? sanitize_text_field(trim(stripslashes($_POST['endpoint']))) : '',
        'bucket' => (isset($_POST['bucket'])) ? sanitize_text_field(trim(stripslashes($_POST['bucket']))) : '',
        'cdn_url_path' => (isset($_POST['cdn_url_path'])) ? sanitize_text_field(trim(stripslashes($_POST['cdn_url_path']))) : '',
      );

      update_option('wordpress_cdn_options', $options);

      update_option('upload_url_path', esc_url_raw(trim(trim(stripslashes($_POST['cdn_url_path'])))));
      ?>

<div style="font-size: 25px;color: red; margin-top: 20px;font-weight: bold;">
  <p>WordPress CDN Saved!</p>
</div>

<?php
    }
  }
  ?>
<div class="wrap" id="wordpres-cdn__container">
  <h2>WordPress CDN Settings</h2>
  <p>welcome to use WordPress CDN plugin</p>
  <form action="<?php echo wp_nonce_url('./options-general.php?page=' . PLUGIN_BASE_FOLDER . '-plugin'); ?>" method="POST">
    <h3 :id="item.key" v-for="(item, index) of fieldsData">
      <label for="">{{item.label || ''}}:</label>
      <select name="platform" value="" v-if="item.type === 'select'" @change="onPlatformChange">
        <option value="aliyun_oss">Aliyun OSS</option>
        <option value="tencent_cos">Tencent COS</option>
      </select>

      <input type="text" :name="item.key" :value="item.value" size="40" v-if="item.type === 'input'" />
    </h3>

    <p>
      <input type="submit" name="submit" value="Save" />
    </p>
    <input type="hidden" name="type" value="options_update">
  </form>
</div>

<script src="https://cdn.bootcss.com/vue/2.6.10/vue.min.js"></script>
<script>
  new Vue({
    el: '#wordpres-cdn__container',
    data: {
      fieldsData: [{
        key: 'platform',
        value: '<?php echo $options['platform'] ?>',
        type: 'select',
      }, {
        key: 'accessKeyId',
        value: '<?php echo $options['accessKeyId'] ?>',
        type: 'input',
      }, {
        key: 'accessKeySecret',
        value: '<?php echo $options['accessKeySecret'] ?>',
        type: 'input',
      }, {
        key: 'endpoint',
        value: '<?php echo $options['endpoint'] ?>',
        type: 'input',
      }, {
        key: 'bucket',
        value: '<?php echo $options['bucket'] ?>',
        type: 'input',
      }, {
        key: 'cdn_url_path',
        value: '<?php echo $options['cdn_url_path'] ?>',
        type: 'input',
      }],
      fieldsLabel: {
        aliyun_oss: {
          accessKeyId: {
            desc: 'accessKeyId',
          },
          accessKeySecret: {
            desc: 'accessKeySecret',
          },
          endpoint: {
            desc: 'endpoint',
          },
          bucket: {
            desc: 'bucket',
          },
          cdn_url_path: {
            desc: 'CDN URL',
          },
        },
        tencent_cos: {
          accessKeyId: {
            desc: 'secretId',
          },
          accessKeySecret: {
            desc: 'secretKey',
          },
          endpoint: {
            desc: 'region',
          },
          bucket: {
            desc: 'bucket',
          },
          cdn_url_path: {
            desc: 'CDN URL',
          },
        },
      }
    },
    created() {
      console.log('created');
      this.onPlatformChange();
    },
    methods: {
      onPlatformChange(e) {
        const platform = e ? e.target.value : '<?php echo $options['platform'] ?>';

        this.fieldsData = this.fieldsData.map(field => {
          if (field.key === 'platform') {
            field.value = platform;
          }

          if (this.fieldsLabel[platform][field.key]) {
            field.label = this.fieldsLabel[platform][field.key].desc;
          } else {
            field.label = field.key;
          }

          return field
        })

        console.log(this.fieldsData)
      },
    },
  })
</script>

<?php
}
?>