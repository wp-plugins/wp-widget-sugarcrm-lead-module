=== WordPress to SugarCRM Lead ===
Contributors: Offshore Evolution Pvt Ltd
Support : http://www.offshoreevolution.com/
Donate link: 
Tags: SugarCRM, Sugar CRM, web-to-lead SugarCRM, web-to-lead Sugar CRM, contact form Sugarcrm, contact us sugarCRM, web to lead sugarCRM, SugarCRM customization, SugarCRM customer portal, SugarCRM WordPress integration, collect info and send to Sugar CRM
Minimum Requirement : 3.4
Requires at least: 3.4
Tested up to: 4.0.1
Stable tag: 2.1.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress to SugarCRM Lead Plug-in submit your custom form data to you SugarCRM Lead module. This will provided Widget to Connect your SugarCRM.

== Description ==
                   
WordPress to SugarCRM Lead plugin will provide you a Widget to display a Lead form at your WordPress front end using fields which are mapped with Lead module. Widget will draw the fields on the bases of “Order By” settings with value “On Widget”. For more information refer screen-shots.

For SugarCRM Settings :
SugarCRM URL :- Set your REST API SugarCRM URL. For customized SugarCRM, REST API URL would be “http OR https://&lt;Your Domain Name&gt;/service/v4_1/rest.php”. Service URL may be depend on your SugarCRM version. Please check your SugarCRM version and get REST API URL from below URL :

http://support.sugarcrm.com/02_Documentation/04_Sugar_Developer/Sugar_Developer_Guide_7.2/70_API/Web_Services/00_API_Versioning/

* You will require SugarCRM administrator level credentials to Plug-in it.
* You can use Widget one time only on each page, if will be display 2 time at single page – it will not work.
* You must save your SugarCRM URL,username & password without which plugin won't work.
* To use 'Pass user Remote Address with every lead' function you must create custom field in your SugarCRM lead module named 'lead_remote_ip'.
* Now make any field hidden & provide it's value in Widget arguments in Widgets page.

== Installation ==

1. Install plugin OR unzip the downloaded file to /wp-content/plugins/
2. Go to your plugin settings and enable the plugin.
3. Go to Widget setting and set widget where you want to show Lead Form.

== Frequently Asked Questions ==

1. Which user name I need to set? 

Answer: You must have to set Administrator level user/pass to submit form data into SugarCRM Lead module. Once you have successfully tested your credentials, Click on SAVE button to save your User/Password into WordPress database.

2.Leads not generating on SugarCRM side ?

Answer : You haven't saved your SugarCRM credentials properly. Go to your SugarCRM menu check your login details once again by 'Test connection' and then click 'Save changes'. It should work now provided if details are correct.

3. Is my credentials safe on WordPress?

Answer: Yes, Your credentials will be stored in MD5 format. So, nobody can access or read your credentials even from database.

== Screenshots ==

1. Plugin settings page
2. List of Lead module fields
3. Form on a webpage using Wdiget

== Release ==
2.1.6

== Upgrade Notice ==
Not Applicable

== Change log ==
<strong>Version 2.1.5</strong><br />
Release Date : 17th December 2014

1. Changed the method of saving SugarCRM settings to prevent conflicts.

<strong>Version 2.0</strong><br />
Release Date : 9th December 2014

1. Now you can capture user's Remote Address with every lead.
2. Option to pass any field as hidden field. You can set value for that hidden field in Widget arguments.

<strong>Version 1.5</strong><br />
Release Date : 27th November 2014

1. Now you can change the Label for any field.
2. Option to mark any field mandatory.
3. Provided calander plugin for date & datetime fields.
