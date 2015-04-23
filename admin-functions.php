<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

add_action('init', 'OEPL_do_output_buffer');
function OEPL_do_output_buffer() {
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
		if(!in_array('custom_field',$rows)){
			$wpdb->query("ALTER TABLE ".OEPL_TBL_MAP_FIELDS." ADD `custom_field` ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N';");
		}
		update_option('OEPL_PLUGIN_VERSION',$OEPL_update_version);
    }
}
add_action('plugins_loaded', 'OEPL_plugin_update_function' );
### Plugin Update database changes Logic END

### Change Wordpress Default Upload dir START
function OEPL_Change_Upload_Dir($upload) {
	$upload['subdir']	= OEPL_FILE_UPLOAD_FOLDER;
	$upload['path']		= $upload['basedir'] . $upload['subdir'];
	$upload['url']		= $upload['baseurl'] . $upload['subdir'];
	return $upload;
}
### Change Wordpress Default Upload dir END

## Front end Save Function
add_action('wp_ajax_WidgetForm', 'WidgetForm');
add_action('wp_ajax_nopriv_WidgetForm', 'WidgetForm');
function WidgetForm(){
	global $objSugar, $wpdb, $_SESSION, $_POST, $_GET;
	$response = array();
	$captchaSettings = get_option('OEPL_Captcha_status');
	$successMsg = get_option('OEPL_SugarCRMSuccessMessage');
	$failureMsg = get_option('OEPL_SugarCRMFailureMessvvage');
	$EmailNotification	= get_option('OEPL_Email_Notification');
	if($_POST['captcha'] != $_SESSION['OEPL']['captcha'] && $captchaSettings == 'Y' ){
		$response['message'] = get_option('OEPL_SugarCRMInvalidCaptchaMessage');
		$response['redirectStatus'] = 'N';
		$response['success'] = 'N';
	} else {
		if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
		if(count($_FILES) > 0){
			$FileArray = array();
			add_filter('upload_dir', 'OEPL_Change_Upload_Dir');
			foreach($_FILES as $key=>$file){
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $file , $upload_overrides );
				$response['message'] = '';
				if ( $movefile ) {
					if($movefile['error']){
						$response['message'] .= "Sorry ! '".$file['name']."' colud not be uploaded due to security reasons.<br>";
					}else{
						$movefile['name'] = $file['name'];
						$FileArray[] = $movefile;
					}
				} else {
					$response['message'] .= "Error occured while uploading file. Please try again<br>";
				}
			}
			remove_filter('upload_dir', 'OEPL_Change_Upload_Dir');
		}
		$a = $objSugar->InsertLeadToSugar($FileArray);
		
		if($a != false) {
			if($EmailNotification && $EmailNotification == "Y"){
				$emailTo = get_option('OEPL_Email_Notification_Receiver');
				$query = "SELECT * FROM ".OEPL_TBL_MAP_FIELDS." WHERE is_show = 'Y' ORDER BY custom_field DESC,display_order";
				$RS = $wpdb->get_results($query,ARRAY_A);
				$message = '<h3>Lead Description</h3><table border="0">';
				$is_attach_avail = 'N';
				foreach($RS as $attr){
					if($attr['custom_field'] != 'Y')
					{
						$message .= '<tr><th>';
						$message .= $attr['wp_meta_label']." : </th><td>".$_POST[$attr['wp_meta_key']]."<br />";
						$message .= '</td></tr>';
					} else {
						$is_attach_avail = 'Y';
					}
				}
				if($is_attach_avail == 'Y'){
					$message .= '<tr><th>';
					$message .= "Attachments</th><td> : All attachments are available in your SugarCRM history/notes module";
					$message .= '</td></tr>';
				}
				$message .= "</table>";
				$Subject = "Lead generated from ".get_bloginfo('name')."";
				add_filter( 'wp_mail_content_type','OEPL_set_mail_contenttype' );
				wp_mail( $emailTo, $Subject, $message );
				remove_filter( 'wp_mail_content_type','OEPL_set_mail_contenttype' );
			}
			$redirectStatus	= get_option("OEPL_User_Redirect_Status");
			$redirectTo		= get_option("OEPL_User_Redirect_To");
			
			if($redirectStatus == 'Y'){
				$response['redirectStatus'] = 'Y';
				$response['redirectTo']		= $redirectTo;
				$response['success'] = 'Y';
			} else {
				$response['redirectStatus'] = 'N';
				$response['message'] .= $successMsg;
				$response['success'] = 'Y';
			}
			
		} else {
			$response['redirectStatus'] = 'N';
			$response['message'] .= $failureMsg;
			$response['success'] = 'N';
		}
		
	}
	$response = json_encode($response);
	echo stripslashes($response);
	$_SESSION['OEPL']['captcha'] = '';
	die();
}
## Front end Save Function End

function OEPL_set_mail_contenttype(){
    return "text/html";
}

