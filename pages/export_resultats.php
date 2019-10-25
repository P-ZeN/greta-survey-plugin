<div class="wrap">
<?php 
// Submenu
require_once __DIR__ . '/plugin_submenu.php';
?>
<h1><span class="dashicons dashicons-format-status gretas-dashicons"></span> <?= esc_html(get_admin_page_title()); ?></h1>
<!--<p><?php echo __FILE__; ?></p>-->

<?php
require_once dirname(dirname(__FILE__)) . '/inc/reponses_list.php';
$reponses_list_table = new Reponses_List_Table();



?>
<form method="post">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
    <?php 
    //$search = empty($_REQUEST['s']) ? '' : sanitize_text_field($_REQUEST['s']);
    $reponses_list_table->prepare_items();
    // $stagiaires_list_table->search_box('Rechercher', 'gretas-list');
    $reponses_list_table->display();
    ?>
</form>

</div>