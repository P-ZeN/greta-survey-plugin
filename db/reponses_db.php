<?php

function gretadb_reponses_install() {
    global $wpdb;
    global $gretadb_reponses_version;
    $gretadb_reponses_version = '1.1';

    $table_name = $wpdb->prefix . 'gretadb_reponses';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        stagiaire_id mediumint(9) NOT NULL,
        questionnaire_id mediumint(9) NOT NULL,
        date_envoi DATETIME NOT NULL,
        date_submission DATETIME,
        token MEDIUMTEXT NOT NULL,
        status mediumint(9) NOT NULL,
        datas text,
        PRIMARY KEY  (id)) $charset_collate;";

    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    add_option('gretadb_reponses_version', $gretadb_reponses_version);
}

function update_gretas_reponse($reponse_id, $datas) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'gretadb_reponses';
    $format = array('%s','%d');
    $date_submission = date("Y/m/d H:i:s");
    //$date_envoi = time(); 

    $where = array(
        'id' => $reponse_id
    );
    
    $updated_datas = array(
        'date_submission' => $date_submission,
        'status' => 1,
        'datas' => serialize($datas)
    );

    //echo '<pre>' . print_r($datas, true) .'</pre>';

    // $int|false = wpdb::update( $table, $data, $where, $format, [$where_format] );
    return $wpdb->update($table_name, $updated_datas, $where, $format);
}





?>