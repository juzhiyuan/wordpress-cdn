<?php
/*
  Plugin Name: WordPress OSS
  Plugin URI: https://www.shaoyaoju.org
  Description: Upload files to Aliyun OSS
  Version: 0.0.1
  Author: juzhiyuan
  Author URI: https://www.shaoyaoju.org
 */

$plugin_options_group = 'wordpress_oss_options';

require_once 'actions.php';

register_activation_hook(__FILE__, "wordpress_oss_activatition");
register_deactivation_hook(__FILE__, "wordpress_oss_deactivation");

// add_action('admin_init', 'wordpress_oss_init');
add_action('admin_menu', 'add_settings_page');
