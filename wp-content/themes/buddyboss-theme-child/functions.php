<?php

/**
 * @package BuddyBoss Child
 * The parent theme functions are located at /buddyboss-theme/inc/theme/functions.php
 * Add your own functions at the bottom of this file.
 */


/****************************** THEME SETUP ******************************/

/**
 * Sets up theme for translation
 *
 * @since BuddyBoss Child 1.0.0
 */
function buddyboss_theme_child_languages()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain('buddyboss-theme', get_stylesheet_directory() . '/languages');

  // Translate text from the CHILD theme only.
  // Change 'buddyboss-theme' instances in all child theme files to 'buddyboss-theme-child'.
  // load_theme_textdomain( 'buddyboss-theme-child', get_stylesheet_directory() . '/languages' );

}
add_action('after_setup_theme', 'buddyboss_theme_child_languages');

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function buddyboss_theme_child_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  // Styles
  wp_enqueue_style('buddyboss-child-css', get_stylesheet_directory_uri() . '/assets/css/custom.css', '', '1.0.0');

  // Javascript
  wp_enqueue_script('buddyboss-child-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', '', '1.0.0');
}
add_action('wp_enqueue_scripts', 'buddyboss_theme_child_scripts_styles', 9999);


/****************************** CUSTOM FUNCTIONS ******************************/


function acf_load_color_field_choices($field)
{
  // reset choices
  $field['choices'] = array();

  $posts = get_posts(array(
    'posts_per_page'  => -1,
    'post_type'      => 'presenter'
  ));

  foreach ($posts as $key => $value) {
    array_push($field['choices'], $value->post_title);
  }
	
  // return the field
  return $field;
}

add_filter('acf/load_field/name=presenter', 'acf_load_color_field_choices');


//provider
function provider_choices($field)
{
  // reset choices
  $field['choices'] = array();

  $posts = get_posts(array(
    'posts_per_page'  => -1,
    'post_type'      => 'provider'
  ));

  foreach ($posts as $key => $value) {
    array_push($field['choices'], $value->post_title);
  }
    
  // return the field
  return $field;
}

add_filter('acf/load_field/name=provider', 'provider_choices');

//disclaimers
function disclaimers_choices($field)
{
  // reset choices
  $field['choices'] = array();

  $posts = get_posts(array(
    'posts_per_page'  => -1,
    'post_type'      => 'disclaimers'
  ));

  foreach ($posts as $key => $value) {
    array_push($field['choices'], $value->post_title);
  }
    
  // return the field
  return $field;
}

add_filter('acf/load_field/name=which_disclaimer_to_include', 'disclaimers_choices'); 
