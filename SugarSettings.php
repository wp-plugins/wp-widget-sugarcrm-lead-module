<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

$htaccessProtected = get_option('OEPL_is_SugarCRM_htaccess_Protected');
$htaccessUsername  = get_option('OEPL_SugarCRM_htaccess_Username');
$htaccessPassword  = get_option('OEPL_SugarCRM_htaccess_Password');

$successMsg 			= get_option('OEPL_SugarCRMSuccessMessage');
$failureMsg 			= get_option('OEPL_SugarCRMFailureMessage');
$ReqFieldsMessage		= get_option('OEPL_SugarCRMReqFieldsMessage');
$InvalidCaptchaMessage	= get_option('OEPL_SugarCRMInvalidCaptchaMessage');

$IPaddrStatus 			= get_option('OEPL_auto_IP_addr_status');
$EmailNotification		= get_option('OEPL_Email_Notification');
$EmailNotificationRx	= get_option('OEPL_Email_Notification_Receiver');
$redirectStatus			= get_option("OEPL_User_Redirect_Status");
$redirectTo 			= get_option("OEPL_User_Redirect_To");

if($EmailNotification == 'Y') $Email_checked = 'checked="checked"'; else $Email_checked = '';
if($IPaddrStatus == 'Y') $IP_checked = 'checked="checked"'; else $IP_checked = '';
if($redirectStatus == 'Y') $redirectCbx = 'checked="checked"'; else $redirectCbx = '';
if($htaccessProtected == 'Y') $htacessCbx = 'checked="checked"'; else $htacessCbx = '';

if(!$_GET['view'] OR !isset($_GET['view']) OR $_GET['view'] == ''){
	$_GET['view'] = 'sugarSettings';
} 
?>

