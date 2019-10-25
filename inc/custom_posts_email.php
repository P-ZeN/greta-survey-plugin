<?php
// Hook <strong>lc_custom_post_movie()</strong> to the init action hook

 
// The custom function to register a movie post type
function gretas_custom_posts_email() {
 
    // Set the labels, this variable is used in the $args array
    $labels = array(
    'name'               => __( 'Modèles d\'email' ),
    'singular_name'      => __( 'Email' ),
    'add_new'            => __( 'Ajouter un modèle d\'email' ),
    'add_new_item'       => __( 'Ajouter un modèle d\'email' ),
    'edit_item'          => __( 'Editer le modèle d\'email' ),
    'new_item'           => __( 'Nouveau modèle d\'email' ),
    'all_items'          => __( 'Tous les modèles d\'email' ),
    'view_item'          => __( 'Voir le modèle d\'email' ),
    'search_items'       => __( 'Chercher un modèle d\'email' ),
    'featured_image'     => 'Image',
    'set_featured_image' => 'Ajouter une image'
    );

    // The arguments for our post type, to be entered as parameter 2 of register_post_type()
    $args = array(
    'labels'            => $labels,
    'description'       => 'Modèles d\'emails de notification de mise en place de questionnaires de satisfaction du GRETA',
    'public'            => true,
    'menu_position'     => 5,
    'supports'          => array( 'title', 'editor'),
    'show_in_menu'      => false,
    'show_in_admin_bar' => false,
    'show_in_nav_menus' => false,
    'has_archive'       => false,
    'exclude_from_search' => true,
    'query_var'         => 'emails_models',
    'register_meta_box_cb' => 'emails_models_subject_box_add'

    );

    // Call the actual WordPress function
    // Parameter 1 is a name for the post type
    // Parameter 2 is the $args array
    register_post_type( 'emails_models', $args);

}

add_action('edit_form_top', 'gretas_custom_posts_email_edit_form_top');
function gretas_custom_posts_email_edit_form_top( $post )
{
    if( in_array( $post->post_type, array( 'emails_models' ) ) ){
        ?>
        <div class="notice notice-success">
            <h5>Le modèle d'email doit contenir :</h5>
            <ul>
                <li>[lien_questionnaire] : le lien vers le questionnaire</li>
                <li>[url_questionnaire] : URL du questionnaire</li>
                <li>[nom_stagiaire] : Nom du stagiaire</li>
            </ul>
            <h5>Il peut également contenir :</h5>
            <ul>
                <li>[intitule_formation] : Intitulé de la formation</li>
                <li>[lieu_formation] : Lieu de la formation</li>
                <li>[debut_formation] : Date du début de la formation</li>
                <li>[fin_formation] : Date de la fin de la formation</li>
            </ul>
        </div>
        <?php
    }
}

function get_gretas_custom_posts_email() {
    $email_models = new WP_Query(
        array(
            'post_type' => 'emails_models',
            'posts_per_page' => 100,
            'orderby' => 'ID',
            'order' => 'ASC',
            'post_status' => 'publish',
        )
    );
    return $email_models;
}

function add_my_first_email_model() {
    
    $count_posts = wp_count_posts('emails_models');
    if (!isset($count_posts->publish)) {

        $content = 'Bonjour [nom_stagiaire],

        Vous avez suivi une formation [intitule_formation] du GRETA Bordeaux du [debut_formation] au [fin_formation] à [lieu_formation].
        Dans le cadre de sa politique de suivi qualité nous vous prions de bien vouloir prendre quelques minutes pour répondre à un questionnaire de satisfaction afin de nous permettre d\'évaluer nos services.
        Vous pouvez répondre en cliquant sur le lien ci-dessous :
        [lien_questionnaire]
        Si le lien direct ne fonctionne pas, vous pouvez copier le lien ci-dessous et le coller dans la barre d\'adresse de votre navigateur :
        [url_questionnaire]
        En vous remerciant de votre coopération,
        Cordialement';

        $my_post = array(
        'post_title'    => wp_strip_all_tags( 'Modèle d\'email n°1' ),
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'emails_models',
        );

        // Insert the post into the database
        $id = wp_insert_post( $my_post );

        // Add meta
        add_post_meta($id, 'emails_models_subject', 'Sujet du modèle d\'email n°1', true);
    }
}

//add_meta_box('emails_models_subject', 'Sujet de l\'email', 'emails_models_subject_metabox', 'emails_models', 'normal', 'high');

//add_action('save_post', 'emails_models_subject_save');
add_action('edit_post', 'emails_models_subject_save');
function emails_models_subject_box_add() {
    // echo '<pre>edit_post</pre>'; 
    add_meta_box( 'emails_models_subject', _('Sujet de l\'email'), 'emails_models_subject_metabox', 'emails_models', 'normal', 'high' );
}

 
function emails_models_subject_metabox($post) {
    $emails_models_subject = get_post_meta($post->ID, 'emails_models_subject', TRUE);
    if (!$emails_models_subject) $emails_models_subject = ''; 
    echo '<div id="titlediv"><div id="titlewrap">';
    // echo '<label class="" id="title-prompt-text" for="title">Saisissez votre titre ici</label>';
    echo sprintf(
        '<input type="text" name="emails_models_subject" size="30" id="title" spellcheck="true" autocomplete="off" value="%s"/>',  $emails_models_subject
    );
    echo sprintf(
        '<input type="hidden" name="emails_models_subject_noncename" id="emails_models_subject_noncename" value="%s" />',
        wp_create_nonce('emails_models_subject'.$post->ID)
    );
    echo '</div></div>';
}

function emails_models_subject_save($post_id) {  
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'trash') return $post_id;
    // verify this came from the our screen and with proper authorization.
    if ( !wp_verify_nonce( $_POST['emails_models_subject_noncename'], 'emails_models_subject'.$post_id )) {
        return $post_id;
    }
     
    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;
     
    // Check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
         
     
    // OK, we're authenticated: we need to find and save the data   
    $post = get_post($post_id);
    if ($post->post_type == 'emails_models') { 
        // echo 'hu';
        update_post_meta($post_id, 'emails_models_subject', esc_attr($_POST['emails_models_subject']) );
                return(esc_attr($_POST['emails_models_subject']));
    }
        echo '<script type="text/javascript">alert("Save !!!";</script>';
    return $post_id;
}
