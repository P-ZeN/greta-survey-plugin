<div class="wrap">
<?php 
// Submenu
require_once __DIR__ . '/plugin_submenu.php';
?>
<h1><span class="dashicons dashicons-format-status gretas-dashicons"></span> <?= esc_html(get_admin_page_title()); ?></h1>
<!--<p><?php echo __FILE__; ?></p>-->

<?php
    include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

?>
</div>