<?phpif (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {    exit('Please don\'t access this file directly.');}
function getHTMLElement($type, $nameID, $values, $setValue, $class='', $jqueryCls='', $extra){	$element = '';	switch($type)	{		case 'text':			$element = '<input type="text" name="' . $nameID . '" id="' . $nameID . '" value="' . $setValue . '" class="' . $class . $jqueryCls . '" '.$extra.' />';			break;		case 'select':			$element = '<select name="' . $nameID . '" class="' . $jqueryCls . '" id="' . $nameID . '" '.$extra.'>';			$sObj = unserialize($values);
			foreach($sObj as $k=>$v)			{
				if($v->value == $setValue) $selected = 'selected="selected"';				else  $selected = '';				$element .= '<option value="' . $v->value . '" ' . $selected . '>' . $v->name . '</option>';			}			$element .= '</select>';			break;
		case 'radio':			$sObj = unserialize($values);			foreach($sObj as $k=>$v)			{				if($v->value == $setValue) $selected = 'checked="checked""';				else  $selected = '';				$element .= '<input type="radio" name="' . $nameID . '" id="' . $nameID . '_' . $v->value . '" value="' . $v->value . '" class="Hradio ' . $class . $jqueryCls . '" ' . $selected . ' '.$extra.'/>&nbsp;' . $v->name . '&nbsp;&nbsp;';		    			}			break;
		case 'checkbox':			$element = '<select name="' . $nameID . '" class="' . $jqueryCls . '" id="' . $nameID . '">';			$sObj = unserialize($values);			foreach($sObj as $k=>$v)			{				if($v->value == $setValue) $selected = 'selected="selected"';				else  $selected = '';				if($v->name != '' && $v->value != '')						$element .= '<option  value="' . $v->value . '" '.$extra.' ' . $selected . '>' . $v->value . '</option>';			}			$element .= '</select>';			break;
		case 'textarea':			$element = '<textarea cols="30" class="' . $jqueryCls . '" rows="5" name="' . $nameID . '" '.$extra.' id="' . $nameID . '">' . $setValue . '</textarea>';			break;
		default:			$element = '';			break;	}
	return $element;}
function NumFrmt($val, $separator = ','){	$val = str_replace(',', '', $val);	if($val == '') return $val;	$val = number_format($val, 0, '', $separator);	return $val;}
function NumFrmtForTraget($val, $separator = ','){	$val = str_replace(',', '', $val);	if($val == '') return $val;	$val = number_format($val, 0, '', $separator);	return $val;}?>