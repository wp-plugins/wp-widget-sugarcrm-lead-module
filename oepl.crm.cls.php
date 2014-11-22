<?phpif (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {    exit('Please don\'t access this file directly.');}
class OEPLSugarCRMClass {	function OEPLSugarCRMClass() 	{		$this->DropSugarTblOnUninstall = false;		$this->ModuleList = array('Leads');		$this->ModuleListName = array(	'Leads' => 'Leads');		$this->ModuleListStr = "'" . implode( "','", $this->ModuleList) . "'";					$this->SugarURL  = '';		$this->SugarUser = ''; 		$this->SugarPass = ''; 		$this->SugarClient = ''; 		$this->SugarSessID = ''; 		$this->ExcludeFields = array('id', 'date_entered', 'date_modified', 'modified_user_id', 'modified_by_name', 'created_by', 'created_by_name', 'deleted', 'assigned_user_id', 'assigned_user_name', 'team_id', 'team_set_id', 'team_count', 'team_name', 'email_addresses_non_primary', 'account_name', 'account_description', 'opportunity_name', 'opportunity_amount', 'email2', 'invalid_email', 'email_opt_out', 'webtolead_email1', 'webtolead_email2', 'webtolead_email_opt_out', 'webtolead_invalid_email', 'email', 'full_name', 'reports_to_id', 'report_to_name', 'contact_id', 'account_id', 'opportunity_id', 'birthdate', 'refered_by', 'c_accept_status_fields', 'm_accept_status_fields');
		$this->ExcludeFieldTypes = array(); //array('date', 'datetime');
	}
	function Install() {		global $wpdb;		$sql = "CREATE TABLE IF NOT EXISTS `".OEPL_TBL_MAP_FIELDS."` (  				  `pid` int(11) NOT NULL AUTO_INCREMENT,    			  `module` varchar(100) NOT NULL,				  `field_type` enum('text','select','radio','checkbox','textarea','file','filler') NOT NULL DEFAULT 'text',  				  `data_type` varchar(50) NOT NULL,  				  `field_name` varchar(255) NOT NULL,				  `field_value` text NOT NULL,				  `wp_meta_key` varchar(150) NOT NULL,				  `wp_meta_label` varchar(200) NOT NULL,  				  `display_order` int(11) NOT NULL,  				  `is_show` enum('Y','N') NOT NULL DEFAULT 'N',				  `show_column` enum('1','2') NOT NULL DEFAULT '1',				  PRIMARY KEY (`pid`) 				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
		$wpdb->query($sql);				add_option("OEPL_SugarCRMSuccessMessage", 'Thank you! You message was sent successfully.');		add_option("OEPL_SugarCRMFailureMessage", 'Sorry. An error occurred while sending the message!');	}
	function UnInstall () {
		global $wpdb;
		delete_option('OEPL_SUGARCRM_URL');		delete_option('OEPL_SUGARCRM_ADMIN_USER');		delete_option('OEPL_SUGARCRM_ADMIN_PASS');		delete_option('OEPL_SugarCRMSuccessMessage');		delete_option('OEPL_SugarCRMFailureMessage');		$sql = "DROP TABLE ".OEPL_TBL_MAP_FIELDS."";		$wpdb->query($sql);	}
	function LoginToSugar()	{		$login_parameters = array(	 "user_auth"=>array(  "user_name"	=> $this->SugarUser,														  "password"	=> $this->SugarPass,														  "version"		=> "1"													   ),									 "application_name"	=>	"RestTest",			 						 "name_value_list"	=>	array()								);																									$this->SugarSessData = $this->SugarCall("login", $login_parameters, $this->SugarURL);		$this->SugerSessID = $this->SugarSessData->id;		return $this->SugerSessID;	}
	function LogoutToSugar()	{		$login_parameters = array(	"user_auth"=>array(	"user_name"	=>	$this->SugarUser,														"password"	=>	md5($this->SugarPass),														"version"	=>	"1"													   ),									"application_name"	=>	"RestTest",									"name_value_list"	=>	array(),		);		$this->SugarCall("logout", $login_parameters, $this->SugarURL);	}

	function get_size($file, $type) {		switch($type){  			case "KB":  				$filesize = filesize($file) * .0009765625; // bytes to KB  				break;  			case "MB":  				$filesize = (filesize($file) * .0009765625) * .0009765625; // bytes to MB  				break;  			case "GB":  				$filesize = ((filesize($file) * .0009765625) * .0009765625) * .0009765625; // bytes to GB  				break;  		}  		if($filesize <= 0)		{  			return $filesize = 0; /*'unknown file size'; */ 		}  		else		{			return round($filesize, 2); /*.' '.$type;*/ 		}  	}
	function ErrorLogWrite($text123) {		$text = "\n\n\n";		$text .= 'Log: ' . "\n" . $text123 . "\n" ;		$myFile = OEPL_PLUGIN_DIR . 'Log.txt';		if($this->get_size($myFile, 'MB') > 2 ) 		{			@rename(HALE_PLUGIN_DIR . 'Log.txt', HALE_PLUGIN_DIR . 'Log_till_'.date('d-M-Y-H-i-s-u').'.txt');		} 
		$myFile = HALE_PLUGIN_DIR . 'Log.txt';		$fh = fopen($myFile, 'a+') or die("can't open file");		fwrite($fh, $text);		fclose($fh);		return NULL;	}	
	function SugarCall($method, $parameters, $url)	{		if($this->SugarURL == '') return null;		$curl_request = curl_init();		curl_setopt($curl_request, CURLOPT_URL, $url);		curl_setopt($curl_request, CURLOPT_POST, 1);		curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);		curl_setopt($curl_request, CURLOPT_HEADER, 1);		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);		curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);		$jsonEncodedData = json_encode($parameters);
		$post = array(			 "method" => $method,			 "input_type" => "JSON",			 "response_type" => "JSON",			 "rest_data" => $jsonEncodedData		);
		curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);		$result = curl_exec($curl_request);		if ( $error = curl_error($curl_request) )		{			echo 'ERROR: ',$error;			echo "<br />";			echo curl_error($curl_request);			echo "<br />";			echo curl_errno($curl_request);		}		
		curl_close($curl_request);		$result = explode("\r\n\r\n", $result, 2);		$response = json_decode($result[1]);
		return $response;	}
	function IsUserAdministrator() {		global $current_user;		$R = '';
		if(is_array($current_user->roles)) {			foreach($current_user->roles as $k => $v) {				if ($v == 'administrator' && $R == '') {					$R = 'administrator';				}			}		}
		if($R == 'administrator') {			return true;		} else {			return false;		}	}	
	function IsUserSubscriber() {		global $current_user;		$R = '';		if(is_array($current_user->roles)) {			foreach($current_user->roles as $k => $v) {				if ($v == 'subscriber' && $R == '') {					$R = 'subscriber';				}			}		}		if($R == 'subscriber') {			return true;		} else {			return false;		}   }	function getLeadFieldsList()	{		$result = (object)array();		if($this->SugerSessID == '')		{			$a = $this->LoginToSugar();		}				if($this->SugerSessID)		{			$set_entry_parameters = array(	 "session" 		=> $this->SugerSessID,											 "module_name"	=> "Leads"										 );			$result = $this->SugarCall("get_module_fields", $set_entry_parameters, $this->SugarURL);		}		return $result;	}		function InsertLeadToSugar()	{		global $wpdb, $_POST;		$result = array();		if($this->SugerSessID == '')		{			$a = $this->LoginToSugar();		}		if($this->SugerSessID)		{			$sql 	= "SELECT * FROM ".OEPL_TBL_MAP_FIELDS." as mp WHERE mp.module = 'Leads'";			$ContRS = $wpdb->get_results($sql,ARRAY_A);			$fCnt 	= count($ContRS);			$name_value_list = array();			for($i=0; $i<$fCnt; $i++)			{				if(  isset($_POST[ $ContRS[$i]['wp_meta_key'] ]) )				{					$name_value_list[] = array("name" => $ContRS[$i]['field_name'], "value" => $_POST[ $ContRS[$i]['wp_meta_key'] ]);				}			}								if(count($name_value_list) > 0 &&  $this->SugerSessID != '')			{				$set_entry_parameters = array(	 "session" 			=> $this->SugerSessID,												 "module_name"		=> "Leads",												 "name_value_list" 	=> $name_value_list											 );				$resultLead = $this->SugarCall("set_entry", $set_entry_parameters, $this->SugarURL);			}			return $resultLead->id;		}		return false;	}	
}?>