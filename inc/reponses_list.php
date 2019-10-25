<?php

global $wpdb;
$table_name = $wpdb->prefix . 'gretadb_reponses';

if (!class_exists('WP_List_Table')) {
    include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}


class Reponses_List_Table extends WP_List_Table {

    private $wp_screen;

    function __construct() {
        parent::__construct(array(
            'singular'=> 'gretas_list_reponse',
            'plural' => 'gretas_list_reponses',
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
            'col_reponse_id'=>'Id',
            'col_reponse_stagiaire_id'=>'Id stagiaire',
            'col_reponse_questionnaire_id'=>'Questionnaire',
            'col_reponse_date_envoi'=>'Date d\'envoi',
            'col_reponse_date_submission'=>'Date de réponse',
            'col_reponse_token'=>'Token',
            'col_reponse_status'=>'Statut',
            'col_reponse_datas'=>'Datas'
        );
        return $cols;
    }

    public static function columns_details() {
        return reponses_List_Table::get_columns();
    }

    public function get_sortable_columns() {
        $cols = array(
            'col_reponse_id'=> array('id', true),
            'col_reponse_stagiaire_id'=> array('stagiaire_id', true),
            'col_reponse_questionnaire_id' => array('questionnaire_id', true),
            'col_reponse_date_envoi' => array('date_envoi', true),
            'col_reponse_date_submission' => array('date_submission', true),
            'col_reponse_token' => array('token', true),
            'col_reponse_status'=> array('status', true),
            'col_reponse_datas'=> array('datas', true)
        );
        return $cols;
    }

    function prepare_items($search='') {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();
    
        $this->process_bulk_action();
        
        $table_name = $wpdb->prefix . 'gretadb_reponses';

        /* -- Preparing query -- */
        $query = "SELECT * FROM $table_name";
    
        /* Where */
        $where = '';
        /*if (!empty($search)) {
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
        }*/

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
        $per_page_option = 'greta-survey_reponses_per_page';

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
        if (empty($paged) || !is_numeric($paged) || $paged<=0 ) { $paged=1; }


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
        //echo '<pre>' . print_r($records, true) . '</pre>';

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
                        case "col_reponse_id":  echo '<td '.$attributes.'>'.stripslashes($rec->id).'</td>';   break;
                        case "col_reponse_stagiaire_id":  echo '<td '.$attributes.'>'.stripslashes($rec->stagiaire_id).'</td>';   break;
                        case "col_reponse_questionnaire_id":  echo '<td '.$attributes.'>'.$this->column_questionnaire($rec).'</td>';   break;
                        case "col_reponse_token":  echo '<td '.$attributes.'>'.stripslashes($rec->token).'</td>';   break;
                        case "col_reponse_date_envoi":  echo '<td '.$attributes.'>'.stripslashes($rec->date_envoi).'</td>';   break;
                        case "col_reponse_date_submission":  echo '<td '.$attributes.'>'.stripslashes($rec->date_submission).'</td>';   break;
                        case "col_reponse_status":  echo '<td '.$attributes.'>'.$this->column_status($rec->status).'</td>';   break;
                        case "col_reponse_datas":  echo '<td '.$attributes.'>'.$this->column_datas($rec->datas).'</td>';   break;

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

    function column_status($item) {
        if ($item > 0) {
            $output = '<span class="dashicons dashicons-yes gretas-dashicons vert"></span>';
        } else {
            $output = '<span class="dashicons dashicons-minus gretas-dashicons"></span>';
        }
        return $output;
    }

    function column_datas($item) {
        $output = '';
        if (!empty($item)) {
            $datas = unserialize($item);
            $output = '';
            foreach ((array)$datas as $key => $value) {
                if (is_array($value)) $value = implode("|", $value);
                $output .= $key . ' : '. $value . '; ';
            }
        }
        return $output;
    }

    function column_questionnaire($item) {
        $href = sprintf(
            get_site_url() . '/gretas-survey/questionnaire?id=%s&token=%s', $item->questionnaire_id, $item->token
        );
        $titre = 'Voir le questionnaire ';
        return sprintf(
            '<a href="%s" target="_blank" class="button action"/><span class="dashicons dashicons-external gretas-dashicons"></span> %s</a>', $href, $titre
        );    
    }

    function get_bulk_actions() {
        $actions = array(
            //'delete' => __('Supprimer'),
            'export_reponses' => __('Exporter'),
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
        $table_name = $wpdb->prefix . 'gretadb_reponses';

        switch ( $action ) {

            /*case 'delete':

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
                
                break;*/

            case 'export':

                break;

                default:

                return;
                break;
        }

        return;
    }
}


function export_reponses_csv($ids) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'gretadb_reponses';

    if (!empty($ids)) {
        $options = get_option('gretas_options');
        $separator = $options['gretas_csv_export_separator'] == 'tabulation' ? "\t" : ";" ;
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
//    echo '<pre>' . print_r($_REQUEST, true) . '</pre>';

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'export_reponses') {
    $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
    export_reponses_csv($ids);
}