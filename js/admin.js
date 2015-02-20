jQuery.fn.center = function () {	this.css("position","absolute");	this.css("top", ( jQuery(window).height() - this.height() ) / 2 + jQuery(window).scrollTop() + "px");	this.css("left", ( jQuery(window).width() - this.width() ) / 2 + jQuery(window).scrollLeft() + "px");	return this;}
jQuery.fn.doesExist = function(){    return jQuery(this).length > 0;};
jQuery(document).ready(function() {	if(jQuery(".IPaddrStatus").is(':checked') == true){		jQuery('.OEPL_highlight_this').addClass('OEPL-red');	}	jQuery(".IPaddrStatus").change(function(){		if(jQuery(this).is(':checked') == true){			jQuery('.OEPL_highlight_this').addClass('OEPL-red');		} else {			jQuery('.OEPL_highlight_this').removeClass('OEPL-red');		}	});		if(jQuery("#OEPL_redirect_user").is(':checked') == true){		jQuery('.OEPL_redirect_tr').show();	}	jQuery("#OEPL_redirect_user").change(function(){		if(jQuery(this).is(':checked') == true){			jQuery('.OEPL_redirect_tr').show();		} else {			jQuery('.OEPL_redirect_tr').hide();		}	});		if(jQuery("#OEPL_is_htacess_protected").is(':checked') == true){		jQuery('.OEPL_htaccess_tr').show();	}	jQuery("#OEPL_is_htacess_protected").change(function(){		if(jQuery(this).is(':checked') == true){			jQuery('.OEPL_htaccess_tr').show();		} else {			jQuery('.OEPL_htaccess_tr').hide();		}	});		/********************************************	 *Front End widget form submit function START 	 ********************************************/ 	var options = { 		target:        	'.LeadFormMsg',	    beforeSubmit:  	showRequest,	    success:		showResponse,	    url:			ajaxurl	}; 	jQuery('#OEPL_Widget_Form').ajaxForm(options); 		function showRequest(formData, jqForm, options) {		var ReturnFlag = false;		var Error = '';		jQuery("#OEPL_Widget_Form p .LeadFormRequired").each(function(){			jQuery(this).removeClass('InvalidInput');			if(jQuery(this).val() == ''){				jQuery(this).addClass('InvalidInput');				Error = ReqFieldMsg;				ReturnFlag = true;			}		});		if(ReturnFlag == true)		{			jQuery('.LeadFormMsg').html(Error);			jQuery('html, body').animate({		        scrollTop: jQuery('.widget_oepl_lead_widget').offset().top		    }, 400);			return false;		}		jQuery('#AjaxWaiting').show();		jQuery('.LeadFormMsg').html('');		jQuery("#WidgetFormSubmit").val("Please Wait...");		jQuery("#WidgetFormSubmit").addClass('loadingBtn');	}	function showResponse(response, statusText, xhr, $form)  {		var json = jQuery.parseJSON(response);		if(json.redirectStatus == 'Y'){			 var url = json.redirectTo;			 window.location = url;		} else {			jQuery('.WidgetLeadFormCaptcha').load(' .WidgetLeadFormCaptcha');			if (json.success == 'Y'){				jQuery("#OEPL_Widget_Form p .nonHidden").each(function(){					jQuery(this).val('');				});			}			jQuery('.LeadFormMsg').html(json.message);			jQuery("#WidgetFormSubmit").val("Submit");			jQuery("#WidgetFormSubmit").removeClass('loadingBtn');			jQuery('html, body').animate({				scrollTop: jQuery('.widget_oepl_lead_widget').offset().top			}, 400);			jQuery('#AjaxWaiting').hide();		}		return false;	}	/******************************************	 *Front End widget form submit function END 	 ******************************************/		if(jQuery('#EmailNotification').is(':checked') == true)		jQuery(".EmailToTR").show();	jQuery('#EmailNotification').change(function(){		if(jQuery(this).is(':checked') == true)			jQuery(".EmailToTR").show();		else			jQuery(".EmailToTR").hide();	});		jQuery(".OEPL_hide_panel").click(function(){		if(jQuery(this).attr("is_show") == "No"){			jQuery(this).attr("is_show","Yes");			jQuery(this).attr('src',OEPL_PLUGIN_URL+'image/minus-icon.png');			jQuery(".OEPL_hidden_panel").show();		} else {			jQuery(this).attr("is_show","No");			jQuery(this).attr('src',OEPL_PLUGIN_URL+'image/plus-icon.png');			jQuery(".OEPL_hidden_panel").hide();		}	});		jQuery(".OEPL_Delete_Cust_Field").click(function(){		var agree=confirm("Are you sure you want to delete this field ?");		if (agree) {			var pid = jQuery(this).attr('pid');			data = {};			data.action = 'OEPL_Custom_Field_Delete';			data.pid 	= pid;			jQuery('#AjaxWaiting').show();			jQuery.post(ajaxurl,data,function(response){				jQuery('#AjaxWaiting').hide();				if (response == 'Field deleted successfully'){					window.location.reload();				} else {					alert(response);				}			});		} else {			return false;		}	});		jQuery(".OEPL_Custom_Field_Add").click(function(){		data = {};		var Field_Name = jQuery("#OEPL_Custom_Field_Name").val();			if(Field_Name == ''){			alert("Field Name could not be blank ! Please try again");			return false;		}		data.action 	= 'OEPL_Custom_Field_Save';		data.Field_Name	= Field_Name;		jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl,data,function(response){			jQuery('#AjaxWaiting').hide();			if (response == 'Field added successfully'){				window.location.reload();			} else {				alert(response);			}		});		return false;	});		jQuery(".OEPLsaveConfig").click(function(){		data = {};		var SugarURL 	= jQuery('#OEPL_SUGARCRM_URL').val();		var SugarUser 	= jQuery('#OEPL_SUGARCRM_ADMIN_USER').val();		var SugarPass 	= jQuery('#OEPL_SUGARCRM_ADMIN_PASS').val();		if(SugarURL == '' || SugarURL == null){			alert ('Please provide SugarCRM URL');			jQuery('#OEPL_SUGARCRM_URL').focus();			return false;		} else if(SugarUser == '' || SugarUser == null){			alert ('Please provide SugarCRM Admin User');			jQuery('#OEPL_SUGARCRM_ADMIN_USER').focus();			return false;		} else if(SugarPass == '' || SugarPass == null){			alert ('Please provide SugarCRM Admin Password');			jQuery('#OEPL_SUGARCRM_ADMIN_PASS').focus();			return false;		}		var isHtaccessProtected = '';		if(jQuery('#OEPL_is_htacess_protected').is(':checked') == true)			isHtaccessProtected = 'Y';		else 			isHtaccessProtected = 'N';				data.action = 'OEPLsaveConfig';		data.SugarURL 	= SugarURL;		data.SugarUser 	= SugarUser;		data.SugarPass 	= SugarPass;				data.isHtaccessProtected = isHtaccessProtected;		if(isHtaccessProtected == 'Y'){			var HtaccessUser = jQuery("#Oepl_Htaccess_Admin_User").val();			var HtaccessPass = jQuery("#Oepl_Htaccess_Admin_Pass").val();			if (HtaccessUser == '' || HtaccessUser == null){				alert ("Please provide .htaccess Username");				jQuery('#Oepl_Htaccess_Admin_User').focus();				return false;			} else if(HtaccessPass == '' || HtaccessPass == null) {				alert ("Please provide .htaccess Password");				jQuery('#Oepl_Htaccess_Admin_Pass').focus();				return false;			}			data.HtaccessUser = HtaccessUser;			data.HtaccessPass = HtaccessPass;		}				jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl,data,function(response){			alert(response);			if(response == 'Configuration saved successfully'){				window.location.reload();			}			jQuery('#AjaxWaiting').hide();		});		return false;	});		jQuery(".DatePicker").datetimepicker({		format:'m/d/Y',		timepicker:false,		closeOnDateSelect:true,	});	jQuery(".DateTimePicker").datetimepicker({		format:'m/d/Y H:i',		closeOnDateSelect:true,	});	jQuery('#OEPL-Leads_table #doaction').click(function(){		var noOfChecked = 0;		jQuery("#OEPL-Leads_table .LeadTableCbx").each(function(){			var CbxChecked = jQuery(this).prop("checked");			if(CbxChecked) noOfChecked++;		})		if(noOfChecked <= 0){			alert ("Please select atleast record to update");			return false;		}	})	jQuery('.LeadTableCbx').change(function(){		check = jQuery(this).prop("checked");		if(check){			jQuery(this).parent().parent().addClass('CbxChecked');		} else {			jQuery(this).parent().parent().removeClass('CbxChecked');		}	});		jQuery('#cb-select-all-1').change(function(){		check = jQuery(this).prop("checked");		if(check){			jQuery(".wp-list-table tbody tr").addClass('CbxChecked');		} else {			jQuery(".wp-list-table tbody tr").removeClass('CbxChecked');		}	});		jQuery('#LeadFldSync').click(function(){		data = {};		data.action = 'LeadFieldSync';		jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl,data,function(response){			alert(response);			jQuery('#AjaxWaiting').hide();		});		return false;	});		jQuery("#OEPL_message_save").click(function(){		var data = {};		data.SuccessMessage			= jQuery(".SuccessMessage").val();		data.FailureMessage			= jQuery(".FailureMessage").val();		data.ReqFieldsMessage		= jQuery(".ReqFieldsMessage").val();		data.InvalidCaptchaMessage	= jQuery(".InvalidCaptchaMessage").val();		data.action = "GeneralMessagesSave";		jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl,data,function(response){			alert(response);			jQuery('#AjaxWaiting').hide();		});		return false;	});		jQuery('#OEPL_save_general_settings').click(function(){		var data = {};		var IPaddrStatus = '';		var EmailNotification = '';		var redirectStatus = '';		if(jQuery('#EmailNotification').is(':checked') == true)			EmailNotification = 'Y';		else 			EmailNotification = 'N';				if(jQuery('.IPaddrStatus').is(':checked') == true)			IPaddrStatus = 'Y';		else 			IPaddrStatus = 'N';					if(jQuery("#OEPL_redirect_user").is(':checked') == true){			redirectStatus = 'Y';		} else {			redirectStatus = 'N';		}		data.action = "GeneralSettingSave";		data.IPaddrStatus		= IPaddrStatus;		data.EmailNotification	= EmailNotification;		data.EmailReceiver		= jQuery("#EmailReceiver").val();		data.redirectStatus		= redirectStatus;		data.redirectTo			= jQuery("#OEPL_redirect_user_to").val();		jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl,data,function(response){			alert(response);			jQuery('#AjaxWaiting').hide();		});		return false;	});		jQuery(".FileEach").change(function(evt){		if (window.FormData) {	  		formdata = new FormData();			var i = 0, len = this.files.length, img, reader, file;			for ( ; i < len; i++ ) {				file = this.files[i];				if (!!file.type.match(/image.*/)) {					if ( window.FileReader ) {						reader = new FileReader();						reader.onloadend = function (e) { 						};						reader.readAsDataURL(file);					}					if (formdata) {						formdata.append("images[]", file);					}				}				}			} else {			formdata = '';		}		return false;	});		jQuery('.OEPLIntInput').keydown(function(e) {    switch( e.keyCode ) {   		case 9:case 97:case 98:case 96:case 48:case 99:case 100:case 101:case 102:case 103:case 104:case 105:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 37:case 39:case 8:case 13:case 46:case 116:case 17:return;}  		e.preventDefault();    });		jQuery("#testConn").click(function(){		var data = {};		data.action = 'TestSugarConn';				var isHtaccessProtected = '';		if(jQuery('#OEPL_is_htacess_protected').is(':checked') == true)			isHtaccessProtected = 'Y';		else 			isHtaccessProtected = 'N';				data.isHtaccessProtected = isHtaccessProtected;		if(isHtaccessProtected == 'Y'){			var HtaccessUser = jQuery("#Oepl_Htaccess_Admin_User").val();			var HtaccessPass = jQuery("#Oepl_Htaccess_Admin_Pass").val();			if (HtaccessUser == '' || HtaccessUser == null){				alert ("Please provide .htaccess Username");				jQuery('#Oepl_Htaccess_Admin_User').focus();				return false;			} else if(HtaccessPass == '' || HtaccessPass == null) {				alert ("Please provide .htaccess Password");				jQuery('#Oepl_Htaccess_Admin_Pass').focus();				return false;			}			data.HtaccessUser = HtaccessUser;			data.HtaccessPass = HtaccessPass;		}				data.URL  = jQuery("#OEPL_SUGARCRM_URL").val();		data.USER = jQuery("#OEPL_SUGARCRM_ADMIN_USER").val();		data.PASS = jQuery("#OEPL_SUGARCRM_ADMIN_PASS").val();		jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl, data, function(response) {			jQuery('#AjaxWaiting').hide();			alert(response);		});		return false;	});
});