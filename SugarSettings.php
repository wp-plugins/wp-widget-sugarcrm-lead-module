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
?>

<div class="wrap"> 
  
  <table align="left" cellpadding="1" width="100%" cellspacing="1" border="0" >
    <tr>
      <td valign="top"><table align="left" cellpadding="1" width="100%" cellspacing="1" border="0" >
          <tr height="30">
            <td><div style="float: left"><h2 style="color: #795548;font-weight: 900">WordPress to SugarCRM Lead module</h2></div><div style="float: right"><a href="#proEdition" class="OEPL_get_pro">Get Pro version</a></div></td>
          </tr>
          <tr id="trSugarAPI" >
            <td valign="top" style=""><form method="post" action="">
                <table align="left" cellpadding="0" cellspacing="0" border="0" class="OEPL_form_table OEPL_sugar_api_table">
                  <tr>
                  	<td colspan="2" class="OEPL_form_header"><h3 style="color: #FFFFFF;padding-left: 15px"><img style="margin-right: 10px" align="absmiddle" src="<?php echo OEPL_PLUGIN_URL; ?>image/api_settings_icon.png" />SugarCRM REST API Settings</h3></td>
                  </tr>
                  <tr height="10px"></tr>
                  <tr>
                    <th align="right">SugarCRM URL : </th>
                    <td><input name="OEPL_SUGARCRM_URL" type="text" id="OEPL_SUGARCRM_URL" value="<?php echo get_option('OEPL_SUGARCRM_URL'); ?>" size="50" required="required"/></td>
                  </tr>
                  <tr>
                  	<td></td>
                  	<td><span class="description"><a href="http://support.sugarcrm.com/02_Documentation/04_Sugar_Developer/Sugar_Developer_Guide_7.2/70_API/Web_Services/00_API_Versioning/" target="_blank">Click here</a> to refer REST API url for your SugarCRM version.</span></td>
                  </tr>
                  <tr height="8"></tr>
                  <tr>
                    <th align="right">SugarCRM Admin User : </th>
                    <td><input name="OEPL_SUGARCRM_ADMIN_USER" autocomplete="off" type="text" id="OEPL_SUGARCRM_ADMIN_USER" value="<?php echo get_option('OEPL_SUGARCRM_ADMIN_USER'); ?>" size="50" required="required" /></td>
                  </tr>
                  <tr height="8"></tr>
                  <tr>
                    <th align="right" valign="top">SugarCRM Admin Password : </th>
                    <td><input name="OEPL_SUGARCRM_ADMIN_PASS" autocomplete="off" type="text" id="OEPL_SUGARCRM_ADMIN_PASS" value="" required="required" size="50" /></td>
                  </tr>
                  <tr height="8"></tr>
                  <tr>
                  	<td colspan="2" style="padding-left: 15px"><strong>Is your SugarCRM .htaccess protected ?</strong>&nbsp;&nbsp;<input type="checkbox" name="OEPL_is_htacess_protected" id="OEPL_is_htacess_protected" <?php echo $htacessCbx; ?> /></td>
                  </tr>
                  <tr height="8"></tr>
                  <tr class="OEPL_htaccess_tr" style="display: none">
                  	<th align="right">.htaccess UserName :</th>
                  	<td>
                  		<input type="text" name="Oepl_Htaccess_Admin_User" id="Oepl_Htaccess_Admin_User" value="<?php echo $htaccessUsername; ?>" />
                  	</td>
                  </tr>
                  <tr height="8"></tr>
                  <tr class="OEPL_htaccess_tr" style="display: none">
                  	<th align="right">.htaccess Password :</th>
                  	<td>
                  		<input type="text" name="Oepl_Htaccess_Admin_Pass" id="Oepl_Htaccess_Admin_Pass" />
                  	</td>
                  </tr>
                  <tr height="8"></tr>
                  <tr>
                    <td></td>
                    <td><input type="button" name="testConn" id="testConn" value="Test Connection" class="button button-large" />
                      &nbsp;&nbsp;
                      <input type="submit" value="<?php _e('Save Changes') ?>" class="button button-primary button-large OEPLsaveConfig" />
                      &nbsp;&nbsp;
                      <?php if(get_option('OEPL_SUGARCRM_ADMIN_PASS') && get_option('OEPL_SUGARCRM_ADMIN_PASS') != '' ){ ?>
                      <input type="button" value="<?php _e('Synchronize Lead Fields') ?>" id="LeadFldSync" class="button button-primary button-large" />
                      <?php } ?></td>
                  </tr>
                </table>
              </form></td>
          </tr>
          <tr height="20px"></tr>
          
          <tr>
            <td style=""><table cellpadding="0" cellspacing="0" class="OEPL_form_table OEPL_general_table">
                <tr class="">
                	<td colspan="2" class="OEPL_form_header" valign="center"><h3 style="color: #FFFFFF;padding-left: 15px"><img style="margin-right: 10px" align="absmiddle" src="<?php echo OEPL_PLUGIN_URL; ?>image/settings_icon.png" />Plugin Genaral Settings</h3></td>
                </tr>
                <tr height="10px"></tr>
                
                <tr>
                  <th align="right">Capture Remote IP :</th>
                  <td><input type="checkbox" name="IPaddrStatus" value="1" class="IPaddrStatus" <?php echo $IP_checked; ?> /></td>
                </tr>
                <tr>
                  <td></td>
                  <td><span class="description">If check box is checked then plug-in will pass user's Remote IP Address to your SugarCRM Lead module. <br /><div class="OEPL_highlight_this"><strong>To use this feature – you must create a custom field in your SugarCRM Lead module with field-name 'lead_remote_ip'.</div></span></td>
                </tr>
                <tr height="8"></tr>
                <tr>
                  <th align="right">Get Email Notification :</th>
                  <td><input type="checkbox" name="EmailNotification" id="EmailNotification" <?php echo $Email_checked; ?> /></td>
                </tr>
                <tr>
                  <td></td>
                  <td><span class="description">Receive email notification whenever a new lead is generated.</span></td>
                </tr>
                <tr height="8"></tr>
                <tr class="EmailToTR" style="display: none">
                  <th align="right">Send Email to :</th>
                  <td><input type="text" name="EmailReceiver" size="50" id="EmailReceiver" value="<?php echo $EmailNotificationRx; ?>" /></td>
                </tr>
                <tr class="EmailToTR" style="display: none">
                  <td></td>
                  <td><span class="description">Provide Email address to which notification will be sent. Multiple recipients may be specified using a comma ( , ) separated string. </span></td>
                </tr>
                <tr height="8"></tr>
                <tr>
                	<th align="right">Redirect after submit :</th>
                	<td><input type="checkbox" name="OEPL_redirect_user" id="OEPL_redirect_user" value="" <?php echo $redirectCbx; ?> /></td>
                </tr>
                <tr>
                  <td></td>
                  <td><span class="description">Redirect user to any page you want after lead is successfully generated.</span></td>
                </tr>
                <tr height="8"></tr>
                <tr class="OEPL_redirect_tr" style="display: none">
                	<th align="right">Redirect to :</th>
                	<td><input type="text" name="OEPL_redirect_user_to" id="OEPL_redirect_user_to" value="<?php echo $redirectTo; ?>" size="50" /></td>
                </tr>
                <tr class="OEPL_redirect_tr" style="display: none">
                  <td></td>
                  <td><span class="description">Please enter URL including http://...<br /><strong>example :</strong> http://www.DomainName.com/</span></td>
                </tr>
                <tr height="8"></tr>
                <tr>
                  <th></th>
                  <td><?php submit_button('Save Settings','primary','OEPL_save_general_settings',false); ?></td>
                </tr>
              </table></td>
          </tr>
          <tr height="20px"></tr>
          
          
          <tr>
          	  <td style=""><table cellpadding="0" cellspacing="0" class="OEPL_form_table OEPL_messages_table" >
                <tr class="">
                	<td colspan="2" class="OEPL_form_header" valign="center"><h3 style="color: #FFFFFF;padding-left: 15px"><img style="margin-right: 10px" align="absmiddle" src="<?php echo OEPL_PLUGIN_URL; ?>image/settings_icon.png" />General Messages</h3></td>
                </tr>
                <tr height="10px"></tr>
                <tr>
                  <td>
                  	<strong>#Success Message : </strong><span class="description">message to be displayed when lead is successfully generated.</span><br />
                  	<input type="text" class="SuccessMessage" name="SuccessMessage" value="<?php echo $successMsg; ?>" id="" class="" size="75" /></td>
                </tr>
                <tr height="10"></tr>
                <tr>
                  <td>
                  	<strong>#Failure Message : </strong><span class="description">message to be displayed when plugin cannot submit lead to SugarCRM.</span><br />
                  	<input type="text" class="FailureMessage" name="FailureMessage" value="<?php echo $failureMsg; ?>" id="" class="" size="75" /></td>
                </tr>
                <tr height="10"></tr>
                <tr>
                  <td>
                  	<strong>#Required Fields Message : </strong><span class="description">message to be displayed when user is too lazy to fill in the required fields.</span><br />
                  	<input type="text" class="ReqFieldsMessage" name="ReqFieldsMessage" value="<?php echo $ReqFieldsMessage; ?>" id="" class="" size="75" /></td>
                </tr>
                <tr height="10"></tr>
                <tr>
                  <td>
                  	<strong>#Invalid Captcha Message : </strong><span class="description">message to be displayed when user is so week at math that he cannot do simple addtion of two digits.</span><br />
                  	<input type="text" class="InvalidCaptchaMessage" name="InvalidCaptchaMessage" value="<?php echo $InvalidCaptchaMessage; ?>" id="" class="" size="75" /></td>
                </tr>
                <tr height="10"></tr>
                <tr>
                  <td><?php submit_button('Save Messages','primary','OEPL_message_save',false); ?></td>
                </tr>
              </table></td>
          </tr>
          
          <tr height="20px"></tr>
          
          <tr>
          	  <td style=""><table cellpadding="0" cellspacing="0" class="OEPL_form_table" style="">
                <tr class="">
                	<td colspan="2" class="OEPL_form_header" valign="center"><h3 style="color: #FFFFFF;padding-left: 15px"><img style="margin-right: 10px" align="absmiddle" src="<?php echo OEPL_PLUGIN_URL; ?>image/settings_icon.png" />Custom CSS</h3></td>
                </tr>
                <tr>
                	<td style="padding: 5px 10px;">
                		<textarea rows="10" style="width: 100%;font-family: Consolas,Monaco,monospace;" name="OEPL_custom_css" id="OEPL_custom_css" ><?php echo get_option("OEPL_Form_Custom_CSS"); ?></textarea>
                		<p class="description">You can write down your custom css here. Be sure to wrap your styles in "&lt;style&gt; &lt;/style&gt;" <br />Click here for basic <a onclick="jQuery('#OEPL_form_structure').toggle()" style="cursor: pointer">Form Structure</a></p>
                			<div id="OEPL_form_structure" style="display: none">
