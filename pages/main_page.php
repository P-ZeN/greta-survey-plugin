<div class="wrap">
<?php 
require_once __DIR__ . '/plugin_submenu.php';
?>

<h1><span class="dashicons dashicons-format-status gretas-dashicons"></span> <?= esc_html(get_admin_page_title()); ?></h1>
<!--<p><?php echo __FILE__; ?></p>-->
<h3>Un plugin de gestion de questionnaires de satisfaction</h3>

<?php
if (isset($_GET['message'])) {
    echo '<div class="notice notice-success is-dismissible">';
    echo '<p>'.urldecode($_GET['message']).'</p>';
    echo '</div>';
}


$options = get_option('gretas_options');
$file_name = $options['gretas_csv_import_file'];
// echo '$file_name = ' . $file_name;
?>


<p>&nbsp;</p>
<h4>Chargement des données CSV</h4>
<p class="gretas_debug"><strong>Fonction de chargement des données csv (tache cron) :</strong><br>
Le fichier à importer doit se trouver dans <code><?php echo $_SERVER['DOCUMENT_ROOT']; ?>/wp-content/plugins/greta-survey/ftp_import</code>. Il peut ensuite être sélectionné <a href="?page=gretas_settings">dans les paramètres du plugin</a>. 
Le fichier actuellement séléctionné est  : <code><?php echo $file_name; ?></code>.</p>
<p class="gretas_debug">Le lien à utiliser par cron est : <code><a href="<?php echo admin_url('admin-post.php'); ?>?action=gretas_import_csv" target="_blank"><?php echo admin_url('admin-post.php'); ?>?action=gretas_import_csv</a></code></p>
<p class="gretas_debug">Pour importer manuellement les données, cliquer sur le bouton ci-dessous :
<form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
  <input type="hidden" name="action" value="gretas_import_csv">
  <input type="submit" value="Importer les données csv" class="button button-primary mt-0">
</form></p>

<!--<h4>Questionaires de satisfaction</h4>
<p class="gretas_debug">
    <strong>Les questionnaires de satisfaction peuvent être visualisés en suivant ces liens :</strong><br>
<?php
/*if ($handle = opendir(dirname(dirname(__FILE__)) . '/formulaires/')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && substr($entry, count($entry)-4) === 'php') {
            $id = substr($entry, count($entry)-6, count($entry)-5);
            $url = get_site_url() . '/gretas-survey/questionnaire?id=' . $id;
           echo 'Questionnaire n°' . $id . ' : <a href="' . $url . '" target="_blank"><span class="dashicons dashicons-external "></span><strong>' . $url . '</strong></a><br>';
        }
    }
    closedir($handle);
}*/
?>
</p>-->
<h4>Mémoire de travail</h4>
<p class="gretas_debug"><strong>Mémoire actuellement allouée à Wordpress (WP_MEMORY_LIMIT) = <?php echo WP_MEMORY_LIMIT; ?></strong><br>
Selon la taille du fichier .csv à importer, la configuration du PHP peut ne pas permettre l'exécution correcte. Il faut alors augmenter les valeurs <code>max_execution_time</code> et <code>memory_limit</code> dans le fichier <code>php.ini</code>. </p>
<p class="gretas_debug">Wordpress utilise également un paramètre de mémoire, par défaut de 32 Mo. Si le plugin ne fonctionne pas pour cause de manque de mémoire, ajouter <code>define( 'WP_MEMORY_LIMIT', '512M' );</code> dans le fichier <code>wp-config.php</code><br>
(Cette modification ne sera prise en compte que si le fichier <code>php.ini</code> de l'hébergement alloue suffisament de mémoire).</p>
<p class="gretas_debug">L'importaton du fichier exemple de 16 413 lignes demande un temps d'exécution d'environ 3 minutes et 512 Mo de mémoire.
</p>

<p>&nbsp;</p>
<h4>Crédits</h4>
<img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>/images/bbonom.png" style="float: left; margin: 0px 10px 20px 0;" />
<p class="gretas_debugXXX">
Pour toute question concernant le plugin GRETA Survey, vous pouvez contacter l'auteur :<br>
<strong>Philippe Zénone</strong><br>
<a href="mailto:philippe.zenone@free.fr">philippe.zenone@free.fr</a> | <a href="https://philippezenone.net" target="_blank">philippezenone.net</a><br>
</p>
</div>