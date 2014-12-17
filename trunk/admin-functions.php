<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}

### Plugin Update database changes Logic START
function OEPL_plugin_update_function() {
    global $OEPL_update_version,$wpdb;
	$OEPL_current_version = get_option("OEPL_PLUGIN_VERSION");
    if ($OEPL_current_version != $OEPL_update_version) {
    	$sql = 'SHOW COLUMNS FROM '.OEPL_TBL_MAP_FIELDS;
		$rows = $wpdb->get_col($sql);
		if(!in_array('wp_custom_label',$rows)){
			$wpdb->query('ALTER TABLE '.OEPL_TBL_MAP_FIELDS.' ADD `wp_custom_label` VARCHAR( 50 ) NOT NULL AFTER `wp_meta_label`');
		}
		if(!in_array('required',$rows)){
			$wpdb->query("ALTER TABLE ".OEPL_TBL_MAP_FIELDS." ADD `required` ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N' AFTER `display_order`");
		}
		if(!in_array('hidden',$rows)){
			$wpdb->query("ALTER TABLE ".OEPL_TBL_MAP_FIELDS." ADD `hidden` ENUM('Y','N') NOT NULL DEFAULT 'N' AFTER `required`;");
		}
		update_option('OEPL_PLUGIN_VERSION',$OEPL_update_version);
    }
}
add_action('plugins_loaded', 'OEPL_plugin_update_function' );
### Plugin Update database changes Logic END

## Front end Save Function
add_action('wp_ajax_WidgetForm', 'WidgetForm');
add_action('wp_ajax_nopriv_WidgetForm', 'WidgetForm');
function WidgetForm(){
	global $objSugar, $wpdb, $_SESSION, $_POST, $_GET;
	$successMsg = get_option('OEPL_SugarCRMSuccessMessage');
	$failureMsg = get_option('OEPL_SugarCRMFailureMessvvage');
	
	if($_POST['captcha'] != ($_SESSION['captcha1'] + $_SESSION['captcha2']) ){	
		echo "Invalid captcha code.Please try again";
	} else {
		$a = $objSugar->InsertLeadToSugar();
		if($a != false) {
			echo $successMsg;
		} else {
			echo $failureMsg;
		}
	}
	$random1 = rand(1,9);
	$random2 = rand(1,9);
	$_SESSION['captcha1'] = $random1;
	$_SESSION['captcha2'] = $random2;
	die();
}
## Front end Save Function End

## Save sugarCRM config START
add_action('wp_ajax_OEPLsaveConfig', 'OEPLsaveConfig');
function OEPLsaveConfig(){
	$TestConn = new OEPLSugarCRMClass;
	$TestConn->SugarURL  = $_POST['SugarURL'];
	$TestConn->SugarUser = $_POST['SugarUser']; 
	$TestConn->SugarPass = md5($_POST['SugarPass']); 
	$t = $TestConn->LoginToSugar();
	if(strlen($t)>10)
	{
		update_option('OEPL_SUGARCRM_URL' , $_POST['SugarURL']);
		update_option('OEPL_SUGARCRM_ADMIN_USER' , $_POST['SugarUser']);
		update_option('OEPL_SUGARCRM_ADMIN_PASS' , md5($_POST['SugarPass']));
		echo 'Configuration saved successfully';
	} else {
		echo "Invalid login details. Please try again";
	}
	die();
}
## Save sugarCRM config END

##Lead fileds Sync function START
add_action('wp_ajax_LeadFieldSync', 'LeadFieldSync');
function LeadFieldSync(){
	global $objSugar;
	$t = $objSugar->LoginToSugar();
	if(!strlen($t)>10)
	{
		echo "Fields synchronization failed. Please try again";
		die();	
	} else {
		FieldSynchronize();
		echo "Fields synchronized successfully.";
		die();
	}
}
##Lead fileds Sync function END

