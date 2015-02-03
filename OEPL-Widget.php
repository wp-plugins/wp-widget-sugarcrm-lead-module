<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

class OEPL_Lead_Widget extends WP_Widget {
	function __construct() 
	{
		parent::__construct(
			'OEPL_Lead_Widget', 
			__('SugarCRM Lead Form', 'OEPL_Lead_Widget'), 
			array( 'description' => __( 'This Widget will submit data into your SugarCRM Lead module.', 'WPBeginner Widget' ), ) 
		);
	}

	public function widget( $args, $instance ) 
	{
		global $wpdb;
		$query = "SELECT * FROM ".OEPL_TBL_MAP_FIELDS." WHERE is_show = 'Y' ORDER BY display_order ASC";
		$RS = $wpdb->get_results($query,ARRAY_A);
		
		$title = apply_filters( 'widget_title', $instance['OEPL_Widget_Title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		
		echo "<p><div class='LeadFormMsg' style='color:red'></div></p>";
		echo "<form id='OEPL_Widget_Form' method='POST' enctype='multipart/form-data'>";
		echo "<input type='hidden' value='".wp_create_nonce( 'upload_thumb' )."' name='_nonce' />";
  		echo '<input type="hidden" name="action" id="action" value="WidgetForm">';
		foreach ($RS as $module) {
			if($module['hidden'] == 'N')
			{
				### Add Date picker Class if filed type match
				if($module['data_type'] == 'date'){
					$JQclass = 'DatePicker nonHidden';
					$readonly = 'readonly';
				} else if($module['data_type'] == 'datetimecombo') {
					$JQclass = 'DateTimePicker nonHidden';
					$readonly = 'readonly';
				} else if($module['data_type'] == 'file') {
					$JQclass = 'files nonHidden';
					$readonly = '';
				} else {
					$readonly = '';
					$JQclass = 'nonHidden';
				}
				### Add required class if reqiured is true
				if($module['required'] === 'Y'){
					$JQclass .= ' LeadFormRequired';
					$LabelAsterisk = ' <span style="color: red">*</span>';				
				} else {
					$LabelAsterisk = '';
				}
				### Display Custom label if is set
				if($module['wp_custom_label'] && $module['wp_custom_label'] != ''){
					$label = $module['wp_custom_label'].$LabelAsterisk;
				} else {
					$label = $module['wp_meta_label'].$LabelAsterisk;
				}
				echo "<p>";
				echo "<label><strong>".$label." :</strong></label><br>";
				echo getHTMLElement($module['field_type'],$module['wp_meta_key'],$module['field_value'],$module['field_value'],'',$JQclass,$readonly);
				echo "</p>";
			} else {
				echo '<input type="hidden" class="LeadFormEach" name="'.$module['wp_meta_key'].'" value="'.$instance[$module['field_name']].'" />';
			}
		}
		$random1 = rand(1,9);
		$random2 = rand(1,9);
		$_SESSION['OEPL'] = array ();
		$_SESSION['OEPL']['captcha1'] = $random1;
		$_SESSION['OEPL']['captcha2'] = $random2;
		echo "<p class='WidgetLeadFormCaptcha'>What is &nbsp;<strong>".$random1." + ".$random2."</strong>&nbsp; ? <input type='text' name='captcha' id='captcha' size='3' maxlength='3'/></p>" ;
		echo "<p><input type='submit' name='submit' style ='' value='Submit' id='WidgetFormSubmit' class='' ></p>";
		echo "</form>";
		echo $args['after_widget'];
	}
			
	public function form( $instance ) 
	{
		global $wpdb;
		if ( isset( $instance[ 'OEPL_Widget_Title' ] ) ) {
			$title = $instance[ 'OEPL_Widget_Title' ];
		} else {
			$title = __( 'New title', 'wpb_widget_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'OEPL_Widget_Title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'OEPL_Widget_Title' ); ?>" name="<?php echo $this->get_field_name( 'OEPL_Widget_Title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<?php
		$query = "SELECT * FROM ".OEPL_TBL_MAP_FIELDS." WHERE is_show = 'Y' AND hidden='Y' AND custom_field = 'N' ORDER BY display_order ASC";
		$RS = $wpdb->get_results($query,ARRAY_A);
		if(count($RS) > 0)
		{
			echo '<div align="center"><h4>Hidden Attributes</h4></div><hr />';
		}
		foreach($RS as $field){
			if ( isset( $instance[$field['field_name']] ) ) {
				$FieldVal = $instance[$field['field_name']];
			} else {
				$FieldVal = '';
			}
			if($field['data_type'] == 'date'){
				$JQclass = 'DatePicker widefat';
				$extra = 'readonly';
			} else if($field['data_type'] == 'datetimecombo') {
				$JQclass = 'DateTimePicker widefat';
				$extra = 'readonly';
			} else {
				$extra = '';
				$JQclass = 'widefat';
			}
			echo "<p>";
			echo "<label>".$field['wp_meta_label']."</label>";
			echo getHTMLElement($field['field_type'],$this->get_field_name($field['field_name']),$field['field_value'],$FieldVal,'',$JQclass,$extra);
			echo "</p>";
		} 
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		foreach($new_instance as $key=>$val)
		{
			$instance[$key] = (!empty($val)) ? strip_tags($val) : '';
		}
		return $instance;
	}
} 

function OEPL_Lead_Widget_init() {
	register_widget( 'OEPL_Lead_Widget' );
}
add_action( 'widgets_init', 'OEPL_Lead_Widget_init' );

function WidgetFormJS(){
	echo "<script>
		  	var ajaxurl = '".admin_url( 'admin-ajax.php', 'relative' )."';
		  </script>";
		echo "<style>
			    .InvalidInput{
					border-color: red !important;
			    }
			  </style>";
}
add_action('wp_footer', 'WidgetFormJS');
?>