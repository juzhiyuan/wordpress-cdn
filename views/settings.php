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
  <p>WordPress OSS Saved!</p>
</div>

<?php
    }
  }
  ?>
<div class="wrap">
  <h2>WordPress CDN Settings</h2>
  <p>welcome to use WordPress CDN plugin</p>
  <form action="<?php echo wp_nonce_url('./options-general.php?page=' . PLUGIN_BASE_FOLDER . '-plugin'); ?>" method="POST">
    <h3 id="platform">
      <label for=""></label>Platform:
      <select name="platform" value="">
        <option value="aliyun_oss">Aliyun OSS</option>
        <option value="tencent_cos">Tencent COS</option>
      </select>
    </h3>

    <h3 id="accessKeyId">
      <label for=""></label>
      <input type="text" name="accessKeyId" value="<?php echo esc_attr($options['accessKeyId']) ?>" size="40" />
    </h3>

    <h3 id="accessKeySecret">
      <label for=""></label>
      <input type="text" name="accessKeySecret" value="<?php echo esc_attr($options['accessKeySecret']) ?>" size="40" />
    </h3>

    <h3 id="endpoint">
      <label for=""></label>
      <input type="text" name="endpoint" value="<?php echo esc_attr($options['endpoint']) ?>" size="40" />
    </h3>

    <h3 id="bucket">
      <label for=""></label>
      <input type="text" name="bucket" value="<?php echo esc_attr($options['bucket']) ?>" size="40" />
    </h3>

    <h3 id="cdn_url_path">
      <label for=""></label>
      <input type="text" name="cdn_url_path" value="<?php echo esc_attr($options['cdn_url_path']) ?>" size="40" />
    </h3>

    <p>
      <input type="submit" name="submit" value="Save" />
    </p>
    <input type="hidden" name="type" value="options_update">
  </form>
</div>

<script>
  jQuery(function($) {
    updateFieldsLabel();

    var platform = '<?php echo $options['platform'] ?>';
    $('#platform option[value=' + platform + ']').attr('selected', 'selected');

    $('#platform').change(function() {
      // var value = document.querySelector('#platform > select > option[selected]').value;
      // updateFieldsLabel(value);
    });
  });

  function updateFieldsLabel(platformName) {
    var platform = platformName || '<?php echo $options['platform'] ?>';

    var PLATFORM_FIELDS_MAP = {
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

    var PLATFORM_FIELDS = PLATFORM_FIELDS_MAP[platform];
    for (key in PLATFORM_FIELDS) {
      document.querySelector('#' + key + ' > label').innerText = PLATFORM_FIELDS[key].desc + ':';
    }
  }
</script>

<?php
}
?>