<?php
if( isset( $_GET[ 'page' ] ) ) {
    $active_tab = $_GET[ 'page' ];
} // end if
?>
<h2 class="nav-tab-wrapper">
    <a class="nav-tab <?php echo $active_tab == 'gretas_home' ? 'nav-tab-active' : ''; ?>" href="?page=gretas_home">GRETA Survey - Home</a>
    <a class="nav-tab <?php echo $active_tab == 'gretas_envoi_email' ? 'nav-tab-active' : ''; ?>" href="?page=gretas_envoi_email">Stagiaires et Professionnels</a>
    <a class="nav-tab <?php echo $active_tab == 'gretas_ajout_stagiaire' ? 'nav-tab-active' : ''; ?>" href="?page=gretas_ajout_stagiaire">Ajouter un stagiaire</a>
    <a class="nav-tab <?php echo $active_tab == 'edit.php?post_type=gretas_form' ? 'nav-tab-active' : ''; ?>" href="edit.php?post_type=gretas_form">Questionnaires</a>
    <a class="nav-tab <?php echo $active_tab == 'edit.php?post_type=emails_models' ? 'nav-tab-active' : ''; ?>" href="edit.php?post_type=emails_models">Modèles d'emails</a>
    <a class="nav-tab <?php echo $active_tab == 'gretas_export_resultats' ? 'nav-tab-active' : ''; ?>" href="?page=gretas_export_resultats">Réponses aux questionnaires</a>
    <a class="nav-tab <?php echo $active_tab == 'gretas_settings' ? 'nav-tab-active' : ''; ?>" href="?page=gretas_settings">Paramètres</a>
</h2>