## Save sugarCRM config START
add_action('wp_ajax_OEPLsaveConfig', 'OEPLsaveConfig');
function OEPLsaveConfig(){
	$TestConn = new OEPLSugarCRMClass;
	$TestConn->SugarURL  = $_POST['SugarURL'];
	$TestConn->SugarUser = $_POST['SugarUser']; 
	$TestConn->SugarPass = md5($_POST['SugarPass']); 
	if($_POST['isHtaccessProtected'] == 'Y'){
		$TestConn->isHtaccessProtected = TRUE;
		$TestConn->HtaccessAdminUser = $_POST['HtaccessUser'];
		$TestConn->HtaccessAdminPass = $_POST['HtaccessPass'];
	}
	$t = $TestConn->LoginToSugar();
	if(strlen($t)>10)
	{
		update_option('OEPL_SUGARCRM_URL' , $_POST['SugarURL']);
		update_option('OEPL_SUGARCRM_ADMIN_USER' , $_POST['SugarUser']);
		update_option('OEPL_SUGARCRM_ADMIN_PASS' , md5($_POST['SugarPass']));
		if($_POST['isHtaccessProtected'] == 'Y'){
			update_option('OEPL_is_SugarCRM_htaccess_Protected','Y');
			update_option('OEPL_SugarCRM_htaccess_Username', $_POST['HtaccessUser']);
			update_option('OEPL_SugarCRM_htaccess_Password', $_POST['HtaccessPass']);
		} else {
			update_option('OEPL_is_SugarCRM_htaccess_Protected','N');
			delete_option('OEPL_SugarCRM_htaccess_Username');
			delete_option('OEPL_SugarCRM_htaccess_Password');
		}
		$response['status'] = 'Y';
		$response['message'] = 'SugarCRM credentials saved successfully';
	} else {
		$response['status'] = 'N';
		$response['message'] = 'Invalid SugarCRM credentials. Please try again';
	}
	$response = json_encode($response);
	echo stripslashes($response);
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
		$response['status'] = 'N';
		$response['message'] = 'Error occured while synchronizing fields. Please try again.';
	} else {
		FieldSynchronize();
		$response['status'] = 'Y';
		$response['message'] = 'Fields synchronized successfully';
	}
	
	$response = json_encode($response);
	echo stripslashes($response);
	die();
}
##Lead fileds Sync function END

##General message save function START
add_action('wp_ajax_GeneralMessagesSave', 'GeneralMessagesSave');
function GeneralMessagesSave(){
	if(!empty($_POST))
	{
		update_option("OEPL_SugarCRMSuccessMessage",$_POST['SuccessMessage']);
		update_option("OEPL_SugarCRMFailureMessage",$_POST['FailureMessage']); 
		update_option("OEPL_SugarCRMReqFieldsMessage",$_POST['ReqFieldsMessage']); 
		update_option("OEPL_SugarCRMInvalidCaptchaMessage",$_POST['InvalidCaptchaMessage']);
		
		$response['status'] = 'Y';
		$response['message'] = 'Generat Messages saved successfully';
	} else {
		$response['status'] = 'N';
		$response['message'] = 'Error occured while saving General Messages. Please try again.';
	}
	$response = json_encode($response);
	echo stripslashes($response);
	die();
}

add_action("wp_ajax_OEPL_save_custom_css","OEPL_save_custom_css");
function OEPL_save_custom_css(){
	if(!empty($_POST)){
		update_option("OEPL_Form_Custom_CSS",$_POST['css']);
		$response['status'] = 'Y';
		$response['message'] = 'Custom CSS saved successfully';
	} else {
		$response['status'] = 'N';
		$response['message'] = 'Error occured while saving Custom CSS. Please try again.';
	}
	$response = json_encode($response);
	echo stripslashes($response);
	die();
}
##General message save function END

##General settings save function START
add_action('wp_ajax_GeneralSettingSave', 'GeneralSettingSave');
function GeneralSettingSave()
{
	$successMsg = get_option('OEPL_SugarCRMSuccessMessage');
	$failureMsg = get_option('OEPL_SugarCRMFailureMessage');
	if(!empty($_POST))
	{
		update_option("OEPL_auto_IP_addr_status",$_POST['IPaddrStatus']);
		update_option("OEPL_Email_Notification",$_POST['EmailNotification']);
		update_option("OEPL_Email_Notification_Receiver",$_POST['EmailReceiver']);
		update_option("OEPL_Captcha_status",$_POST['catpchaStatus']);
		update_option("OEPL_User_Redirect_Status",$_POST['redirectStatus']);
		update_option("OEPL_User_Redirect_To",$_POST['redirectTo']);
		
		$response['status'] = 'Y';
		$response['message'] = 'Plugin General Settings saved successfully';
	} else {
		$response['status'] = 'N';
		$response['message'] = 'Error occured while saving Plugin General Settings. Please try again.';
	}
	$response = json_encode($response);
	echo stripslashes($response);
	die();
}
##General settings save function END

