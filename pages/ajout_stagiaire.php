<div class="wrap">
<?php 
// Submenu
require_once __DIR__ . '/plugin_submenu.php';
?>
<h1><span class="dashicons dashicons-format-status gretas-dashicons"></span> <?= esc_html(get_admin_page_title()); ?></h1>
<!--<p><?php echo __FILE__; ?></p>-->

<?php

if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'ajouter_un_stagiaire'){
    global $wpdb;
    $table_name = $wpdb->prefix . 'gretadb_stagiaires';
    $format = array('%s','%d');

    $line = array('id' => '');
    foreach ($_POST as $key => $value) {
        if (substr($key, 0, 4) === 'col_') {
            $champ = array($key => $value);
            $line = array_merge($line, $champ);
        }
    }

    echo 'line = <pre>'. print_r($line, true) .'</pre>';
    //$prepare = "SELECT COUNT(*) FROM $table_name WHERE col_85=, $line[col_85];

    // echo '<pre>'. $prepare .'</pre>';
    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE col_85 LIKE %s", $line['col_85']));
    
    if (!$exists) {
        $wpdb->insert($table_name, $line, $format);

        ?>
        <div class="notice notice-success is-dismissible">
            <p>1 élément ajouté</p>
        </div>
        <?php

    }
    else {
        ?>
        <div class="notice notice-error is-dismissible">
            <p>une erreur s'est produite et aucun élément n'a été ajouté</p>
        </div>
        <?php

    }

}


?>



<form method="post">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
    <input type="hidden" name="action" value="ajouter_un_stagiaire" />
    <table class="form-table" style="max-width: 600px">
    <?php 
    for ($i = 0; $i < 85; $i++) {
        $name = 'col_' . ($i + 1);
        $title = 'Colonne ' . ($i + 1);
?>
        <tr class="form-field">
            <th scope="row"><label for="<?php echo $name; ?>"><?php echo $title; ?></label></th>
            <td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" /></td>
        </tr>
<?php
    }
    ?>
    </table>
    <p class="submit"><input type="submit" name="createstagiaire" id="createstagiaire" class="button button-primary" value="Ajouter un stagiaire"></p>
</form>

</div>
<?php
