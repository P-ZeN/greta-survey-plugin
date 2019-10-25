<?php

global $wpdb;
$table_name = $wpdb->prefix . 'gretadb_stagiaires';

//Our class extends the WP_List_Table class, so we need to make sure that it's there
if (!class_exists('WP_List_Table')) {
    include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
 }


class Stagiaires_List_Table extends WP_List_Table {

    private $wp_screen;

    function __construct() {
        parent::__construct(array(
            'singular'=> 'gretas_list_stagiaire',
            'plural' => 'gretas_list_stagiaires',
            'ajax'   => true
            )
        );
        //$this->debug();

    }

    function debug() {
        $screen = get_current_screen();
        echo '<p style="text-align: center">$this->wp_screen->id = ' . $this->screen->id . '</p>';
        echo '<p style="text-align: center">$this->wp_screen->id = <pre>' . print_r($this->screen, true) . '</pre></p>';

    }

    function get_columns() {
        $cols = array( 
            'cb'=> '<input type="checkbox" />', //Render a checkbox instead of text
            'col_stagiaire_id'=>'Id'
        );
        for ($i = 0; $i<85; $i++) {
            $col = array('col_stagiaire_col_' . ($i + 1) =>'Colonne ' . ($i + 1));
            $cols = array_merge($cols, $col);
        }
        return $cols;
    }
    public static function columns_details() {
        return Stagiaires_List_Table::get_columns();
    }

    public function get_sortable_columns() {
        $cols = array( 'col_stagiaire_id'=>array('id', true));
        for ($i = 0; $i<85; $i++) {
            $col = array('col_stagiaire_col_' . ($i + 1) => array('col_' . ($i + 1), true));
            $cols = array_merge($cols, $col);
        }
        //echo '$cols = <pre>'. print_r($cols, true) . '</pre>';
        return $cols;
    }

    function prepare_items($search='') {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();
    
        $this->process_bulk_action();
        
        $table_name = $wpdb->prefix . 'gretadb_stagiaires';

        /* -- Preparing query -- */
        $query = "SELECT * FROM $table_name";
    
        /* Where */
        $where = '';
        if (!empty($search)) {
            $string = '';
            for ($i = 1; $i < 86; $i++) {
                $string .= "col_$i LIKE '%{$search}%' ";
                if ($i < 85) $string .= 'OR 
                '; 
            }

            $where = "
            WHERE (
                $string
                )";
        }
        if (!empty($where)) {
            $query = $query . $where;
        }

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? esc_attr($_GET["orderby"]) : 'ASC';
        $order = !empty($_GET["order"]) ? $wpdb->esc_like($_GET["order"]) : '';
        if (!empty($orderby) & !empty($order)) { 
            $query.=' ORDER BY '.$orderby.' '.$order;
        }
    
        /* -- Pagination parameters -- */
        //Number of elements in table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows

        //How many to display per page?
        //per page options
        $per_page_field = 'per_page';
        $per_page_option = 'greta-survey_stagiaires_per_page';

        //Save options that were applied
        if (isset($_REQUEST['wp_screen_options']) && isset($_REQUEST['wp_screen_options']['value'])) {
            update_option($per_page_option, esc_html($_REQUEST['wp_screen_options']['value']));
            // echo '$per_page_option value = ' . esc_html($_REQUEST['wp_screen_options']['value']) . '<br>';
        }

        //if per page option is not set, use default
        $perpage = get_option($per_page_option, 10);
        $args = array('label' => __('Records', 'sll'), 'default' => $perpage);

        //Which page is this?
        $paged = !empty($_GET["paged"]) ?  $wpdb->esc_like($_GET["paged"]) : '';

        //Page Number
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }


        //How many pages do we have in total? 
        $totalpages = ceil($totalitems/$perpage);
        //adjust the query to take pagination into account 
        if (!empty($paged) && !empty($perpage)) { 
            $offset=($paged-1)*$perpage; $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
        }

        /* -- Register the pagination -- */ 
        $this->set_pagination_args(
            array(
                "total_items" => $totalitems,
                "total_pages" => $totalpages,
                "per_page" => $perpage,
            )
        );
    
        /* -- Register the Columns -- */
        $columns = $this->get_columns();

        $_wp_column_headers[$screen->id] = $columns;
    
        $this->_column_headers = array( 
            $this->get_columns(), // columns
            array(), // hidden
            $this->get_sortable_columns() // sortable
        );

        /* -- Fetch the items -- */
        $this->items = $wpdb->get_results($query);
    }


    function display_rows() {

        //Get the records registered in the prepare_items method
        $records = $this->items;

        //Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();

        //Loop for each record
        if (!empty($records)) {
            foreach ($records as $rec) {
    
                //Open the line

                echo '<tr id="record_'.$rec->id.'">';
                foreach ($columns as $column_name => $column_display_name) {
            
                    //Style attributes for each col
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden)) $style = ' style="display:none;"';
                    $attributes = $class . $style;
            
                    $this->column_default($rec, $column_name);

                    //Display the cell
                    switch ( $column_name ) {
                        case "cb":  echo '<th scope="row" class="check-column">'.$this->column_cb($rec).'</th>';   break;
                        case "col_stagiaire_id":  echo '<td '.$attributes.'>'.stripslashes($rec->id).'</td>';   break;

                    default:
                        $arr = (array) $rec;
                        $i = substr($column_name, 18);
                        echo '<td '.$attributes.'>'.stripslashes($arr['col_'.$i]).'</td>';
                    }
                }
            
                //Close the line
                echo'</tr>';
            }
        }
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />', $item->id
        );    
    }

    function get_bulk_actions() {
        $actions = array(
            'delete' => __('Supprimer'),
            'export' => __('Exporter'),
        );
    
        return $actions;
    }

    function column_default( $item, $column_name) {
        do_action(
            'manage_toplevel_page_gretas_home_column',
            $column_name, $item->id
        );
    }

    public function process_bulk_action() {

        // security check!
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );

        }

        $action = $this->current_action();
        global $wpdb;
        $table_name = $wpdb->prefix . 'gretadb_stagiaires';

        switch ( $action ) {

            case 'delete':

                if ('delete' === $this->current_action()) {
                    $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();

                    $nbr_ids = count($ids);

                    if (!empty($ids)) {
                        $result = 0;
                        foreach ($ids as $id) {

                         $wpdb->query("DELETE FROM $table_name WHERE id = $id");
                         $result++;
                        }
                    }

                    if ($result > 0 ) {
                        ?>
                        <div class="notice notice-success is-dismissible">
                            <p><?php echo $nbr_ids; ?> éléments supprimés</p>
                        </div>
                        <?php

                    }
                    else {
                        ?>
                        <div class="notice notice-error is-dismissible">
                            <p>une erreur s'est produite et aucun élément a été supprimé</p>
                        </div>
                        <?php

                    }
                }
                
                break;

            case 'export':

                break;

                default:

                return;
                break;
        }

        return;
    }
}


function download_csv($ids) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'gretadb_stagiaires';

    if (!empty($ids)) {
        $options = get_option('gretas_options');
        $separator = $options['gretas_csv_export_separator'] === 'tabulation' ? "\t" : ";" ;
        $ids = implode($ids, '\',\'');
        $query = "SELECT * FROM $table_name WHERE `id` IN ('$ids')";
        $results = $wpdb->get_results($query);

        $output_filename = $table_name .'.csv';

        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename=' . $output_filename);
        header('Expires: 0');
        header('Pragma: public');

        foreach ($results as $result) {
            echo implode($separator, (array) $result)."\r\n";
        }
        exit;
    }

}

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'export'){
    $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
    download_csv($ids);
}