<div class="wrap">
<table align="left" cellpadding="1" width="100%" cellspacing="1" border="0" >
  <tr>
    <td valign="top"><table align="left" cellpadding="1" width="100%" cellspacing="1" border="0" >
        <tr height="30">
          <td><div style="float: left">
              <h2 style="color: #795548;font-weight: 900">WordPress to SugarCRM Lead</h2>
            </div>
            <div style="float: right"><a href="<?php echo admin_url('admin.php?page=SugarSetting&view=pro'); ?>" class="OEPL_get_pro">Get Pro version</a></div></td>
        </tr>
        <tr>
          <td valign="top" style=""><div class="OEPL_tab_settings">
            <ul class="tabs">
              <li class="<?php echo ($_GET['view'] == 'sugarSettings' ? 'active':''); ?>"><a href="<?php echo admin_url('admin.php?page=SugarSetting&view=sugarSettings'); ?>">SugarCRM Settings</a></li>
              <li class="<?php echo ($_GET['view'] == 'generalSettings' ? 'active':''); ?>"><a href="<?php echo admin_url('admin.php?page=SugarSetting&view=generalSettings'); ?>">General Settings</a></li>
              <li class="<?php echo ($_GET['view'] == 'messageSettings' ? 'active':''); ?>"><a href="<?php echo admin_url('admin.php?page=SugarSetting&view=messageSettings'); ?>">Message Settings</a></li>
              <li class="<?php echo ($_GET['view'] == 'CustomCSS' ? 'active':''); ?>"><a href="<?php echo admin_url('admin.php?page=SugarSetting&view=CustomCSS'); ?>">Custom CSS</a></li>
              <li class="<?php echo ($_GET['view'] == 'pro' ? 'active':''); ?>"><a href="<?php echo admin_url('admin.php?page=SugarSetting&view=pro'); ?>">Pro Features</a></li>
            </ul>
            <div class="content">
              <form name="OEPl_sugarSettings" id="OEPl_sugarSettings" method="post">
              <div class="OEPL_ErrMsg">This is Error Message</div>
              <div class="OEPL_SuccessMsg">This is success message</div>
              <table class="form-table" style="clear: none">
              <?php if($_GET['view'] == 'sugarSettings'){ 
		              	/**************************************
						 * PLUGIN SUGARCRM API SETTINGS
						 **************************************/
		              ?>
              <div class="title"><span class="fa fa-gears fa-1x" ></span> SugarCRM REST API Settings</div>
              <tr>
                <td><strong>SugarCRM URL :</strong><br />
                  <input name="OEPL_SUGARCRM_URL" type="text" id="OEPL_SUGARCRM_URL" value="<?php echo get_option('OEPL_SUGARCRM_URL'); ?>" size="53" required/>
                  <p class="description"><a href="http://support.sugarcrm.com/02_Documentation/04_Sugar_Developer/Sugar_Developer_Guide_7.2/70_API/Web_Services/00_API_Versioning/" target="_blank">Click here</a> to refer REST API url for your SugarCRM version.</p></td>
              </tr>
              <tr>
                <td><strong>SugarCRM Admin User :</strong><br />
                  <input name="OEPL_SUGARCRM_ADMIN_USER" autocomplete="off" type="text" id="OEPL_SUGARCRM_ADMIN_USER" value="<?php echo get_option('OEPL_SUGARCRM_ADMIN_USER'); ?>" size="25" required /></td>
              </tr>
              <tr>
                <td><strong>SugarCRM Admin Password : </strong><br />
                  <input name="OEPL_SUGARCRM_ADMIN_PASS" autocomplete="off" type="text" id="OEPL_SUGARCRM_ADMIN_PASS" value="" required size="25" /></td>
              </tr>
              <tr>
                <td colspan="2"><strong>Is your SugarCRM .htaccess protected ?</strong>&nbsp;&nbsp;
                  <input type="checkbox" name="OEPL_is_htacess_protected" id="OEPL_is_htacess_protected" <?php echo $htacessCbx; ?> /></td>
              </tr>
              <tr class="OEPL_htaccess_tr" style="display: none">
                <td><strong>.htaccess UserName : </strong><br />
                  <input type="text" name="Oepl_Htaccess_Admin_User" id="Oepl_Htaccess_Admin_User" value="<?php echo $htaccessUsername; ?>" /></td>
              </tr>
              <tr class="OEPL_htaccess_tr" style="display: none">
                <td><strong>.htaccess Password : </strong><br />
                  <input type="text" name="Oepl_Htaccess_Admin_Pass" id="Oepl_Htaccess_Admin_Pass" /></td>
              </tr>
              <tr>
                <td class="OEPL_reload_this"><input type="button" name="testConn" id="testConn" value="Test Connection" class="button button-large" />
                  &nbsp;&nbsp;
                  <input type="submit" value="<?php _e('Save Changes') ?>" class="button button-primary button-large OEPLsaveConfig" />
                  &nbsp;&nbsp;
                  <?php if(get_option('OEPL_SUGARCRM_ADMIN_PASS') && get_option('OEPL_SUGARCRM_ADMIN_PASS') != '' ){ ?>
                  <input type="button" value="<?php _e('Synchronize Lead Fields') ?>" id="LeadFldSync" class="button button-primary button-large" />
                  <?php } ?></td>
              </tr>
              <?php } ?>
              <?php if($_GET['view'] == 'generalSettings'){ 
		       /**************************************
			   * PLUGIN GENERAL SETTINGS
			   **************************************/
			   $captchaSettings = get_option('OEPL_Captcha_status');
		      ?>
              <div class="title"><span class="fa fa-tachometer fa-1x" style="font-size: 25px"></span> Plugin Genaral Settings</div>
              <tr>
              	<th>CAPTCHA status :</th>
              	<td>
              		<select class="captchaSettings" name="captchaSettings">
              			<option <?php echo ($captchaSettings == 'Y'? 'selected="selected"' : '') ?> value="Y">Active</option>
              			<option <?php echo ($captchaSettings == 'N' || $captchaSettings == FALSE ? 'selected="selected"' : '') ?> value="N">Inactive</option>
              		</select>
              		<p class="description">You can Enable or Disable CAPTCHA on your Lead-Forms anytime you want.</p>
              	</td>
              </tr>
              <tr>
                <th align="right">Capture Remote IP :</th>
                <td><input type="checkbox" name="IPaddrStatus" value="1" class="IPaddrStatus" <?php echo $IP_checked; ?> />
                  <p class="description">If check box is checked then plug-in will pass user's Remote IP Address to your SugarCRM Lead module. <br />
                  
                  <div class="OEPL_highlight_this"><strong>To use this feature – you must create a custom field in your SugarCRM Lead module with field-name 'lead_remote_ip'.</div>
                  </p></td>
              </tr>
              <tr>
                <th align="right">Get Email Notification :</th>
                <td><input type="checkbox" name="EmailNotification" id="EmailNotification" <?php echo $Email_checked; ?> />
                  <p class="description">Receive email notification whenever a new lead is generated.</p></td>
              </tr>
              <tr class="EmailToTR" style="display: none">
                <th align="right">Send Email to :</th>
                <td><input type="text" name="EmailReceiver" size="50" id="EmailReceiver" value="<?php echo $EmailNotificationRx; ?>" />
                  <p class="description">Provide Email address to which notification will be sent. Multiple recipients may be specified using a comma ( , ) separated string.</p></td>
              </tr>
              <tr>
                <th align="right">Redirect after submit :</th>
                <td><input type="checkbox" name="OEPL_redirect_user" id="OEPL_redirect_user" value="" <?php echo $redirectCbx; ?> />
                  <p class="description">Redirect user to any page you want after lead is successfully generated.</p></td>
              </tr>
              <tr class="OEPL_redirect_tr" style="display: none">
                <th align="right">Redirect to :</th>
                <td><input type="text" name="OEPL_redirect_user_to" id="OEPL_redirect_user_to" value="<?php echo $redirectTo; ?>" size="50" />
                  <p class="description">Please enter URL including http://...<br />
                    <strong>example :</strong> http://www.DomainName.com/</p></td>
              </tr>
              <tr height="8"></tr>
              <tr>
                <th></th>
                <td><?php submit_button('Save Settings','primary','OEPL_save_general_settings',false); ?></td>
              </tr>
              <?php } ?>
              
              <?php if($_GET['view'] == 'messageSettings'){ 
		        /**************************************
				* PLUGIN MESSAGE SETTINGS
				**************************************/
		      ?>
              <div class="title"><span class="fa fa-comments fa-1x" style="font-size: 25px"></span> Plugin Message Settings</div>
              <tr>
                <th>Success Message :</th>
                <td><textarea class="SuccessMessage" cols="40" rows="2" name="SuccessMessage"><?php echo $successMsg; ?></textarea>
                  <p class="description">Message to be displayed when lead is successfully generated.</p></td>
              </tr>
              <tr>
                <th>Failure Message :</th>
                <td><textarea cols="40" rows="2" class="FailureMessage" name="FailureMessage"><?php echo $failureMsg;  ?></textarea>
                  <p class="description">Message to be displayed when plugin cannot submit lead to SugarCRM.</p></td>
              </tr>
              <tr>
                <th>Required Fields Message :</th>
                <td><textarea cols="40" rows="2" class="ReqFieldsMessage" name="ReqFieldsMessage"><?php echo $ReqFieldsMessage; ?></textarea>
                  <p class="description">Message to be displayed when user is too lazy to fill in the required fields.</p></td>
              </tr>
              <tr>
                <th>Invalid Captcha Message :</th>
                <td><textarea cols="40" rows="2" class="InvalidCaptchaMessage" name="InvalidCaptchaMessage" ><?php echo $InvalidCaptchaMessage; ?></textarea>
                  <p class="description">Message to be displayed when user is so week at math that he cannot do simple addtion of two digits. </p></td>
              </tr>
              <tr>
                <td></td>
                <td><?php submit_button('Save Messages','primary','OEPL_message_save',false); ?></td>
              </tr>
              <?php } ?>
              
              
              <?php if($_GET['view'] == 'CustomCSS'){ 
		      	/**************************************
				* PLUGIN CUSTOM CSS SETTINGS
				**************************************/
		      ?>
		      
              <div class="title"><span class="fa fa-pencil-square-o fa-1x" style="font-size: 25px"></span> Custom CSS</div>
              <tr>
                <td style="padding: 5px 10px;"><textarea rows="15" style="width: 100%;font-family: Consolas,Monaco,monospace;min-width: 100%;max-width: 100%;border: 1px solid #009688 !important;box-shadow: 0 0 5px #009688 inset !important;" name="OEPL_custom_css" id="OEPL_custom_css" ><?php echo get_option("OEPL_Form_Custom_CSS"); ?></textarea>
                  <p class="description">You can write down your custom css here. Be sure to wrap your styles in "&lt;style&gt; &lt;/style&gt;" <br />
                    Click here for basic <a onclick="jQuery('#OEPL_form_structure').toggle()" style="cursor: pointer">Form Structure</a></p>
                  <div id="OEPL_form_structure" style="display: none">
                  <xmp style="cursor:copy;border: 1px solid #009688;padding: 10px;box-shadow: 0 0 5px #009688 inset !important;">
<p>// Form Message will be displayed here
  <div class='LeadFormMsg' style='color:red'></div>
</p>

<form id="OEPL_Widget_Form" >
  <p>
    <label><strong>LABEL HERE <span style="color: red">*</span> :</strong></label><br>
    <input type="text" id="" name="">
  </p>
  
  <p class="WidgetLeadFormCaptcha">
    <label>What is &nbsp;<strong>8 + 9</strong> ?</label><br>
    <input type="text"  id="captcha" name="">
  </p>
  
  <p>
    <input type="submit" id="WidgetFormSubmit" value="Submit" name="submit">
  </p>
</form>
              </xmp>
            </div></td>
	        </tr>
	        <tr>
	          <td style="padding: 5px 10px;"><?php submit_button('Save Custom CSS','primary','OEPL_css_save',false); ?></td>
	        </tr>
	        <?php } ?>
	        
	        
	      <?php if($_GET['view'] == 'pro'){ 
		  	/**************************************
			* PLUGIN CUSTOM CSS SETTINGS
			**************************************/
		  ?>
		  <div class="title"><span class="fa fa-bolt fa-1x" style="font-size: 25px"></span> Wordpress to SugarCRM form builder PRO</div>
		    <p>Thank you for using our Plugin. It's always nice to have more on your plate. Our PRO edition will help you to <strong>generate unlimited web-forms</strong> in few simple steps via user friendly drag & drop designed based form-builder.</p>
		    <p>If you didn't know then let me tell you that our plugin is also compitible with <strong>SuiteCRM.</strong></p>
		    <h4 class="OEPL_highlight_this OEPL-red" style="margin: 0px !important">Pro Version Features :</h4>
			<ul style="list-style:inside;margin-left: 10px;line-height: 1.6">
			    <li>User-friendly Drag & Drop form builder.</li>
			    <li>Option to use Custom CAPTCHA method or google reCAPTCHA on your Lead-Forms.</li>
			    <li>Create multiple forms and use Shortcode generated anywhere on your WordPress site.</li>
			    <li>Unlimited Forms with different set of fields.</li>
			    <li>Additional email notification option for each form.</li>
			    <li>Generate Short-Code to set form anywhere in your WordPress website.</li>
			    <li>Email compose feature to send auto reply to visitors for individual forms. You can use Lead-Form fields in WYSIWYG editor to compose email template.</li>
			    <li>Get premium support.</li>
			</ul>
			<hr />
			<p><a class="OEPL-link" href="https://www.youtube.com/watch?feature=player_embedded&v=Ue8XFqC6bnM" target="_blank">Click Here</a> for detailed video walk through of our <strong>'Wordpress to SugarCRM form builder PRO'.</strong></p>
			<p><a class="OEPL-link" href="http://goo.gl/q5cDJa" target="_blank">Click Here</a> for <strong>Live-Demo</strong></p>
			<p><a class="OEPL-link" href="http://goo.gl/h0RJE6" target="_blank">Click Here</a> to submit your inquiry to get PRO plugin and more detail about it.</p>
		  <?php } ?>
        </table>
      </form>
    </div>
  
    </div>
    </td>
  
    </tr>
  
