<?php
function gretas_settings_init() {
    // register a new setting for "gretas" page
    register_setting('gretas_settings', 'gretas_options');

    if (get_option('gretas_options') === false) {
        $defaults = array(
            'gretas_thanks_submission_title' => 'Vos réponses ont bien été enregistréeees',
            'gretas_thanks_submission_template' => 'Vos réponses ont bien été enregistrées et le GRETA vous remercie.',
            'gretas_csv_import_file' => 'greta_example-datas_100-lignes.csv',
            'gretas_csv_import_separator' => 'semicolon',
            'gretas_csv_export_separator' => 'tabulation',
        );
        update_option('gretas_options', $defaults);
    }

   // Message de remerciement
    add_settings_section(
        'gretas_thanks_submission',
        'Message de remerciement',
        'gretas_thanks_submission_desc',
        'gretas_settings'
    );

    add_settings_field(
        'gretas_thanks_submission_title',
        'Titre de la page de remerciement', 
        'gretas_thanks_submission_title_field',
        'gretas_settings',
        'gretas_thanks_submission'
    );

    function gretas_thanks_submission_title_desc() {
        echo "<p>Titre de la page de remerciement affichée quand le questionnaire est rempli.</p>";
    }

    function gretas_thanks_submission_title_field($args) {
        $options = get_option('gretas_options');
        // echo 'options = <pre>' . print_r($options, true) . '</pre>';
        $gretas_thanks_submission_title = (isset($options['gretas_thanks_submission_title'])) ? $options['gretas_thanks_submission_title'] : '';
        $gretas_thanks_submission_title = sanitize_text_field($gretas_thanks_submission_title); //sanitise output

        echo '<div id="titlediv"><div id="titlewrap">';
        // echo '<label class="" id="title-prompt-text" for="title">Saisissez votre titre ici</label>';
        echo sprintf(
            '<input type="text" name="gretas_options[gretas_thanks_submission_title]" size="30" id="title" spellcheck="true" autocomplete="off" value="%s"/>',  $gretas_thanks_submission_title
        );
        echo '</div></div>';
   
    }

    add_settings_field(
        'gretas_thanks_submission_template',
        'Texte de la page de remerciement', 
        'gretas_thanks_submission_field',
        'gretas_settings',
        'gretas_thanks_submission'
    );

    function gretas_thanks_submission_desc() {
        echo "<p>Texte de la page de remerciement affiché quand le questionnaire est rempli.</p>";
    }

    function gretas_thanks_submission_field($args) {
        $options = get_option('gretas_options');
        // echo 'options = <pre>' . print_r($options, true) . '</pre>';
        $gretas_thanks_submission_template = (isset($options['gretas_thanks_submission_template'])) ? $options['gretas_thanks_submission_template'] : '';
        $gretas_thanks_submission_template = wp_filter_post_kses($gretas_thanks_submission_template); //sanitise output

        $content = $gretas_thanks_submission_template;
        $editor_id = 'gretas_thanks_submission_template';
        $settings = array(
            'textarea_name'       => 'gretas_options[gretas_thanks_submission_template]',
            'textarea_rows'       => 10,
        );
        wp_editor( $content, $editor_id, $settings );
   
    }


    // CSV
    add_settings_section(
        'gretas_csv_settings',
        'Paramètres CSV',
        'gretas_csv_settings_desc',
        'gretas_settings'
    );
    function gretas_csv_settings_desc() {
        echo '<p>Paramètres utilisés pour l\'importation et l\'exportation des données au format CSV.</p>';
    }

    add_settings_field(
        'gretas_csv_import_file',
        'Fichier utilisé pour l\'importation cron', 
        'gretas_csv_import_file_field',
        'gretas_settings',
        'gretas_csv_settings'
    );

    function gretas_csv_import_file_field($args) {
        $options = get_option('gretas_options');
        $gretas_csv_import_file= (isset($options['gretas_csv_import_file'])) ? $options['gretas_csv_import_file'] : '';
        $gretas_csv_import_file = sanitize_text_field($gretas_csv_import_file); //sanitise output
        echo '<select name="gretas_options[gretas_csv_import_file]">';
        if ($handle = opendir(dirname(dirname(__FILE__)) . '/ftp_import/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && substr($entry, count($entry)-4) === 'csv') {
                    echo '  <option value="' . $entry . '" ' . selected($gretas_csv_import_file, $entry) . '>' . $entry . '</option>';
                }
            }
            closedir($handle);
        }
        echo '</select>';
    }

    add_settings_field(
        'gretas_csv_import_separator',
        'Séparateur pour l\'importation', 
        'gretas_csv_import_separator_field',
        'gretas_settings',
        'gretas_csv_settings'
    );

    function gretas_csv_import_separator_field($args) {
        $options = get_option('gretas_options');
        // echo '<pre>' . print_r($options, true) . '</pre>';
        $gretas_csv_import_separator = (isset($options['gretas_csv_import_separator'])) ? $options['gretas_csv_import_separator'] : '';
        $gretas_csv_import_separator = sanitize_text_field($gretas_csv_import_separator); //sanitise output
        echo '<select name="gretas_options[gretas_csv_import_separator]">';
        echo '  <option value="tabulation" ' . selected($gretas_csv_import_separator, 'tabulation') . '>Tabulation (↦)</option>';
        echo '  <option value="semicolon" ' . selected($gretas_csv_import_separator, 'semicolon', true) . '>Point virgule (;)</option>';
        echo '</select>';
    }

    add_settings_field(
        'gretas_csv_export_separator',
        'Séparateur pour l\'exportation', 
        'gretas_csv_export_separator_field',
        'gretas_settings',
        'gretas_csv_settings'
    );

    function gretas_csv_export_separator_field($args) {
        $options = get_option('gretas_options');
        $gretas_csv_export_separator = (isset($options['gretas_csv_export_separator'])) ? $options['gretas_csv_export_separator'] : '';
        $gretas_csv_export_separator = sanitize_text_field($gretas_csv_export_separator); //sanitise output
        echo '<select name="gretas_options[gretas_csv_export_separator]">';
        echo '  <option value="tabulation" ' . selected($gretas_csv_export_separator, 'tabulation') . '>Tabulation (↦)</option>';
        echo '  <option value="semicolon" ' . selected($gretas_csv_export_separator, 'semicolon', true) . '>Point virgule (;)</option>';
        echo '</select>';
    }

}


?>