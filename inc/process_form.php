<?php 

// Référence la page de confirmation
function check_confirm_url() {
    if (false !== strpos( $_SERVER[ 'REQUEST_URI' ], '/gretas-survey/confirm')) {
        add_filter( 'the_posts', 'confirm_page' );
    }
}
// Construit la page de confirmation
function confirm_page( $posts ) {

    $options = get_option('gretas_options');
    $message = $options['gretas_thanks_submission_template'];
    $title = $options['gretas_thanks_submission_title'];

    
    ob_start();
    include_once dirname(dirname(__FILE__)) . '/pages/confirmation.php';
    $output = ob_get_clean();

    $posts = null;
    $post = new stdClass();
    $post->post_content = $output;
    $post->post_title = $title;
    $post->post_type = "page";
    $post->comment_status = "closed";
    $posts[] = $post;
    return $posts;
}
add_action( 'init', 'check_confirm_url' );


// Référence la page questionnaire
function check_questionnaire_url() {
    if (false !== strpos( $_SERVER[ 'REQUEST_URI' ], '/gretas-survey/questionnaire')) {
        add_filter( 'the_posts', 'page_questionnaires' );
    }
}
// Construit la page questionnaire
function page_questionnaires( $posts ) {

    $output =  'Le formulaire demandé n\'existe pas';

    if (isset($_GET['id'])
        && is_numeric($_GET['id'])
        // && is_file(dirname(dirname(__FILE__)) . '/formulaires/form_'. $_GET['id'] .'.php')
    ) {
        $id = $_GET['id'];
        $token = check_token();

        if (gettype($token) == 'object') {
            $stagiaire = get_gretas_stagiaire($token->stagiaire_id);
            // $message = '';
            $disabled = '';
        } else {
            $message = $token;
            $disabled ='disabled';
        }
        $queried_post = get_post($id);
        if (!empty($queried_post)) {
            ob_start();
            include_once dirname(dirname(__FILE__)) . '/inc/form_header.php';
            $output = ob_get_clean();
            $output .= $queried_post->post_content;
            ob_start();
            include_once dirname(dirname(__FILE__)) . '/inc/form_footer.php';
            $output .= ob_get_clean();
        }
    }

    $posts = null;
    $post = new stdClass();
    $post->post_content = $output;
    $post->post_title = isset($queried_post) ? $queried_post->post_title : "Gretas - Questionnaire de satisfaction";
    $post->post_type = "page";
    $post->comment_status = "closed";
    $posts[] = $post;
    return $posts;
}
add_action( 'init', 'check_questionnaire_url' );

// check token
function check_token() {
    if (isset($_GET['token'])) {
        $token = sanitize_text_field($_GET['token']);

        global $wpdb;
        $table_reponses = $wpdb->prefix . 'gretadb_reponses';
        $reponse = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_reponses WHERE token LIKE %s", $token));

        if (!empty($reponse)) {
            if ($reponse->status == 0) {
                // si le token existe et que le stagiaire 
                // n'a pas répondu au questionnaire, renvoie la ligne questionnaire
                return $reponse; 
            }
            // si le token existe et que le stagiaire 
            // a déja répondu au questionnaire, renvoie un message d'erreur
            return (string)'Vous avez déjà répondu à ce questionnaire !'; 
        }
        // si le token n'existe pas, renvoie un message d'erreur
        return (string) 'Le lien que vous utilisez n\'est pas autorisé'; 
    }
    // si il n'y a pas de token
    // retourne un résultat vide
    return (string) 'Vous ne pouvez pas répondre à ce questionnaire actuellement';
}

// lecture des résultats du formulaire
function process_gretas_form() {
    if ( empty($_POST) || !wp_verify_nonce($_POST['security-code-here'], 'gretas_form') ) {
        echo 'You targeted the right function, but sorry, your nonce did not verify.';
        die();
    } else {
        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (update_gretas_reponse($_POST['reponse_id'], $_POST['form_datas'])) {
            wp_redirect('/gretas-survey/confirm?p=' . rawurlencode(nl2br(print_r($_POST, true))));
        }
        else {
            wp_redirect('/gretas-survey/confirm?p=' . rawurlencode("Une erreur s'est produite"));
        }
        exit;
    }
}
add_action('wp_ajax_gretas_form', 'process_gretas_form');

?>