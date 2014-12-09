jQuery.fn.center = function () {	this.css("position","absolute");	this.css("top", ( jQuery(window).height() - this.height() ) / 2 + jQuery(window).scrollTop() + "px");	this.css("left", ( jQuery(window).width() - this.width() ) / 2 + jQuery(window).scrollLeft() + "px");	return this;}
jQuery.fn.doesExist = function(){    return jQuery(this).length > 0;};
jQuery(document).ready(function() {		jQuery(".DatePicker").datetimepicker({		format:'m/d/Y',		timepicker:false,		closeOnDateSelect:true,	});	jQuery(".DateTimePicker").datetimepicker({		format:'m/d/Y H:i',		closeOnDateSelect:true,	});	jQuery('#OEPL-Leads_table #doaction').click(function(){		var noOfChecked = 0;		jQuery("#OEPL-Leads_table .LeadTableCbx").each(function(){			var CbxChecked = jQuery(this).prop("checked");			if(CbxChecked) noOfChecked++;		})		if(noOfChecked <= 0){			alert ("Please select atleast record to update");			return false;		}	})	jQuery('.LeadTableCbx').change(function(){		check = jQuery(this).prop("checked");		if(check){			jQuery(this).parent().parent().addClass('CbxChecked');		} else {			jQuery(this).parent().parent().removeClass('CbxChecked');		}	});		jQuery('#cb-select-all-1').change(function(){		check = jQuery(this).prop("checked");		if(check){			jQuery(".wp-list-table tbody tr").addClass('CbxChecked');		} else {			jQuery(".wp-list-table tbody tr").removeClass('CbxChecked');		}	});		jQuery('#LeadFldSync').click(function(){		data = {};		data.action = 'LeadFieldSync';		jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl,data,function(response){			alert(response);			jQuery('#AjaxWaiting').hide();		});		return false;	});		jQuery('#SugarGeneralMsgSubmit').click(function(){		var data = {};		var IPaddrStatus = '';		if(jQuery('.IPaddrStatus').is(':checked') == true)			IPaddrStatus = 'Y';		else 			IPaddrStatus = 'N';		data.action = "SugarGeneralMsg";		data.SuccessMessage 	= jQuery('.SuccessMessage').val();		data.FailureMessage 	= jQuery('.FailureMessage').val();		data.IPaddrStatus		= IPaddrStatus;		jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl,data,function(response){			alert(response);			jQuery('#AjaxWaiting').hide();		});		return false;	});		jQuery("#WidgetFormSubmit").on('click',function(){		var ReturnFlag = false;		jQuery("#WidgetForm p .LeadFormRequired").each(function(){			jQuery(this).removeClass('InvalidInput');			if(jQuery(this).val() == ''){				jQuery(this).addClass('InvalidInput');				ReturnFlag = true;			}		});		if(ReturnFlag == true)		{			alert ("Please fill in all required fields");			return false;		}		var data = {};		var captcha = parseInt(jQuery('#captcha').val());		jQuery('.LeadFormMsg').html('');		jQuery(this).val("Please Wait...");		jQuery(this).addClass('loadingBtn');		data.action = 'WidgetForm';		data.captcha = captcha;		jQuery("#WidgetForm p .LeadFormEach").each(function(){			data[jQuery(this).attr('name')] = jQuery(this).val()		});		jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl,data,function(response){			//alert(response);			jQuery('.WidgetLeadFormCaptcha').load(' .WidgetLeadFormCaptcha');			jQuery("#WidgetForm p .nonHidden").each(function(){				jQuery(this).val('');			});			jQuery('.LeadFormMsg').html(response);			jQuery("#WidgetFormSubmit").val("Submit");			jQuery("#WidgetFormSubmit").removeClass('loadingBtn');			jQuery('#AjaxWaiting').hide();		});		return false;	});		jQuery('.OEPLIntInput').keydown(function(e) {    switch( e.keyCode ) {   		case 9:case 97:case 98:case 96:case 48:case 99:case 100:case 101:case 102:case 103:case 104:case 105:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 37:case 39:case 8:case 13:case 46:case 116:case 17:return;}  		e.preventDefault();    });		jQuery("#testConn").click(function(){		var data = {};		data.action = 'TestSugarConn';		data.URL  = jQuery("#OEPL_SUGARCRM_URL").val();		data.USER = jQuery("#OEPL_SUGARCRM_ADMIN_USER").val();		data.PASS = jQuery("#OEPL_SUGARCRM_ADMIN_PASS").val();		jQuery('#AjaxWaiting').show();		jQuery.post(ajaxurl, data, function(response) {			jQuery('#AjaxWaiting').hide();			alert(response);		});		return false;	});
});