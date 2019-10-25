<?php
if ($_REQUEST['page'] === 'gretas_envoi_email' && !empty($_REQUEST['id']) && !empty($_REQUEST['form_id']) ) {
    // echo '$_REQUEST = <pre>' . print_r($_REQUEST, true) . '</pre>';

    global $wpdb;
    $table_stagiaires = $wpdb->prefix . 'gretadb_stagiaires';
    $table_reponses = $wpdb->prefix . 'gretadb_reponses';
    $format = array('%s','%d');
    $time_start = microtime(true);
    $inserted_lines = 0;
    $skipped_lines = 0;

    $ids = $_REQUEST['id'];
    $form_id = intval($_REQUEST['form_id']);
    $email_model = intval($_REQUEST['email_model']);
    $emails_envoyes = array();

    foreach ($ids as $id) {
        $stagiaire = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_stagiaires WHERE id LIKE %s", $id));

        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_reponses WHERE stagiaire_id LIKE %s AND questionnaire_id LIKE %s", $stagiaire->id, $form_id));

        if (!$exists) {

            $date_envoi = date("Y/m/d H:i:s");
            //$date_envoi = time(); 
            $token = bin2hex(random_bytes(12));
            
            $new_questionnaire = array(
                'id' => '',
                'stagiaire_id' => $id,
                'questionnaire_id' => $form_id,
                'date_envoi' => $date_envoi,
                'token' => $token,
                'status' => 0
            );

            //echo 'Stagiaire = <pre>' . print_r($new_questionnaire, true) . '</pre>';
            $wpdb->insert($table_reponses, $new_questionnaire, $format);
            $inserted_lines++;

            // Emails
            $emails_envoyes[] = prepare_emails($stagiaire, $new_questionnaire, $email_model);

        } else {
            $skipped_lines++;
        } 
    }
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start)/60;
        $message = '<b>Total Execution Time : </b>'.round($execution_time, 2).' mn <br>';
        $message .= '<b>Lignes insérées : </b>'.$inserted_lines.' <br>';
        $message .= '<b>Lignes ignorées : </b>'.$skipped_lines;
        // exit(wp_redirect(admin_url('admin.php?page=gretas_envoi_email&message='.urlencode($message))));


}
function prepare_emails($stagiaire, $new_questionnaire, $email_model) {

    //modèle email
    $post_id = $email_model;
    $queried_post = get_post($post_id);

    $emails_models_subject = get_post_meta($queried_post->ID, 'emails_models_subject', TRUE);
    if (!$emails_models_subject) $emails_models_subject = ''; 


    // lien questionnaire
    $url_questionnaire = sprintf(
        get_site_url() . '/gretas-survey/questionnaire?id=%s&token=%s', $new_questionnaire['questionnaire_id'], $new_questionnaire['token']
    );
    $lien_questionnaire = sprintf(
        '<p><a href="%s" target="_blank" class="button button-primary button-largebutton button-primary button-large">Répondre au questionnaire</a></p>', $url_questionnaire
    );

    // shortcode lien
    $pattern[0] = '/\[lien_questionnaire\]/';
    $pattern[1] = '/\[url_questionnaire\]/';
    $pattern[2] = '/\[nom_stagiaire\]/';
    $pattern[3] = '/\[intitule_formation\]/';
    $pattern[4] = '/\[lieu_formation\]/';
    $pattern[5] = '/\[debut_formation\]/';
    $pattern[6] = '/\[fin_formation\]/';

    $replacement[0] = $lien_questionnaire;
    $replacement[1] = $url_questionnaire;
    $replacement[2] = $stagiaire->col_85;
    $replacement[3] = $stagiaire->col_13;
    $replacement[4] = $stagiaire->col_15;
    $replacement[5] = $stagiaire->col_6;
    $replacement[6] = $stagiaire->col_7;

    $email = array(
        'to'       => $stagiaire->id,
        'subject' => $emails_models_subject,
        'content' => preg_replace($pattern, $replacement, $queried_post->post_content),
        'dump' => '<pre>' . print_r($queried_post, true) . '</pre>'
    );

    return $email;
}
?>