##General message save function START
add_action('wp_ajax_SugarGeneralMsg', 'SugarGeneralMsg');
function SugarGeneralMsg()
{
	$successMsg = get_option('OEPL_SugarCRMSuccessMessage');
	$failureMsg = get_option('OEPL_SugarCRMFailureMessage');
	if(!empty($_POST))
	{
		update_option("OEPL_SugarCRMSuccessMessage",$_POST['SuccessMessage']);
		update_option("OEPL_SugarCRMFailureMessage",$_POST['FailureMessage']); 
		update_option("OEPL_auto_IP_addr_status",$_POST['IPaddrStatus']); 
		echo "General settings saved successfully.";
		die();
	}
}
##General message save function END

##Submenu under SugarCRM Menu START
function OEPL_SugarCRM_Submenu_function(){
	echo "<div class='wrap'>";
	echo "<h1>SugarCRM Lead module field list</h1>";
	echo '<form id="OEPL-Leads_table" method="post">';
	require_once(OEPL_PLUGIN_DIR . 'Fields_map_table.php');
	$table = new Fields_Map_Table;
	echo '<input type="hidden" name="page" value="mapping_table" />';
	$table->search_box('Search', 'LeadSearchID');
	$table->prepare_items();
	$table->display();
	echo '</form>';
	echo "</div>";
}
##Submenu under SugarCRM Menu END

## SugarCRM Password Encryption
/***************************************
 * Code commented because sometimes hook
 * does not fire because some conflicts 
 * in tyhemes and other plugins
 * *************************************
add_action('update_option','SetHaleSugarCRMFields', 10, 2);
function SetHaleSugarCRMFields(){
	global $objSugar, $wpdb;
	if(!empty($_POST))
	{
		if($_POST['OEPL_SUGARCRM_ADMIN_PASS'] != '')
		{
			delete_option( 'OEPL_SUGARCRM_ADMIN_PASS' );
			add_option( 'OEPL_SUGARCRM_ADMIN_PASS', md5($_POST['OEPL_SUGARCRM_ADMIN_PASS']), NULL, 'no' );
		}
				
		$objSugar = new OEPLSugarCRMClass;
		$objSugar->SugarURL  = trim($_POST['OEPL_SUGARCRM_URL']);
		$objSugar->SugarUser = trim($_POST['OEPL_SUGARCRM_ADMIN_USER']); 
		$objSugar->SugarPass = md5($_POST['OEPL_SUGARCRM_ADMIN_PASS']);
	
		$t = $objSugar->LoginToSugar();
		if(!strlen($t)>10)
		{
			return false;	
		}
		FieldSynchronize();
		return null;
	}
} */
## SugarCRM Password Encryption

