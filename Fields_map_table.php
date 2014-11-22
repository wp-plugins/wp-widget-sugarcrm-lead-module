<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Fields_Map_Table extends WP_List_Table {
    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'Lead',     //singular name of the listed records
            'plural'    => 'Leads',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }


    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'field_name':
            	return $item['wp_meta_label'];
            case 'is_show':
				if($item['is_show'] == 'Y')
					return "Yes";
				if($item['is_show'] == 'N')
					return "No";
            case 'display_order':
                return '<input type="text" name="display_order[]" value='.$item['display_order'].' size="3" maxlength="3" class ="display_order OEPLIntInput" /><input type="hidden" name="display_order_ID[]" value='.$item['pid'].' />';
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" class="LeadTableCbx" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $item['module'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['pid']                //The value of the checkbox should be the record's id
        );
    }


    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'        	=> '<input type="checkbox" />', //Render a checkbox instead of text
            'field_name'	=> 'Field Name',
            'display_order' => 'Display Order',
            'is_show'    	=> 'Show on Widget',
        );
        return $columns;
    }


    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'field_name'	=> array('field_name',false),     
            'display_order'	=> array('display_order',true),		//true means it's already sorted
            'is_show'    	=> array('is_show',true)			//true means it's already sorted
        );
        return $sortable_columns;
    }


    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
        	'display_on_widget'		=> 'Set on Widget',
            'remove_from_widget'	=> 'Remove from Widget',
            'update_display_order'	=> 'Update Display Order'
        );
        return $actions;
    }
	
	public function search_box( $text, $input_id ) { ?>
	    <p class="search-box">
	      <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
	      <input type="search" id="<?php echo $input_id ?>" name="LeadSearch" value="<?php echo $_POST['LeadSearch']; ?>" />
	      <?php submit_button( $text, 'button', 'LeadSearchSubmit', false, array('id' => 'LeadSearchSubmit') ); ?>
	  	</p>
	<?php }
	
	function extra_tablenav(){
		echo '<span class="subsubsub" >';
		echo "<a href=".admin_url('admin.php?page=mapping_table&is_show=Y')." 
			class='".($_GET[is_show] == 'Y' ? "current":"")."'>Enabled on Widget</a>&nbsp;|&nbsp;";
		echo "<a href=".admin_url('admin.php?page=mapping_table&is_show=N')." class='".($_GET[is_show] == 'N' ? "current":"")."'>Disabled on Widget</a>&nbsp;|&nbsp;";
		echo "<a href=".admin_url('admin.php?page=mapping_table').">Reset</a>";
		echo '</span>';
	}
	
    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
    	global $wpdb; 
        $redirectFlag = FALSE;
        if( 'display_on_widget'===$this->current_action() ) {
			foreach($_POST[Leads] as $k=>$v)
			{
				$UpdateQuery = 'UPDATE '.OEPL_TBL_MAP_FIELDS.' SET is_show="Y" WHERE pid = '.$v.'';
				$wpdb->query($UpdateQuery);
			}
			$redirectFlag = TRUE;	
		}
		else if('remove_from_widget' === $this->current_action())
		{
			foreach($_POST[Leads] as $k=>$v)
			{
				$UpdateQuery = 'UPDATE '.OEPL_TBL_MAP_FIELDS.' SET is_show="N" WHERE pid = '.$v.'';
				$wpdb->query($UpdateQuery);
			}
			$redirectFlag = TRUE;
		}
		else if ('update_display_order' === $this->current_action())
		{
			//echo "<pre>"; print_r($_POST); echo "</pre>";
			$LeadsID = $_POST['Leads'];
			$DisplayOrder = $_POST['display_order'];
			$DisplayOrderID = $_POST['display_order_ID'];
			for($i = 0 ; $i<10 ; $i++)
			{
				if(in_array($DisplayOrderID[$i], $LeadsID))
				{
					$UpdateQuery = 'UPDATE '.OEPL_TBL_MAP_FIELDS.' SET display_order='.$DisplayOrder[$i].' WHERE pid = '.$DisplayOrderID[$i].'';
					$wpdb->query($UpdateQuery);
				}
			}
			$redirectFlag = TRUE;
		}
		if($redirectFlag == TRUE)
		{
			if($_GET['orderby']) $orderby = '&orderby='.$_GET['orderby']; 	else $orderby = '';
			if($_GET['order']) $order = '&order='.$_GET['order'];			else $order = '';	
			if($_GET['paged']) $paged = '&paged='.$_GET['paged'];			else $paged = '';
			if($_GET['is_show']) $is_show = '&is_show='.$_GET['is_show'];	else $is_show = '';
			$url = admin_url('admin.php?page=mapping_table'.$orderby.$order.$is_show.$paged);
			wp_redirect($url);
			exit;
		}
    }

    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
        global $wpdb; 
        $per_page = 10;
        $columns = $this->get_columns();
        $hidden = array();
        $query = 'SELECT * FROM '.OEPL_TBL_MAP_FIELDS.' WHERE 1';
		
		if(!empty($_POST['LeadSearch']))
		{
			$where = ' AND wp_meta_label LIKE "%'.$_POST['LeadSearch'].'%"';
			$query .= $where;
		}
		if(!empty($_GET['is_show']))
		{
			$where = ' AND is_show = "'.$_GET['is_show'].'"';
			$query .= $where;
		}
		$orderby 	= !empty($_GET["orderby"]) 	? $_GET["orderby"]: 'ASC';
		$order 		= !empty($_GET["order"]) 	? $_GET["order"]:'';
		if(!empty($orderby) & !empty($order)) {
			$query .=' ORDER BY '.$orderby.' '.$order;
		} else {
			$query .= ' ORDER BY is_show ASC,display_order ASC';
		}
        $data = $wpdb->get_results($query,ARRAY_A);      
        
		$sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
		
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page),   //WE have to calculate the total number of pages
        ) );
    }
}
?>