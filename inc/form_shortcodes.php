<?php

// Shortcode [gform_question_simple_courte]
function gretas_form_question_simple_courte_include($atts = [], $content ='') {

    $output = ''; 

    $output .= '<div class="form-group mb-5">';
    $output .= sprintf('    <label for="form_datas[%s]">%s</label>', $atts['name'], $content);
    $output .= sprintf('    <input type="text" name="form_datas[%s]" class="form-control" required/>', $atts['name']);
    $output .= '</div>';

    return $output;
}

// Shortcode [gform_question_simple_longue]
function gretas_form_question_simple_longue_include($atts = [], $content ='') {

    $output = ''; 

    $rows = isset($atts['rows']) ? $atts['rows'] : 3;
    $output .= '<div class="form-group mb-5">';
    $output .= sprintf('    <label for="form_datas[%s]">%s</label>', $atts['name'], $content);
    $output .= sprintf('    <textarea name="form_datas[%1$s]" rows="%2$u" class="form-control" required></textarea>', $atts['name'], $rows);
    $output .= '</div>';

    return $output;
}

// Shortcode [gform_choix_multiples_radio]
function gform_choix_multiples_radio_include($atts = [], $content ='') {

    $output = ''; 
    $output .= '<div class="form-group mb-5">';
    $output .= sprintf('    <label>%s</label>', $content);

    $options = explode('|', $atts['options']);
    $i = 0;
    foreach ($options as $option) {
        $output .= '<div class="form-check">';
        $output .= sprintf('    <input class="form-check-input" type="radio" name="form_datas[%1$s]" id="%1$s%2$u" value="%2$u"required>', $atts['name'], $i);
        $output .= sprintf('    <label class="form-check-label" for="%1$s%2$u">%3$s</label>', $atts['name'], $i, $option);
        $output .= '</div>';
        $i++;
    }

    $output .= '</div>';

    return $output;
}

// Shortcode [gform_choix_multiples_checkboxes]
function gform_choix_multiples_checkboxes_include($atts = [], $content ='') {

    $output = ''; 
    $output .= '<div class="form-group mb-5">';
    $output .= sprintf('    <label>%s</label>', $content);

    $options = explode('|', $atts['options']);
    $i = 0;
    foreach ($options as $option) {
        $output .= '<div class="form-check">';
        $output .= sprintf('    <input class="form-check-input" type="checkbox" name="form_datas[%1$s][]" id="%1$s%2$u" value="%2$u">', $atts['name'], $i);
        $output .= sprintf('    <label class="form-check-label" for="%1$s%2$u">%3$s</label>', $atts['name'], $i, $option);
        $output .= '</div>';
        $i++;
    }

    $output .= '</div>';

    return $output;
}

// Shortcode [gform_evaluation_1-5]
function gform_evaluation_1_5_include($atts = [], $content ='') {

    $output = ''; 
    $output .= '<div class="row mb-5">';
    $output .= '    <div class="col-sm-7 form-group">';
    $output .= sprintf('    <label>%s</label>', $content);
    $output .= '    </div>';
    $output .= '    <div class="col-sm-5 radio-label-vertical-wrapper">';
        $output .= '    <div class="form-group text-right">';

    for ($i = 1; $i < 6; $i++) {
        $output .= sprintf('<label class="radio-label-vertical evaluation evaluation_%2$u" for="%1$s%2$u">', $atts['name'], $i);
        $output .= sprintf('<input type="radio" name="form_datas[%1$s]" id="%1$s%2$u" value="%2$u" required>%2$u', $atts['name'], $i);
        $output .= '</label>';
    }
    $output .= '        </div>';
    $output .= '    </div>';
    $output .= '</div>';

    return $output;
}


// initialisation des shortcodes
function gretas_form_shortcodes_init() {
    // init shortcodes
    add_shortcode('gform_question_simple_courte', 'gretas_form_question_simple_courte_include');
    add_shortcode('gform_question_simple_longue', 'gretas_form_question_simple_longue_include');
    add_shortcode('gform_choix_multiples_radio', 'gform_choix_multiples_radio_include');
    add_shortcode('gform_choix_multiples_checkboxes', 'gform_choix_multiples_checkboxes_include');
    add_shortcode('gform_evaluation_1_5', 'gform_evaluation_1_5_include');
}
add_action('init', 'gretas_form_shortcodes_init');