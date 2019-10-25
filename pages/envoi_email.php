<div class="wrap">
<?php 
// Submenu
require_once __DIR__ . '/plugin_submenu.php';
?>

<h1><span class="dashicons dashicons-format-status gretas-dashicons"></span> <?= esc_html(get_admin_page_title()); ?></h1>
<!--<p><?php echo __FILE__; ?></p>-->

<?php
// Gestion emails
require_once dirname(__DIR__) . '/inc/process_emails.php';

if (isset($message)) {
    echo '<div class="notice notice-success is-dismissible">';
    echo '<p>'.urldecode($message).'</p>';
    echo '</div>';
}


// Liste des stagiaires
require_once dirname(dirname(__FILE__)) . '/inc/stagiaires_list.php';
$stagiaires_list_table = new Stagiaires_List_Table();

// Liste des modèles d'emails
$email_models = get_gretas_custom_posts_email();
if ($email_models->have_posts()) {
    $emails_models_select_list = '<select class="form-control" name="email_model" id="email_model" style="max-width: 200px;">';
    $emails_models_select_list .= '<option value="">--Choisir une option--</option>';
    while ($email_models->have_posts()) { 
        $email_models->the_post();
        $emails_models_select_list .= sprintf('<option value="%d">%s</option>', get_the_ID(), get_the_title());
    }
    $emails_models_select_list .= '</select>';
    wp_reset_postdata();
}

// Liste des questionnairess
$posts_form = get_gretas_custom_posts_form();
if ($posts_form->have_posts()) {
    $posts_form_radio_list = '<fieldset>';
    while ($posts_form->have_posts()) { 
        $posts_form->the_post();
        $posts_form_radio_list .= '<label>';
        $posts_form_radio_list .= '<input type="radio" value="' . get_the_ID() . '" name="form_id">' . get_the_title() . '';
        $posts_form_radio_list .= '</label><br>';
}
    $posts_form_radio_list .= '</fieldset>';
    wp_reset_postdata();
}

// affichage des emails envoyés
 if (isset($emails_envoyes) && !empty($emails_envoyes)) {
    // echo '$emails_envoyes <pre>' . print_r($emails_envoyes, true) . '</pre>';
    $i = 1;
    foreach ($emails_envoyes as $email) {
        echo '<div class="welcome-panel">';
        echo sprintf('<h4>Email n°%s</h4>  <p>', $i);
        echo sprintf('<strong>To :</strong> %s <br>', $email['to']);
        echo sprintf('<strong>Subject :</strong> %s <br>', $email['subject']);
        echo nl2br(sprintf('<strong>Content :</strong><br><div class="welcome-panel">%s</div>', $email['content']));
        
        echo '</p></div>';
        // echo $email['dump'];
        $i++;
    }
}
?>

<form method="post">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
    <?php 
    $search = empty($_REQUEST['s']) ? '' : sanitize_text_field($_REQUEST['s']);
    $stagiaires_list_table->prepare_items($search);
    $stagiaires_list_table->search_box('Rechercher', 'gretas-list');
    $stagiaires_list_table->display();
    ?>
    <table class="form-table fixed">
        <tbody>    
        <tr>
                <th scope="row">Sélectionner le format de l'e-mail à envoyer :</th>
                <td>
<?php
echo $emails_models_select_list;
?>
                </td>
            </tr>
            <tr>
                <th scope="row">Sélectionner le questionnaire à utiliser :</th>
                <td>
                    <fieldset>
<?php
echo $posts_form_radio_list;
?>
                    </fieldset>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit"><input type="submit" name="envoi_email" id="envoi_email" class="button button-primary" value="Envoyer un e-mail"  disabled="disabled"></p>
</form>

</div>
<script>

jQuery(document).ready(function () {
    var email = 0;
    var questionnaire = 0;
    var checkedValues = 0;

    jQuery("#email_model").change(function () {
        email = jQuery(this).val();
        cansubmit();
    });
    
    jQuery("input[name=form_id]:radio").change(function () {
        questionnaire = jQuery(this).val();
        cansubmit();
    });
    
    jQuery("input[type=checkbox]").change(function () {
        checkedValues = 0;
        jQuery('.check-column input:checkbox').each(function () {
             if (this.checked) checkedValues++;
            });
        cansubmit();
    });

    var cansubmit = function () {
        document.getElementById('envoi_email').disabled = (email < 1 || questionnaire < 1 || checkedValues == 0);
    };
    
});
</script>