<xmp style="cursor:copy">
<p>
  // Form Message will be displayed here
  <div class='LeadFormMsg' style='color:red'></div>
</p> 
<form id="OEPL_Widget_Form" >
  <p>
    <label><strong>LABEL HERE <span style="color: red">*</span> :</strong></label><br>
    <input type="text" id="" name=""> // input box for each field
  </p>
  <p class="WidgetLeadFormCaptcha"><label>What is &nbsp;<strong>8 + 9</strong> ?</label>
    <input type="text"  id="captcha" name=""> // input box for captcha
  </p>
  <p>
    <input type="submit" id="WidgetFormSubmit" value="Submit" name="submit">
  </p>
</form>
</xmp>
	</div>
                	</td>
                </tr>
                <tr>
                  <td style="padding: 5px 10px;"><?php submit_button('Save Custom CSS','primary','OEPL_css_save',false); ?></td>
                </tr>
              </table></td>
          </tr>
          
          <tr id="proEdition" height="20px"></tr>
          
          <tr>
            <td valign="top" style="">
                <table align="left" cellpadding="0" cellspacing="0" border="0" class="OEPL_form_table">
                	<img class="OEPL_pro_img" src="<?php echo OEPL_PLUGIN_URL; ?>image/OEPL_pro_1.png" />
                  <tr>
                  	<td colspan="2" class="OEPL_form_header"><h3 style="color: #FFFFFF;padding-left: 60px"> Wordpress to SugarCRM form builder PRO features</h3></td>
                  </tr>
                  <tr height="10px"></tr>
                  <tr>
                  	<td style="padding: 0px 15px;">
                  		<p style="font-size: 14px;"><em>We are proud to announce our new ultimate, feature packed & powerfull '<strong>Wordpress to SugarCRM form builder PRO</strong>' plugin.</em></p>
                  		<p>Thank you for using our Plugin. It's always nice to have more on your plate. Our PRO edition will  help you to <strong>generate unlimited web-forms</strong> in few simple steps via user friendly drag & drop designed based  form-builder.</p>
                  		<p>If you didn't know then let me tell you that our plugin is also compitible with <strong><em>SuiteCRM</em></strong>.</p>
                  		<p><strong class="OEPL_highlight_this OEPL-red">Pro Version Features</strong><a class="OEPL-link" href="http://in.pinterest.com/dipeshpatel1612/wordpress-to-sugarcrm-form-builder/" style="margin-left: 15px" target="_blank">ScreenShots</a>
                  			<ul style="padding: 0px 15px;">
                  			<li>- User-friendly Drag & Drop form builder.</li>
                  			<li>- Unlimited Forms with different set of fields.</li>
                  			<li>- Different field settings for each form like field Display-order , Hidden-fields value & Redirect on Success.</li>
                  			<li>- Additional email notification option for each form.</li>
                  			<li>- Create multiple forms and use Shortcode generated anywhere on your WordPress site.</li>
                  			<li>- Get premium support. </li>
                  			</ul>
                  		</p>
                  		<p><a href="http://goo.gl/dB8I1f" class="OEPL-link" style="" target="_blank">Click here</a> for detailed video walk through of our '<strong>Wordpress to SugarCRM form builder PRO</strong>'.</p>
                  		<p><a class="OEPL-link" target="_blank" href="http://goo.gl/h0RJE6">Click here</a> to submit your inquiry to get PRO plugin and more detail about it.</p>
                  	</td>
                  </tr>
                  
                </table>
              </td>
          </tr>
          
        </table></td>
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
            <td align="center">
            	<a href="http://goo.gl/4if8Iz" target="_blank"><img class="Social_icons" src="<?php echo OEPL_PLUGIN_URL.'image/fb_icon.png'?>" alt="Facebook logo" title="Follow us on facebook" /></a> 
            	<a href="http://goo.gl/tFfSk1" target="_blank"><img class="Social_icons" src="<?php echo OEPL_PLUGIN_URL.'image/google_plus_icon.png'?>" alt="Google+ logo" title="Follow us on google+" /></a> 
            	<a href="http://goo.gl/ThCEqJ" target="_blank"><img class="Social_icons" src="<?php echo OEPL_PLUGIN_URL.'image/youtube_icon.png'?>" alt="youtube logo" title="Subscribe to our youtube channel" /></a>
            </td>
          </tr>
          <tr>
          	<td class="OEPL_promotion_tab" ><div align="center" style="font-size: 14px;font-weight: 600">We'd love to hear from you</div><br />Would you mind taking a few minutes to write a review for us please ? It will be very helpful to us & will keep us motivated.<br /><br /><strong><a href="https://wordpress.org/support/view/plugin-reviews/wp-widget-sugarcrm-lead-module" target="_blank">Click here</a></strong> to submit your thoughts.</td>
          </tr>
          <tr>
          	<td class="OEPL_promotion_tab" >Our goal is to expand this plugin as much as possible. If you want to support our continuous dedication & efforts. Please donate to our <em><strong>PayPal</strong></em> account <em><strong>“dipesh.99869@gmail.com”</strong></em>. We will very much appreciated that.</td>
          </tr>
          <tr height="10"></tr>
          <tr><td><img style="width: 280px" src="<?php echo OEPL_PLUGIN_URL; ?>image/OEPL_slogan.png" /></td></tr>
        </table></td>
    </tr>
  </table>
</div>
<style>
	
</style>