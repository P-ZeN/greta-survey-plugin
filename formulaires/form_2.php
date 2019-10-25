<?php 
/**
 * Exemple de formulaire.
 */

// inclusion header
require_once dirname(dirname(__FILE__)) . '/inc/form_header.php';
?>
    <h3>Questionnaire n°2</h3>
    <div class="row">
        <div class="col-sm-6">
            <p><strong>Texte de la question 1 :</strong></p>
        </div>
        <div class="col-sm-6">
            <?php for($i = 0; $i < 5; $i++) { ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="form_datas[question_1]" id="question1_<?php echo $i; ?>" value="<?php echo $i; ?>">
                <label class="form-check-label" for="question1_<?php echo $i; ?>"><?php echo $i; ?></label>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <p><strong>Texte de la question 2 :</strong></p>
        </div>
        <div class="col">
            <?php for($i = 0; $i < 5; $i++) { ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="form_datas[question_2]" id="question2_<?php echo $i; ?>" value="<?php echo $i; ?>">
                <label class="form-check-label" for="question2_<?php echo $i; ?>"><?php echo $i; ?></label>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="form-group">
        <label for="message">Message</label>
        <textarea name="form_datas[message]" class="form-control"> Enter text here…</textarea>
    </div>
<?php
// inclusion footer
require_once dirname(dirname(__FILE__)) . '/inc/form_footer.php';
?>
