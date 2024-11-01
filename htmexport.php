<?php
/**
 * Plugin Name: Single Post Export
 * Description: Export single posts or select a list of posts and generate an export for the wordpress standard importer
 * Author: Html Digital
 * Author URI: https://www.htmldigital.co.uk
 * Version: 1.0.0
 * Plugin URI:
 */

require_once(ABSPATH . '/wp-admin/includes/plugin.php');
require_once(ABSPATH . WPINC . '/pluggable.php');

//Autoload
include( __DIR__.'/'.'lib'.'/'.'classloader.php'); 

define( 'HTMEXPORT', __FILE__ );


//Load the plugin - classloader will take care of the includes
$HtmBakery_plugin = \HtmExport\Plugin\Plugin::get_instance()->register();

