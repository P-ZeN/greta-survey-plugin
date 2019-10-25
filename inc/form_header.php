<?php
if (!empty($message)) {
    ?>
<div class="notice notice-error">
    <p class="text-center"><?php echo $message; ?></p>
</div>

    <?php
}
if (isset($stagiaire)) {
    ?>
 <div class="notice notice-success">
 <p class="text-center"><strong><u>Datas stagiaire :</u></strong></p>
 <p class="text-center"><?php echo implode(', ', (array) $stagiaire); ?></p>
</div>
<?php
}
?>

<form class="form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
   
    <?php
     /* -----------------------------------------------------
        Champs masqués pour la sécurité : 
     --------------------------------------------------------*/?>
    <input name="action" value="gretas_form" type="hidden">
    <?php
     wp_nonce_field('gretas_form', 'security-code-here');

    if (isset($stagiaire)) {
        //echo '$stagiaire : <pre>' . print_r($stagiaire, true) .'</pre>';
        //echo '$token : <pre>' . print_r($token, true) .'</pre>';
        echo '<input name="reponse_id" value="'.$token->id.'" type="hidden">';
    }
?>
<fieldset <?php echo $disabled; ?>>
