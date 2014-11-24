<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Please don\'t access this file directly.');
}

class OEPL_Lead_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'OEPL_Lead_Widget', 
			__('SugarCRM Lead Form', 'OEPL_Lead_Widget'), 
			array( 'description' => __( 'This Widget will submit data into your SugarCRM Lead module.', 'WPBeginner Widget' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		global $wpdb;
		
		$query = "SELECT * FROM ".OEPL_TBL_MAP_FIELDS." WHERE is_show = 'Y' ORDER BY display_order ASC";
		$RS = $wpdb->get_results($query,ARRAY_A);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		echo "<p><div class='LeadFormMsg' style='color:red'></div></p>";
		echo "<form id='WidgetForm' method='POST'>";
		
		$dateFieldArray = array('birthdate');
		foreach ($RS as $module) {
			### Add Date picker Class if filed type match
			if($module['data_type'] == 'date'){
				$JQclass = 'LeadFormEach DatePicker';
				$readonly = 'readonly';
			} else if($module['data_type'] == 'datetimecombo') {
				$JQclass = 'LeadFormEach DateTimePicker';
				$readonly = 'readonly';
			} else {
				$readonly = '';
				$JQclass = 'LeadFormEach';
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
		}
		$random1 = rand(1,9);
		$random2 = rand(1,9);
		$_SESSION['captcha1'] = $random1;
		$_SESSION['captcha2'] = $random2;
		echo "<p class='WidgetLeadFormCaptcha'>What is &nbsp;<strong>".$random1." + ".$random2."</strong>&nbsp; ? <input type='text' name='captcha' id='captcha' size='3' maxlength='3'/></p>" ;
		echo "</form>";
		echo "<p><input type='submit' name='submit' style ='' value='Submit' id='WidgetFormSubmit' class='' ></p>";
		echo $args['after_widget'];
	}
			
	public function form( $instance ) {
		global $wpdb;
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'New title', 'wpb_widget_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
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
			    .loadingBtn{
			    	padding-left: 35px !important;
			    	background-image: url(".OEPL_PLUGIN_URL."image/ajax-loader-button.gif) !important;
			    	background-repeat: no-repeat !important;
			    	background-position: 10px 5px;
			    }
			    .InvalidInput{
					border-color: red !important;
			    }
			  </style>";
}
add_action('wp_footer', 'WidgetFormJS');
?>