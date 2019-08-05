<?php
// Add Setting Page Start
function generate_settings_page()
{
  global $plugin_options_group;
  global $accessKeyIdField;

  if (!current_user_can('manage_options')) {
    wp_die(__("You don't have sufficient permissions to access the page."));
  }
  ?>
  <div class="wrap">
    <h2>WordPress OSS Settings</h2>
    <p>Welcome to WordPress OSS plugin.</p>
    <form action="options.php" method="POST" id="wordpress-oss-form">
      <!-- Generate Nonce String for Security -->
      <?php settings_fields($plugin_options_group); ?>

      <h3>
        <label for=""></label>accessKeyId:
        <input type="text" name="<?php echo esc_attr($accessKeyIdField) ?>" id="<?php echo esc_attr($accessKeyIdField) ?>" value="<?php echo esc_attr(get_option($accessKeyIdField)) ?>" size="40" />
      </h3>

      <p>
        <input type="submit" name="submit" value="Save" />
      </p>
    </form>
  </div>
<?php
}
?>