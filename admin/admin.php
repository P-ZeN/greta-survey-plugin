<?php

// don't load directly
if (!defined('ABSPATH')) {
    die('-1');
}

class GretaSurveyAdmin {
  
    private $greta_screen_name;
    private static $instance;
     /*......*/

    static function GetInstance()
    {
        
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    function greta_plugin_top_menu()
    {
        $hook_home = add_menu_page(
            'GRETA Survey',
            'GRETA Survey',
            'manage_options',
            'gretas_home',
            'gretas_home_html',
            'dashicons-format-status'
        );
        $hook_envois = add_submenu_page(
            'gretas_home',
            'GRETA Survey - Stagiaires et Professionnels',
            'Stagiaires & Pros',
            'manage_options',
            'gretas_envoi_email',
            'gretas_envoi_email_html'
        );
        $hook_ajout = add_submenu_page(
            'gretas_home',
            'GRETA Survey - Ajouter un stagiaire',
            'Ajouter un stagiaire',
            'manage_options',
            'gretas_ajout_stagiaire',
            'gretas_ajout_stagiaire_html'
        );
        add_submenu_page(
            'gretas_home',
            'GRETA Survey - Questionnaires de satisfaction',
            'Questionnaires de satisfaction',
            'manage_options',
            'edit.php?post_type=gretas_form',
            //'gretas_manage_emails_models_html'
            NULL
        );
        add_submenu_page(
            'gretas_home',
            'GRETA Survey - Modèles d\'emails',
            'Modèles d\'emails',
            'manage_options',
            'edit.php?post_type=emails_models',
            //'gretas_manage_emails_models_html'
            NULL
        );
        $hook_export = add_submenu_page(
            'gretas_home',
            'GRETA Survey - Réponses aux questionnaires',
            'Réponses',
            'manage_options',
            'gretas_export_resultats',
            'gretas_export_resultats_html'
        );
        add_submenu_page(
            'gretas_home',
            'GRETA Survey - Paramètres',
            'Paramètres',
            'manage_options',
            'gretas_settings',
            'gretas_settings_html'
        );
        //remove_submenu_page('gretas_home', 'gretas_home');

        function gretas_home_html()
        {
            include_once dirname(dirname(__FILE__)) . '/pages/main_page.php';
        }

        function gretas_ajout_stagiaire_html()
        {
            include_once dirname(dirname(__FILE__)) . '/pages/ajout_stagiaire.php';
        }

        function gretas_envoi_email_html()
        {
            include_once dirname(dirname(__FILE__)) . '/pages/envoi_email.php';
        }
        
        function gretas_export_resultats_html()
        {
            include_once dirname(dirname(__FILE__)) . '/pages/export_resultats.php';
        }
                
        function gretas_settings_html()
        {
            include_once dirname(dirname(__FILE__)) . '/pages/gretas_settings.php';
        }
                
        function gretas_manage_emails_models_html()
        {
            include_once dirname(dirname(__FILE__)) . '/pages/emails_models.php';
        }
                
        // options liste stagiaires
        function gretas_stagiaires_screen_options()
        {
            $options = 'per_page';
            $per_page = get_option('greta-survey_stagiaires_per_page');
            $args = array(
                'label' => 'Nombre de lignes par page',
                'default' => $per_page,
                'option' =>'greta-survey_stagiaires_per_page'
            );
            add_screen_option($options, $args);

            require_once dirname(dirname(__FILE__)) . '/inc/stagiaires_list.php';
            $Stagiaires_List_Table = new Stagiaires_List_Table;
            add_screen_option(
                'columns_prefs',
                array(
                //'columns_prefs'  => $Stagiaires_List_Table->columns_details()
                )
            );
        }

        // nombre d'éléments de liste par pages
        add_action("load-".$hook_envois, 'gretas_stagiaires_screen_options', 10, 0);
        add_filter('set-screen-option', 'gretas_set_stagiaires_option', 10, 3);
 
        function gretas_set_stagiaires_option($status, $option, $value) {
            if (in_array(array('greta-survey_stagiaires_per_page', 'greta-survey_stagiaires_layout_columns'), $option)) {
                return $value;
            }
            return $status;
        }

        // options liste reponses
        function gretas_reponses_screen_options()
        {
            $options = 'per_page';
            $per_page = get_option('greta-survey_reponses_per_page');
            $args = array(
                'label' => 'Nombre de lignes par page',
                'default' => $per_page,
                'option' =>'greta-survey_reponses_per_page'
            );
            add_screen_option($options, $args);

            require_once dirname(dirname(__FILE__)) . '/inc/reponses_list.php';
            $Reponses_List_Table = new Reponses_List_Table;
            add_screen_option(
                'columns_prefs',
                array(
                //'columns_prefs'  => $Stagiaires_List_Table->columns_details()
                )
            );
        }

        // nombre d'éléments de liste par pages
        add_action("load-".$hook_export, 'gretas_reponses_screen_options', 10, 0);
        add_filter('set-screen-option', 'gretas_set_reponses_option', 10, 3);
 
        function gretas_set_reponses_option($status, $option, $value) {
            if (in_array(array('greta-survey_reponses_per_page', 'greta-survey_reponses_layout_columns'), $option)) {
                return $value;
            }
            return $status;
        }

        // Greta Settings
        require_once dirname(__FILE__) . '/greta_settings.php';
        add_action( 'admin_init', 'gretas_settings_init' );
    }

    public function InitPlugin()
    {
         add_action('admin_menu', array($this, 'greta_plugin_top_menu'));
    }

}

$GretaSurveyAdmin = GretaSurveyAdmin::GetInstance();
$GretaSurveyAdmin->InitPlugin();