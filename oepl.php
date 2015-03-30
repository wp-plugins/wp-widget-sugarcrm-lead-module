<?php
 /*
 Plugin Name: WordPress to SugarCRM Lead
 Plugin URI: http://www.offshoreevolution.com/
 Description: This plugin will provide a Widget Form anywhere you want for easy,fast & hassle-free SugarCRM Leads.
 Version: 3.7
 Author: Offshore Evolution Pvt Ltd
 Author URI: http://www.offshoreevolution.com/
 License: GPL
 */

require_once ("oepl.conf.php");

/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'WPOEPLInstall');

/* Runs on plugin deactivation*/
register_deactivation_hook(__FILE__, 'WPOEPLUninstall');

function WPOEPLInstall() {
	$ins = new OEPLSugarCRMClass;
	$ins -> Install();
}

function WPOEPLUninstall() {
	$ins = new OEPLSugarCRMClass;
	$ins -> UnInstall();
}

if (is_admin()) {
	add_action('admin_menu', 'CreateMenu');
}

#### Session setting start
add_action('init', 'OEStartSession', 1);
add_action('wp_logout', 'OEEndSession');
function OEStartSession() {
    if(!session_id()) {
        session_start();
    }
}
function OEEndSession() {
    session_destroy();
}
#### Session setting end
?>