function FieldSynchronize()
{
	global $objSugar, $wpdb;

	if($objSugar->SugerSessID == '')
	{
		$t = $objSugar->LoginToSugar();
	}
	if(!strlen($objSugar->SugerSessID)>10)
	{
		return false;	
	}
	
	## Start - Set Module Fields in Table
	foreach($objSugar->ModuleList as $key => $val)
	{
		$ModuleName = $val;
		$ModuleFileds = $objSugar->getLeadFieldsList();
		$SugarFlds = array();
		if(count($ModuleFileds->module_fields) > 0)
		{
		foreach($ModuleFileds->module_fields as $fkey => $fval)
		{
			$fType = $fval->type;
			$insAry = array();
			switch($fType)
			{
				case 'enum':
					$insAry['field_type']  = 'select';
					$insAry['field_value'] = serialize($fval->options);
					break;
				case 'radioenum':
					$insAry['field_type']  = 'radio';
					$insAry['field_value'] = serialize($fval->options);
					break;
				case 'bool':
					$insAry['field_type']  = 'checkbox';
					$insAry['field_value'] = serialize($fval->options);
					break;
				case 'text':
					$insAry['field_type'] 	= 'textarea';
					$insAry['field_value'] 	= '';
					break;
				case 'file':
					$insAry['field_type'] 	= 'file';
					$insAry['field_value'] 	= '';
					break;
				default:
					$insAry['field_type']  = 'text';
					$insAry['field_value'] = '';
					break;
			}
			$insAry['module'] 		 = $ModuleName;
			$insAry['field_name'] 	 = $fkey;
			$insAry['wp_meta_key'] 	 = OEPL_METAKEY_EXT . strtolower($ModuleName). '_' . $fkey;
			$insAry['wp_meta_label'] = $fval->label;
			$insAry['data_type'] 	 = $fval->type;
			$insAry['wp_meta_label'] = str_replace(':','', trim($insAry['wp_meta_label']) );
				
			$query = "SELECT count(*) as tot FROM ".OEPL_TBL_MAP_FIELDS." 
					  WHERE module = '".$insAry['module'] . "' AND field_name = '".$insAry['field_name'] . "'";
			$RecCount = $wpdb->get_results($query, ARRAY_A);
			
			if(!in_array($insAry['field_name'], $objSugar->ExcludeFields) )
			{
				$SugarFlds[] = $insAry['field_name'];
				if( $RecCount[0]['tot'] <= 0 )
				{
					$sql = "INSERT INTO ".OEPL_TBL_MAP_FIELDS." SET 
							module 		  = '".$insAry['module'] . "' , 
							field_type 	  = '".$insAry['field_type'] . "' , 
							data_type 	  = '".$insAry['data_type'] . "' , 
							field_name 	  = '".$insAry['field_name'] . "' , 
							field_value   = '".$insAry['field_value'] . "' , 
							wp_meta_label = '".$insAry['wp_meta_label'] . "' , 
							wp_meta_key   = '".$insAry['wp_meta_key'] . "' ";
					$wpdb->query($sql);
				} else {
					$sql = "UPDATE ".OEPL_TBL_MAP_FIELDS." SET 
								module 		  = '".$insAry['module'] . "' , 
								field_type 	  = '".$insAry['field_type'] . "' , 
								data_type 	  = '".$insAry['data_type'] . "' , 
								field_name 	  = '".$insAry['field_name'] . "' , 
								field_value   = '".$insAry['field_value'] . "' , 
								wp_meta_label = '".$insAry['wp_meta_label'] . "' , 
								wp_meta_key   = '".$insAry['wp_meta_key'] . "' 
						   WHERE module = '".$insAry['module'] . "' AND field_name = '".$insAry['field_name'] . "'";
					$wpdb->query($sql);
				}
			}
		}
		}
		$query   = "SELECT pid, field_name, wp_meta_key FROM ".OEPL_TBL_MAP_FIELDS." 
					WHERE module = '".$insAry['module'] . "'";
		$WPFieldsRS = $wpdb->get_results($query, ARRAY_A);
		$fcnt = count($WPFieldsRS);
		$WPFields = array();
		for($i=0; $i<$fcnt; $i++)
		{
			if(!in_array($WPFieldsRS[$i]['field_name'], $SugarFlds) )
			{
				$delSql = "DELETE FROM ".OEPL_TBL_MAP_FIELDS." WHERE pid = ".$WPFieldsRS[$i]['pid']." AND module = '".$ModuleName."'"; 
				$wpdb->query($delSql);
			}
		}
	}
	## End - Set Module Fields in Table
}

## start AJAX functions
add_action('wp_ajax_TestSugarConn', 'TestSugarConn');
function TestSugarConn()
{	
	$TestConn = new OEPLSugarCRMClass;
	$TestConn->SugarURL  = $_POST['URL'];
	$TestConn->SugarUser = $_POST['USER']; 
	$TestConn->SugarPass = md5($_POST['PASS']); 
	$t = $TestConn->LoginToSugar();

	if(strlen($t)>10)
	{
		echo "Connection successfully established.";
	} else {
		echo "Connection failed. Please check detail.";
	}
	die();
}
## end   AJAX functions
?>