</table>
</td>
<td valign="top" width="290px"><table class="OEPL_promotion" >
    <tr>
      <td ><a href="http://goo.gl/OPbP50" target="_blank"><img class="Social_icons" src="<?php echo OEPL_PLUGIN_URL.'image/offshore_evo_icon.png'?>" alt="Offshore Evolution - We make your IT needs live" title="Offshore Evolution - We make your IT needs live" style="height: 75px" /></a><br /></td>
    </tr>
    <tr>
      <td ><div style="font-size: 14px;margin-top: 8px;font-weight: 600;margin-right: 0px">&nbsp;Website : <a href="http://goo.gl/OPbP50" target="_blank">www.offshoreevolution.com</a></div></td>
    </tr>
    <tr>
      <td colspan="3"><div style="font-size: 14px;margin-top: 0px;font-weight: 600">Email us : <a href="mailto:info@offshoreevolution.com?subject=WP plugin - <?php echo get_bloginfo('name'); ?>&body=Hi Dipesh, %0D%0A %0D%0A Website: <?php echo get_bloginfo('url');  ?> %0D%0A %0D%0A Questions: %0D%0A %0D%0A Suggestions:">info@offshoreevolution.com</a></div></td>
    </tr>
    <tr>
      <td align="center"><a href="http://goo.gl/4if8Iz" target="_blank"><img class="Social_icons" src="<?php echo OEPL_PLUGIN_URL.'image/fb_icon.png'?>" alt="Facebook logo" title="Follow us on facebook" /></a> <a href="http://goo.gl/tFfSk1" target="_blank"><img class="Social_icons" src="<?php echo OEPL_PLUGIN_URL.'image/google_plus_icon.png'?>" alt="Google+ logo" title="Follow us on google+" /></a> <a href="http://goo.gl/ThCEqJ" target="_blank"><img class="Social_icons" src="<?php echo OEPL_PLUGIN_URL.'image/youtube_icon.png'?>" alt="youtube logo" title="Subscribe to our youtube channel" /></a></td>
    </tr>
    <tr>
      <td class="OEPL_promotion_tab" ><div align="center" style="font-size: 14px;font-weight: 600">We'd love to hear from you</div>
        <br />
        Would you mind taking a few minutes to write a review for us please ? It will be very helpful to us & will keep us motivated.<br />
        <br />
        <strong><a href="https://wordpress.org/support/view/plugin-reviews/wp-widget-sugarcrm-lead-module" target="_blank">Click here</a></strong> to submit your thoughts.</td>
    </tr>
    <tr>
      <td class="OEPL_promotion_tab" >Our goal is to expand this plugin as much as possible. If you want to support our continuous dedication & efforts. Please donate to our <em><strong>PayPal</strong></em> account <em><strong>“dipesh.99869@gmail.com”</strong></em>. We will very much appreciated that.</td>
    </tr>
    <tr height="10"></tr>
    <tr>
      <td><img style="width: 280px" src="<?php echo OEPL_PLUGIN_URL; ?>image/OEPL_slogan.png" /></td>
    </tr>
  </table></td>
</tr>
</table>
</div>
