<?php
/*
Plugin Name:  GRETA Survey
Description:  Plugin de gestion des questionnaires de satisfaction stagiaires
Version:      20181122
Author:       Philippe Zénone
Author URI:   https://philippezenone.net/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

ini_set('display_errors', 'On');
error_reporting(E_ALL);
register_activation_hook( __FILE__, 'my_activation_func' ); function my_activation_func() {
    file_put_contents(__DIR__.'/my_loggg.txt', ob_get_contents());
}
// define( 'WP_MEMORY_LIMIT', '512M' );


// enqueue scripts/css
wp_register_style('greta-survey', plugins_url('/css/style.css', __FILE__));
wp_enqueue_style('greta-survey');
wp_register_script('greta-survey', plugins_url('/js/gretas_scripts.js', __FILE__));
wp_enqueue_script('greta-survey');

// Déclare les customs post email
require_once __DIR__ . '/inc/custom_posts_email.php';
add_action('init', 'gretas_custom_posts_email');
register_activation_hook(__FILE__, 'add_my_first_email_model');

// Gestion formulaires
require_once __DIR__ . '/inc/custom_posts_form.php';
add_action('init', 'gretas_custom_posts_form');
require_once __DIR__ . '/inc/form_shortcodes.php';
require_once __DIR__ . '/inc/process_form.php';
register_activation_hook(__FILE__, 'add_my_first_gretas_form');

// Menus admin
if (is_admin()) {
    include_once __DIR__ . '/admin/admin.php';
}


// Base de données stagiaires
require_once __DIR__ . '/db/stagiaires_db.php';
register_activation_hook(__FILE__, 'gretadb_stagiaires_install');
// Base de données reponseses
require_once __DIR__ . '/db/reponses_db.php';
register_activation_hook(__FILE__, 'gretadb_reponses_install');

?>