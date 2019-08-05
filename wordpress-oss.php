<?php
/*
  Plugin Name: WordPress OSS
  Plugin URI: https://www.shaoyaoju.org
  Description: Upload files to Aliyun OSS
  Version: 0.0.1
  Author: juzhiyuan
  Author URI: https://www.shaoyaoju.org
 */

require_once 'actions.php';

add_action('admin_init', 'wordpress_oss_init');
add_action('admin_menu', 'add_settings_page');
