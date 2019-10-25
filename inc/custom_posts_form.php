<?php
// The custom function to register a gretas_forms post type
function gretas_custom_posts_form() {
 
    // Set the labels, this variable is used in the $args array
    $labels = array(
    'name'               => __( 'Questionnaires de satisfaction' ),
    'singular_name'      => __( 'Questionnaire' ),
    'add_new'            => __( 'Ajouter un questionnaire' ),
    'add_new_item'       => __( 'Ajouter un questionnaire de satisfaction' ),
    'edit_item'          => __( 'Editer le questionnaire de satisfaction' ),
    'new_item'           => __( 'Nouveau questionnaire de satisfaction' ),
    'all_items'          => __( 'Tous les questionnaires de satisfaction' ),
    'view_item'          => __( 'Voir le questionnaire de satisfaction' ),
    'search_items'       => __( 'Chercher unquestionnaire de satisfaction' ),
    'featured_image'     => 'Image',
    'set_featured_image' => 'Ajouter une image'
    );

    // The arguments for our post type, to be entered as parameter 2 of register_post_type()
    $args = array(
    'labels'            => $labels,
    'description'       => 'Questionnaires de satisfaction du GRETA',
    'public'            => true,
    'menu_position'     => 5,
    'supports'          => array( 'title', 'editor'),
    'show_in_menu'      => false,
    'show_in_admin_bar' => false,
    'show_in_nav_menus' => false,
    'has_archive'       => false,
    'exclude_from_search' => true,
    'query_var'         => 'gretas_form'
    );

    // Call the actual WordPress function
    // Parameter 1 is a name for the post type
    // Parameter 2 is the $args array
    register_post_type( 'gretas_form', $args);

}

add_action('edit_form_top', 'gretas_custom_posts_form_edit_form_top');
function gretas_custom_posts_form_edit_form_top( $post )
{
    if (in_array($post->post_type, array( 'gretas_form'))) {
        ?>
        <div class="notice notice-success">
            <h5>Eléments de formulaire</h5>
            <p><u><strong><em>L'éditeur propose plusieurs boutons facilitant l'insertion d'éléments de formulaire :</em></strong></u></p>
            <p><strong>[question_simple_courte]</strong> : Affiche la question suivie d'un champ de texte d'une ligne.</p>
            <p><strong>[question_simple_longue]</strong> : Affiche la question suivie d'un champ de texte multiligne.</p>
            <p><strong>[choix_multiples_radio]</strong> : Affiche la question suivie de plusieurs réponses possible. Une seule peut être sélectionnée.</p>
            <p><strong>[choix_multiples_checkboxes]</strong> : Affiche la question suivie de plusieurs réponses possible. Plusieurs réponses peuvent être sélectionnées.</p>
            <p><strong>[evaluation_1-5]</strong> : Affiche la question suivie d'une évaluation de 1 à 5.</p>
            <p>Pour une disposition régulière, chaque ensemble description/commentaire/question/élément de formulaire est à entourer d'une balise <code>&lt;div&gt;&lt;/div&gt;</code> (en mode texte de l'éditeur).</p>
        </div>
        <?php
    }
}

function get_gretas_custom_posts_form() {
    $gretas_forms = new WP_Query(
        array(
            'post_type' => 'gretas_form',
            'posts_per_page' => 100,
            'orderby' => 'ID',
            'order' => 'ASC',
            'post_status' => 'publish',
        )
    );
    return $gretas_forms;
}

function add_my_first_gretas_form() {
    
    $count_posts = wp_count_posts('gretas_form');
    if (!isset($count_posts->publish)) {

        $content = '<div><em>Question simple avec réponse courte (input[type=text]) :</em>
        [gform_question_simple_courte name="champ0"]Texte de la question avec réponse courte ?[/gform_question_simple_courte]</div>
        
        <div><em>Question simple avec réponse longue (textarea) :</em>
        [gform_question_simple_longue name="champ1"]Texte de la question avec réponse longue ?[/gform_question_simple_longue]</div>
        
        <div><em>Question à choix multiples (input[type=radio], une seule réponse possible) :</em>
        [gform_choix_multiples_radio name="champ2" options="oui|non|peut être|je ne sais pas"]Texte de la question à choix multiples (une seule réponse possible) ?[/gform_choix_multiples_radio]</div>
        
        <div><em>Question à choix multiples (input[type=checkbox], plusieurs réponses possibles) :</em>
        [gform_choix_multiples_checkboxes name="champ3" options="Rouge|Vert|Bleu|Choix, avec, virgule|Choix avec l\'apostrophe"]Texte de la question à choix multiples (plusieurs réponses possibles) ?[/gform_choix_multiples_checkboxes]</div>
        
        <div><em>Question évaluation 1/5 :</em>
        [gform_evaluation_1_5 name="champ4"]Texte de la question evaluation 1/5 ?[/gform_evaluation_1_5]</div>';

        $my_post = array(
        'post_title'    => wp_strip_all_tags( 'Exemple de questionnaire' ),
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'gretas_form',
        );

        // Insert the post into the database
        $id = wp_insert_post( $my_post );
    }
}


// hooks your functions into the correct filters
function gretas_add_mce_button() {
    global $typenow, $pagenow;

    if ( empty( $typenow ) && !empty( $_GET['post'] ) ) {
        $post = get_post( $_GET['post'] );
        $typenow = $post->post_type;
    }

    $curpage = $pagenow . 'post-new.php?post_type=' . $typenow;

    if ('gretas_form' == $typenow || 'post-new.php?post-type=gretas_form' == $curpage ) {
            // check user permissions
        if ( !current_user_can( 'edit_posts' ) &&  !current_user_can( 'edit_pages' ) ) {
                return;
        }
        // check if WYSIWYG is enabled
        //if ( 'true' == get_user_option( 'rich_editing' ) ) {
        function mce_show_toolbar( $args ) {
            $args['wordpress_adv_hidden'] = false;
            return $args;
        }
        add_filter( 'tiny_mce_before_init', 'mce_show_toolbar' );
        add_filter( 'mce_external_plugins', 'gretas_add_tinymce_plugin' );
        add_filter( 'mce_buttons_2', 'gretas_register_mce_button' );
        //}
    }
}
add_action('admin_head', 'gretas_add_mce_button');

// register new button in the editor
function gretas_register_mce_button( $buttons ) {
    $buttons = array(
        'gretas_mce_button0',
        'gretas_mce_button1',
        'gretas_mce_button2',
        'gretas_mce_button3',
        'gretas_mce_button4'
    );
    return $buttons;
}


// declare a script for the new button
// the script will insert the shortcode on the click event
function gretas_add_tinymce_plugin( $plugin_array ) {
    $plugin_array['gretas_mce_button0'] = plugins_url( 'greta-survey/js/gretas_mce_button.js');
    $plugin_array['gretas_mce_button1'] = plugins_url( 'greta-survey/js/gretas_mce_button.js');
    $plugin_array['gretas_mce_button2'] = plugins_url( 'greta-survey/js/gretas_mce_button.js');
    $plugin_array['gretas_mce_button3'] = plugins_url( 'greta-survey/js/gretas_mce_button.js');
    $plugin_array['gretas_mce_button4'] = plugins_url( 'greta-survey/js/gretas_mce_button.js');
    return $plugin_array;
}