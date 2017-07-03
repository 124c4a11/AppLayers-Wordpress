<?php

function applayers_setup() {
  load_theme_textdomain('applayers');

  add_theme_support('title-tag');

  add_theme_support('custom-logo', array(
    'width' => '134',
    'height' => '31',
    'flex-height' => true
  ));

  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(730, 446);

  add_theme_support('html5', array(
    'search-form',
    'comment-fomr',
    'comment-list',
    'gallery',
    'caption'
  ));

  add_theme_support('post-formats', array(
    'aside',
    'image',
    'video',
    'gallery'
  ));
}
add_action('after_setup_theme', 'applayers_setup');


function applayers_scripts() {
  wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.min.css');
  wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
  wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
  wp_enqueue_style( 'style-css', get_stylesheet_uri() );

  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '1.0.0', true );
  wp_enqueue_script( 'css3-animate-it', get_template_directory_uri() . '/js/css3-animate-it.js', array(), '1.0.0', true );
  wp_enqueue_script( 'jquery.easing', get_template_directory_uri() . '/js/jquery.easing.min.js', array(), '1.0.0', true );
  wp_enqueue_script( 'agency', get_template_directory_uri() . '/js/agency.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'applayers_scripts' );