## Save Custom Browse field START
add_action("wp_ajax_OEPL_Custom_Field_Save","OEPL_Custom_Field_Save");
function OEPL_Custom_Field_Save(){
	global $wpdb;
	$FieldName = $_POST['Field_Name'];
	$Filtered_FieldName = str_replace(' ','_',$_POST['Field_Name']);
	
	$query = "SELECT * FROM ".OEPL_TBL_MAP_FIELDS." WHERE wp_meta_label = '".$FieldName."'";
	$RS = $wpdb->get_results($query);
	
	if(count($RS) > 0){
		echo "Duplicate field already exist. Please try with a different field name.";
	} else {
		$insArray = array ('module'			=>'OEPL',
						   'field_type'		=>'file',
						   'data_type'		=>'file',

						   'wp_meta_label' 	=> $FieldName,
						   'is_show'		=> 'Y',
						   'custom_field' 	=> 'Y'
						  );
		$insert = $wpdb->insert (OEPL_TBL_MAP_FIELDS , $insArray);
		if ($insert != FALSE) {
			$where = array('pid' => $wpdb->insert_id);
			$updArray = array ('field_name' 	=> 'oepl_browse_'.$wpdb->insert_id,
							   'wp_meta_key' 	=> 'oepl_browse_'.$wpdb->insert_id);
			$update = $wpdb->update( OEPL_TBL_MAP_FIELDS ,$updArray,$where);
			if ($update != FALSE){
				echo "Field added successfully";
			} else {
				echo "Error occured. Please try again";
			}
		} else {
			echo 'Problem adding field. Please try again';
		}
	}
	die();
}
## Save Custom Browse field END

## DELETE custom browse field START
add_action("wp_ajax_OEPL_Custom_Field_Delete","OEPL_Custom_Field_Delete");
function OEPL_Custom_Field_Delete(){
	global $wpdb;
	$pid = $_POST['pid'];
	$where = array ( 'pid' => $pid );
	$delete = $wpdb->delete( OEPL_TBL_MAP_FIELDS , $where);
	if($delete != FALSE){
		echo "Field deleted successfully";	
	} else {
		echo "Error occured ! Please try again";
	}
	die();
}
## DELETE custom browse field END

##Submenu under SugarCRM Menu START
function OEPL_SugarCRM_Submenu_function(){
	echo "<div class='wrap'>";
	?>
	<div style="float: left">
	<h1 >SugarCRM Lead module field list</h1>
	<?PHP
	echo '<table class="OEPL_add_field_box">
		  	<tr height="25">
		  		<td><img src="'.OEPL_PLUGIN_URL.'image/plus-icon.png" valign="center" class="OEPL_hide_panel" is_show="No" /></td>
		  		<td colspan="4" style="font-size:15px" valign="center">Add Custom Browse Field</td>
		  	</tr>
		  	<tr class="OEPL_hidden_panel" style="display:none">
		  		<td></td>
		  		<td width="80">Field name : </td>
		  		<td width="80"><input type="text" id="OEPL_Custom_Field_Name" name="OEPL_Custom_Field_Name" /></td>
		  		<td align="left"><button class="button button-primary OEPL_Custom_Field_Add">Add Field</button></td>
		  	</tr>
		  	<tr class="OEPL_hidden_panel" style="display:none">
		  		<td></td>
		  		<td colspan="4"><span class="description"><strong>Note:</strong> Uploaded files will be available in Notes module of SugarCRM and History subpanel of Lead module.</span></td>
		  	</tr>
		  </table>';
	?>
	</div>
	<div class="OEPL_Vertical_Banner">
		
		<div class="Banner_content">
		  Enjoying our plugin? Please rate us or write review on WordPress.
		  <br /><br /><a class="OEPL_transperent_btn" href="https://wordpress.org/support/view/plugin-reviews/wp-widget-sugarcrm-lead-module" target="_blank" style="margin-right: 15px">Rate now</a><a class="OEPL_transperent_btn" href="http://goo.gl/OPbP50" target="_blank">Visit us</a>
		</div>
	</div>
		<br clear="all">
	<?php	  
	
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
	if($_POST['isHtaccessProtected'] == 'Y'){
		$TestConn->isHtaccessProtected = TRUE;
		$TestConn->HtaccessAdminUser = $_POST['HtaccessUser'];
		$TestConn->HtaccessAdminPass = $_POST['HtaccessPass'];
	}
	$t = $TestConn->LoginToSugar();
	if(strlen($t)>10)
	{
		$response['status'] = 'Y';
		$response['message'] = 'Connection Established Successfully';
	} else {
		$response['status'] = 'N';
		$response['message'] = 'Cannot connect to your SugarCRM. Please try again with correct SugarCRM credentials';
	}
	$response = json_encode($response);
	echo stripslashes($response);
	die();
}
## end   AJAX functions
?>