<?php
function generate_settings_page()
{
  if (!current_user_can('manage_options')) {
    wp_die(__("You don't have sufficient permissions to access the page."));
  }
  $options = get_option('wordpress_oss_options');
  if ($options && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
    if ($_POST['type'] == 'wordpress_oss_options_update') {
      $options = array(
        'accessKeyId' => (isset($_POST['accessKeyId'])) ? sanitize_text_field(trim(stripslashes($_POST['accessKeyId']))) : '',
        'accessKeySecret' => (isset($_POST['accessKeySecret'])) ? sanitize_text_field(trim(stripslashes($_POST['accessKeySecret']))) : '',
        'endpoint' => (isset($_POST['endpoint'])) ? sanitize_text_field(trim(stripslashes($_POST['endpoint']))) : '',
        'bucket' => (isset($_POST['bucket'])) ? sanitize_text_field(trim(stripslashes($_POST['bucket']))) : '',
        'cdn_url_path' => (isset($_POST['cdn_url_path'])) ? sanitize_text_field(trim(stripslashes($_POST['cdn_url_path']))) : '',
      );

      update_option('wordpress_oss_options', $options);

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
    <h2>WordPress OSS Settings</h2>
    <p>Welcome to WordPress OSS plugin.</p>
    <form action="<?php echo wp_nonce_url('./options-general.php?page=' . WORDPRESS_OSS_BASE_FOLDER . '-plugin'); ?>" method="POST" id="wordpress-oss-form">
      <h3>
        <label for=""></label>accessKeyId:
        <input type="text" name="accessKeyId" value="<?php echo esc_attr($options['accessKeyId']) ?>" size="40" />
      </h3>

      <h3>
        <label for=""></label>accessKeySecret:
        <input type="text" name="accessKeySecret" value="<?php echo esc_attr($options['accessKeySecret']) ?>" size="40" />
      </h3>

      <h3>
        <label for=""></label>endpoint:
        <input type="text" name="endpoint" value="<?php echo esc_attr($options['endpoint']) ?>" size="40" />
      </h3>

      <h3>
        <label for=""></label>bucket:
        <input type="text" name="bucket" value="<?php echo esc_attr($options['bucket']) ?>" size="40" />
      </h3>

      <h3>
        <label for=""></label>cdn_url_path:
        <input type="text" name="cdn_url_path" value="<?php echo esc_attr($options['cdn_url_path']) ?>" size="40" />
      </h3>

      <p>
        <input type="submit" name="submit" value="Save" />
      </p>
      <input type="hidden" name="type" value="wordpress_oss_options_update">
    </form>
  </div>
<?php
}
?>