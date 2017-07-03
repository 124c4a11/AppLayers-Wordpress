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

  register_nav_menu('primary', 'Primary menu');
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


add_filter('excerpt_more', function($more) {
  return '';
});

add_filter('document_title_parts', function($parts) {
  if ( isset($parts['site']) ) unset($parts['site']);
  return $parts;
});

add_filter('document_title_parts', function($title) {
  if ( isset($title['tagline']) ) unset($title['tagline']);
  return $title;
});


function applayers_the_breadcrumb(){
  global $post;
  if(!is_home()){
    echo '<li><a href="'.site_url().'"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li> <li> / </li>';
    if(is_single()){ // posts
    the_category(', ');
    echo '<li> / </li>';
    echo '<li>';
      the_title();
    echo '</li>';
    }
    elseif (is_page()) { // pages
      if ($post->post_parent ) {
        $parent_id  = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
          $page = get_page($parent_id);
          $breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
          $parent_id  = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ($breadcrumbs as $crumb) echo $crumb . '<li> / </li>';
      }
      echo '<li>';
        the_title();
      echo '</li>';
    }
    elseif (is_category()) { // categories
      global $wp_query;
      $obj_cat = $wp_query->get_queried_object();
      $current_cat = $obj_cat->term_id;
      $current_cat = get_category($current_cat);
      $parent_cat = get_category($current_cat->parent);
      if ($current_cat->parent != 0)
        echo(get_category_parents($parent_cat, TRUE, '<li> / </li>'));
      single_cat_title();
    }
    elseif (is_search()) { // search pages
      echo 'Search results "' . get_search_query() . '"';
    }
    elseif (is_tag()) { // tags
      echo single_tag_title('', false);
    }
    elseif (is_day()) { // arhives (days)
      echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> <li> / </li>';
      echo '<li><a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a></li> <li> / </li>';
      echo get_the_time('d');
    }
    elseif (is_month()) { // arhive (moth)
      echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li> <li> / </li>';
      echo get_the_time('F');
    }
    elseif (is_year()) { // arhive (years)
      echo get_the_time('Y');
    }
    elseif (is_author()) { // autors
      global $author;
      $userdata = get_userdata($author);
      echo '<li>Posted ' . $userdata->display_name . '</li>';
    } elseif (is_404()) { // if page not found
      echo '<li>Error 404</li>';
    }

    if (get_query_var('paged')) // number of page
      echo ' (' . get_query_var('paged').'- page)';

  } else { // home
    $pageNum=(get_query_var('paged')) ? get_query_var('paged') : 1;
    if($pageNum>1)
        echo '<li><a href="'.site_url().'"><i class="fa fa-home"></i> Home</a></li> <li> / </li>'.$pageNum.'- page';
    else
        echo '<li><i class="fa fa-home" aria-hidden="ture"></i> Home</li>';
  }
}