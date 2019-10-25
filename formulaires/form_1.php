<?php 
/**
 * Exemple de formulaire.
 */

// inclusion header
require_once dirname(dirname(__FILE__)) . '/inc/form_header.php';
?>
    <h3>Questionnaire n°1</h3>
    <div class="form-group">
        <label for="firstname">First name</label>
        <input type="text" name="form_datas[firstname]" class="form-control">
    </div>

    <div class="form-group">
        <label for="lastname">Last name</label>
        <input type="text" name="form_datas[lastname]" class="form-control">
    </div>

    <div class="form-group">
        <label for="message">Message</label>
        <textarea name="form_datas[message]" class="form-control"> Enter text here…</textarea>
    </div>
<?php
// inclusion footer
require_once dirname(dirname(__FILE__)) . '/inc/form_footer.php';
?>
