<?php

function gretadb_stagiaires_install() {
    global $wpdb;
    global $gretadb_stagiaires_version;
    $gretadb_stagiaires_version = '3';

    $table_name = $wpdb->prefix . 'gretadb_stagiaires';
    
    $charset_collate = $wpdb->get_charset_collate();

    $cols = '';
    for ($i = 0; $i < 85; $i++) {
        $cols .= 'col_' . ($i + 1) . ' text,' . "\r\n";
    }

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        $cols
        PRIMARY KEY  (id)) $charset_collate;";

    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    add_option('gretadb_stagiaires_version', $gretadb_stagiaires_version);
}


function gretadb_stagiaires_csv_2_db() {

    $options = get_option('gretas_options');
    $file_name = $options['gretas_csv_import_file'];
    $separator = $options['gretas_csv_import_separator'] === 'tabulation' ? "\t" : ";" ;
    $path_file = dirname(dirname(__FILE__)) . '/ftp_import/' . $file_name;

    if (!is_file($path_file)) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'gretadb_stagiaires';
    $format = array('%s','%d');

    $time_start = microtime(true);
    $inserted_lines = 0;
    $skipped_lines = 0;

    $file = fopen($path_file, "r");
    $lines = Array();
    while ($champs = fgetcsv($file, 1000, $separator)) {
        //$wpdb->insert($table_name, $line);
        $line = array('id' => '');
        foreach ($champs as $key => $value) {
            //$valeur = empty($value) ? 'NULL' : $value;
            $champ = array('col_'.($key+1) => $value);
            $line = array_merge($line, $champ);
        }

        // echo 'line = <pre>'. print_r($line, true) .'</pre>';
        //$prepare = "SELECT COUNT(*) FROM $table_name WHERE col_85=, $line[col_85];

        // echo '<pre>'. $prepare .'</pre>';
        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE col_85 LIKE %s", $line['col_85']));
        
        if (!$exists) {
            $wpdb->insert($table_name, $line, $format);
            $lines[] =  $line;
            $inserted_lines++;

            // echo $wpdb->insert_id . '<br>';
        } else {
            $skipped_lines++;
        } 
    }
    fclose($file);
    //echo '<pre>'. print_r($lines, true).'</pre>';
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start)/60;
    $message = '<b>Total Execution Time : </b>'.round($execution_time, 2).' mn <br>';
    $message .= '<b>Lignes insérées : </b>'.$inserted_lines.' <br>';
    $message .= '<b>Lignes ignorées : </b>'.$skipped_lines;
    exit(wp_redirect(admin_url('admin.php?page=gretas_home&message='.urlencode($message))));
}
add_action( 'admin_post_gretas_import_csv', 'gretadb_stagiaires_csv_2_db' );

function get_gretas_stagiaire($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'gretadb_stagiaires';
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id LIKE %s", $id));
}
?>