<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

/* define the plugin folder url */
define('OEPL_PLUGIN_URL', plugin_dir_url(__FILE__));

/* define the plugin folder dir */
define ('OEPL_PLUGIN_DIR', plugin_dir_path(__FILE__));

define ('OEPL_METAKEY_EXT', 'oepl_');

ini_set("soap.wsdl_cache_enabled","0");

define('OEPL_SUGAR_USER_ID_KEY', 'oepl_sugar_contact_id');

define('OEPL_SUGARCRM_URL'			, get_option('OEPL_SUGARCRM_URL') );
define('OEPL_SUGARCRM_ADMIN_USER'	, get_option('OEPL_SUGARCRM_ADMIN_USER'));
define('OEPL_SUGARCRM_ADMIN_PASS'	, get_option('OEPL_SUGARCRM_ADMIN_PASS'));

# Table list
define('OEPL_TBL_MAP_FIELDS', 	 	 'oepl_crm_map_fields');

require_once(OEPL_PLUGIN_DIR. "oepl.crm.cls.php");
$objSugar = new OEPLSugarCRMClass;
$objSugar->SugarURL  = OEPL_SUGARCRM_URL;
$objSugar->SugarUser = OEPL_SUGARCRM_ADMIN_USER; 
$objSugar->SugarPass = OEPL_SUGARCRM_ADMIN_PASS; 

require_once(OEPL_PLUGIN_DIR . "OEPL-Widget.php");
require_once(OEPL_PLUGIN_DIR . "admin-functions.php");
require_once(OEPL_PLUGIN_DIR . "Common-functions.php");

define('LOCAL_SERVER_IP', '172.16.16.33');

function OEPL_frontend_script_load() {
	wp_enqueue_script('admin_js',OEPL_PLUGIN_URL.'/js/admin.js', array( 'jquery' ));
}
add_action( 'wp_enqueue_scripts', 'OEPL_frontend_script_load' );

add_action( 'admin_init', 'PluginStyleJS' );
function PluginStyleJS() {
	wp_enqueue_style("OpelStyle", OEPL_PLUGIN_URL ."style/style.css", false, "1.0", "all");
	wp_register_script('OpelJS', OEPL_PLUGIN_URL .'js/admin.js',array(),false, true);
	wp_enqueue_script( 'OpelJS');
}

function CreateMenu() {
    global $current_user, $menu, $objSugar;
	if($objSugar->IsUserAdministrator() == true) {
	    add_menu_page( 'SugarSetting', 'Your SugarCRM', 'administrator', 'SugarSetting', 'SugarSettings', '', 98 );
		add_submenu_page( 'SugarSetting', 'Lead Module', 'Lead Module', 'manage_options', 'mapping_table', 'OEPL_SugarCRM_Submenu_function');
	}
}

function SugarSettings()
{
	global $objSugar, $wpdb;
	require_once(OEPL_PLUGIN_DIR . 'SugarSettings.php');
}

add_action( 'admin_footer', 'HaleFooterHTML' );
function HaleFooterHTML(){
global $current_user;
  echo '<br clear="all" />
  		<div id="AjaxWaiting" style="display: none; background:url('.OEPL_PLUGIN_URL.'image/ajax-loader.gif) no-repeat center #fff;">Please Wait...</div>
		<div id="OverLayer" class="BlackOverlayer" style="display:none;"></div>
		<div id="HTMLContainer" class="HTMLContainerCls" style="display:none;"></div>
		<br clear="all" />';
	echo '<script type="text/javascript">
			var OEPL_PLUGIN_URL = \''.OEPL_PLUGIN_URL.'\';
			var OEPL_WP_URL = \''.admin_url().'\';
	</script>';
}
?>