<?php

function sdw_register_stuff() {
  wp_register_script('tags-input', get_stylesheet_directory_uri() . '/js/jquery.tagsinput.min.js', array('jquery'), '1.3.5', true);
  wp_register_script('validate', get_stylesheet_directory_uri() . '/js/jquery.validate.min.js', array('jquery'), '1.15', true);
}
add_action('wp_enqueue_scripts', 'sdw_register_stuff');


function sdw_enqueue_stuff() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

    wp_enqueue_script('tags-input');
    wp_enqueue_script('validate');

}
add_action('wp_enqueue_scripts', 'sdw_enqueue_stuff');

?>
