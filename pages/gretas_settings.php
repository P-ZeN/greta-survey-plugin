<div class="wrap">
<?php 
require_once __DIR__ . '/plugin_submenu.php';

?>

<h1><span class="dashicons dashicons-format-status gretas-dashicons"></span> <?= esc_html(get_admin_page_title()); ?></h1>


<?php 
if ( isset( $_GET['settings-updated'] ) ) {

    ?>
    <div class="notice notice-success">
    <p>Paramètres sauvegardés</p>
    </div>
    <?php
    
    }
 // echo '<pre>' . print_r($_REQUEST, true) . '</pre>';

    ?>
<form method="post" action="options.php"> 
<?php
    // output security fields for the registered setting "wporg"
    settings_fields( 'gretas_settings' );
    // output setting sections and their fields
    // (sections are registered for "wporg", each field is registered to a specific section)
    do_settings_sections( 'gretas_settings' );
    // output save settings button
    submit_button( 'Sauvegarder les paramètres' );
?>
</form>
</div>