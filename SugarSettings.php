<?phpif (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {    exit('Please don\'t access this file directly.');}?><div class="wrap">  <h2>WordPress to SugarCRM Lead module Plugin by <a href="http://www.offshoreevolution.com/" target="_blank">OffshoreEvolution.com</a></h2>  <table align="left" cellpadding="1" cellspacing="1" border="0" >    <tr height="20">      <td>&nbsp;</td>    </tr>    <tr>      <td valign="middle"><h2>SugarCRM REST API Settings</h2></td>    </tr>    <tr id="trSugarAPI" >      <td valign="top" style="padding-left:50px !important;"><form method="post" action="options.php">
          <input type="hidden" name="action" value="update" />
          <input type="hidden" name="page_options" value="OEPL_SUGARCRM_URL, OEPL_SUGARCRM_ADMIN_USER,OEPL_SUGARCRM_ADMIN_PASS" />
          <?php wp_nonce_field('update-options'); ?>
<table align="left" cellpadding="1" cellspacing="1" border="0" >
<tr><th align="right">SugarCRM URL : </th><td><input name="OEPL_SUGARCRM_URL" type="text" id="OEPL_SUGARCRM_URL" value="<?php echo get_option('OEPL_SUGARCRM_URL'); ?>" size="50" required="required"/></td></tr>
<tr><th align="right">SugarCRM Admin User : </th><td><input name="OEPL_SUGARCRM_ADMIN_USER" autocomplete="off" type="text" id="OEPL_SUGARCRM_ADMIN_USER" value="<?php echo get_option('OEPL_SUGARCRM_ADMIN_USER'); ?>" size="50" required="required" /></td></tr><tr><th align="right" valign="top">SugarCRM Admin Password : </th><td><input name="OEPL_SUGARCRM_ADMIN_PASS" autocomplete="off" type="text" id="OEPL_SUGARCRM_ADMIN_PASS" value="" required="required" size="50" /></td></tr><tr><td></td><td><input type="button" name="testConn" id="testConn" value="Test Connection" /><em></em>
&nbsp;&nbsp;<input type="submit" value="<?php _e('Save Changes') ?>" class="button button-primary button-large" />&nbsp;&nbsp;<?php if(get_option('OEPL_SUGARCRM_ADMIN_PASS') && get_option('OEPL_SUGARCRM_ADMIN_PASS') != '' ){ ?><input type="button" value="<?php _e('Synchronize Lead Fields') ?>" id="LeadFldSync" class="button button-primary button-large" /><?php } ?></td></tr></table></form></td></tr><tr>	<td valign="middle">		<h2>Genaral Setting</h2>		<p>You can customize your general settings here.</p>	</td></tr><?php$successMsg 	= get_option('OEPL_SugarCRMSuccessMessage');$failureMsg 	= get_option('OEPL_SugarCRMFailureMessage');$IPaddrStatus 	= get_option('OEPL_auto_IP_addr_status');if($IPaddrStatus == 'Y') $checked = 'checked="checked"'; else $checked = '';?><tr>	<td style="padding-left:50px !important;">		<table>			<tr>	        	<th align="right"><label>Success Message : </label></th>	            <td><input type="text" class="SuccessMessage" name="SuccessMessage" value="<?php echo $successMsg; ?>" id="" class="" size="50" /></td>	        </tr>	        <tr>	        	<th align="right"><label>Failure Message : </label></th>	            <td><input type="text" class="FailureMessage" name="FailureMessage" value="<?php echo $failureMsg; ?>" id="" class="" size="50" /></td>	        </tr>	        <tr><td></td><td>Pass user Remote address with every lead ?</td></tr>	        <tr>	        	<th align="right">Pass Remote IP :</th>	        	<td><input type="checkbox" name="IPaddrStatus" value="1" class="IPaddrStatus" <?php echo $checked; ?> /></td>	        </tr>	    	<tr><td></td><td><span class="description">You must create a custom field in your SugarCRM lead module named <strong>'lead_remote_ip'</strong> in order to use this feature.</span></td></tr>	        <tr>				<th></th>	            <td><?php submit_button('Save Settings','primary','SugarGeneralMsgSubmit',false); ?></td>	        </tr>		</table>	</td></tr>
</table>
</div>