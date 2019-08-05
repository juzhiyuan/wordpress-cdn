<?php
/*
  Plugin Name: WordPress OSS
  Plugin URI: https://www.shaoyaoju.org
  Description: Upload files to Aliyun OSS
  Version: 0.0.1
  Author: juzhiyuan
  Author URI: https://www.shaoyaoju.org
 */

  // Public Variables
  $plugin_options_group = 'wordpress_oss_options';

  $accessKeyIdField= 'wordpress_oss__access_key_id';

  // Register Option Fields
  function wordpress_oss_init() {
    global $plugin_options_group;
    global $accessKeyIdField;

    register_setting($plugin_options_group, $accessKeyIdField);
  }
  add_action('admin_init', 'wordpress_oss_init');

  // Add Setting Menu to Admin Menu
  function add_settings_menu()
  {
    add_options_page('WordPress OSS Settings', 'WordPress OSS', 'manage_options', 'wordpress-oss-plugin', 'generate_settings_page');
  }
  add_action('admin_menu', 'add_settings_menu');

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
          <input type="text" name="<?php echo esc_attr($accessKeyIdField) ?>" id="<?php echo esc_attr($accessKeyIdField) ?>"
            value="<?php echo esc_attr(get_option($accessKeyIdField)) ?>" size="40" />
        </h3>

        <p>
          <input type="submit" name="submit" value="Save" />
        </p>
      </form>
    </div>

<?php
  }
  // Add Setting Page End
?>