<?php

require get_template_directory() . '/inc/class-applayers-widget-recent-posts.php';

require get_template_directory() . '/inc/class-applayers-widget-subscribe-form.php';


function applayers_register_foo_widget() {
	register_widget( 'Applayers_Widget_Recent_Posts' );
	register_widget( 'Applayers_Widget_Subscribe' );
}
add_action( 'widgets_init', 'applayers_register_foo_widget' );


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

  add_image_size('applyaers-recent-post', 80, 80, true);

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
      echo '<li><a href="'.site_url().'"><i class="fa fa-home"></i> Home</a></li> <li> / </li> <li>' .$pageNum.'- page</li>';
    else
      echo '<li><i class="fa fa-home" aria-hidden="ture"></i> Home</li>';
  }
}

/**
 * WordPress Bootstrap Pagination
 */
function applayers_pagination( $args = array() ) {

  $defaults = array(
    'range'           => 4,
    'custom_query'    => FALSE,
    'previous_string' => __( 'Previous', 'text-domain' ),
    'next_string'     => __( 'Next', 'text-domain' ),
    'before_output'   => '<div class="next_page"><ul class="page-numbers">',
    'after_output'    => '</ul></div>'
  );

  $args = wp_parse_args(
    $args,
    apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
  );

  $args['range'] = (int) $args['range'] - 1;
  if ( !$args['custom_query'] )
    $args['custom_query'] = @$GLOBALS['wp_query'];
  $count = (int) $args['custom_query']->max_num_pages;
  $page  = intval( get_query_var( 'paged' ) );
  $ceil  = ceil( $args['range'] / 2 );

  if ( $count <= 1 )
    return FALSE;

  if ( !$page )
    $page = 1;

  if ( $count > $args['range'] ) {
    if ( $page <= $args['range'] ) {
        $min = 1;
        $max = $args['range'] + 1;
    } elseif ( $page >= ($count - $ceil) ) {
        $min = $count - $args['range'];
        $max = $count;
    } elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
        $min = $page - $ceil;
        $max = $page + $ceil;
    }
  } else {
    $min = 1;
    $max = $count;
  }

  $echo = '';
  $previous = intval($page) - 1;
  $previous = esc_attr( get_pagenum_link($previous) );

  if ( $previous && (1 != $page) )
    $echo .= '<li><a class="page-numbers" href="' . $previous . '" title="' . __( 'previous', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';

  if ( !empty($min) && !empty($max) ) {
    for( $i = $min; $i <= $max; $i++ ) {
        if ($page == $i) {
            $echo .= '<li class="active"><span class="page-numbers current">' . str_pad( (int)$i, 1, '0', STR_PAD_LEFT ) . '</span></li>';
        } else {
            $echo .= sprintf( '<li><a class="page-numbers" href="%s">%2d</a></li>', esc_attr( get_pagenum_link($i) ), $i );
        }
    }
  }

  $next = intval($page) + 1;
  $next = esc_attr( get_pagenum_link($next) );
  if ($next && ($count != $page) )
    $echo .= '<li><a class="page-numbers" href="' . $next . '" title="' . __( 'next', 'text-domain') . '">' . $args['next_string'] . '</a></li>';

  if ( isset($echo) )
    echo $args['before_output'] . $echo . $args['after_output'];
}


function applayers_customize_register( $wp_customize ) {
  // social section
  $wp_customize->add_setting( 'header_social' , array(
    'default'   => __('Share Your Favorite Mobile Apps With Your Friends', 'applayers'),
    'transport' => 'refresh',
  ) );

  $wp_customize->add_section( 'social_section' , array(
      'title'      => __( 'Social section', 'applayers' ),
      'priority'   => 30,
  ) );

  $wp_customize->add_control(
    'header_social',
    array(
      'label'    => __( 'Social header in footer', 'applyaers' ),
      'section'  => 'social_section',
      'settings' => 'header_social',
      'type'     => 'text'
  ) );

  // facebook_social
  $wp_customize->add_setting( 'facebook_social' , array(
    'default'   => __('Url facebook', 'applayers'),
    'transport' => 'refresh',
  ) );

  $wp_customize->add_control(
    'facebook_social',
    array(
      'label'    => __( 'facebook url', 'applyaers' ),
      'section'  => 'social_section',
      'settings' => 'facebook_social',
      'type'     => 'text'
  ) );

  // twitter
  $wp_customize->add_setting( 'twitter_social' , array(
    'default'   => __('Url twitter', 'applayers'),
    'transport' => 'refresh',
  ) );

  $wp_customize->add_control(
    'twitter_social',
    array(
      'label'    => __( 'twitter url', 'applyaers' ),
      'section'  => 'social_section',
      'settings' => 'twitter_social',
      'type'     => 'text'
  ) );

  // linkedin
  $wp_customize->add_setting( 'linkedin' , array(
    'default'   => __('Url linkedin', 'applayers'),
    'transport' => 'refresh',
  ) );

  $wp_customize->add_control(
    'linkedin_social',
    array(
      'label'    => __( 'linkedin url', 'applyaers' ),
      'section'  => 'social_section',
      'settings' => 'linkedin_social',
      'type'     => 'text'
  ) );

  // google+
  $wp_customize->add_setting( 'googleplus_social' , array(
    'default'   => __('Url google+', 'applayers'),
    'transport' => 'refresh',
  ) );

  $wp_customize->add_control(
    'googleplus_social',
    array(
      'label'    => __( 'google+ url', 'applyaers' ),
      'section'  => 'social_section',
      'settings' => 'googleplus_social',
      'type'     => 'text'
  ) );

  // youtube
  $wp_customize->add_setting( 'youtube_social' , array(
    'default'   => __('Url youtube', 'applayers'),
    'transport' => 'refresh',
  ) );

  $wp_customize->add_control(
    'youtube_social',
    array(
      'label'    => __( 'youtube url', 'applyaers' ),
      'section'  => 'social_section',
      'settings' => 'youtube_social',
      'type'     => 'text'
  ) );

  // instagram
  $wp_customize->add_setting( 'instagram_social' , array(
    'default'   => __('Url instagram', 'applayers'),
    'transport' => 'refresh',
  ) );

  $wp_customize->add_control(
    'instagram_social',
    array(
      'label'    => __( 'instagram url', 'applyaers' ),
      'section'  => 'social_section',
      'settings' => 'instagram_social',
      'type'     => 'text'
  ) );

  // footer settigs
  $wp_customize->add_section( 'footer_settings' , array(
      'title'      => __( 'Footer settings', 'applayers' ),
      'priority'   => 30,
  ) );

  $wp_customize->add_setting( 'copy_footer' , array(
    'default'   => __('Copyright text', 'applayers'),
    'transport' => 'refresh',
  ) );

  $wp_customize->add_control(
    'copy_footer',
    array(
      'label'    => __( 'Copyright text', 'applyaers' ),
      'section'  => 'footer_settings',
      'settings' => 'copy_footer',
      'type'     => 'text'
  ) );
}
add_action( 'customize_register', 'applayers_customize_register' );

/**
 * Add a sidebar.
 */
function applayers_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'applayers' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'applayers' ),
        'before_widget' => '<div id="%1$s" class="sidebar_wrap %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="side_bar_heading"><h6>',
        'after_title'   => '</h6></div>',
    ) );
}
add_action( 'widgets_init', 'applayers_widgets_init' );


/**
 * Categories
 */
class Walker_Categories_Applayers extends Walker_Category {
  /**
   * Starts the list before the elements are added.
   *
   * @since 2.1.0
   * @access public
   *
   * @see Walker::start_lvl()
   *
   * @param string $output Used to append additional content. Passed by reference.
   * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
   * @param array  $args   Optional. An array of arguments. Will only append content if style argument
   *                       value is 'list'. See wp_list_categories(). Default empty array.
   */
  public function start_lvl( &$output, $depth = 0, $args = array() ) {
    parent::start_lvl($output, $depth, $args);
  }

  /**
   * Ends the list of after the elements are added.
   *
   * @since 2.1.0
   * @access public
   *
   * @see Walker::end_lvl()
   *
   * @param string $output Used to append additional content. Passed by reference.
   * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
   * @param array  $args   Optional. An array of arguments. Will only append content if style argument
   *                       value is 'list'. See wp_list_categories(). Default empty array.
   */
  public function end_lvl( &$output, $depth = 0, $args = array() ) {
    parent::end_lvl($output, $depth, $args);
  }

  /**
   * Starts the element output.
   *
   * @since 2.1.0
   * @access public
   *
   * @see Walker::start_el()
   *
   * @param string $output   Passed by reference. Used to append additional content.
   * @param object $category Category data object.
   * @param int    $depth    Optional. Depth of category in reference to parents. Default 0.
   * @param array  $args     Optional. An array of arguments. See wp_list_categories(). Default empty array.
   * @param int    $id       Optional. ID of the current category. Default 0.
   */
  public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
    /** This filter is documented in wp-includes/category-template.php */
    $cat_name = apply_filters(
      'list_cats',
      esc_attr( $category->name ),
      $category
    );

    // Don't generate an element if the category name is empty.
    if ( ! $cat_name ) {
      return;
    }

    $link = '<a href="' . esc_url( get_term_link( $category ) ) . '" ';
    if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
      /**
       * Filters the category description for display.
       *
       * @since 1.2.0
       *
       * @param string $description Category description.
       * @param object $category    Category object.
       */
      $link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
    }

    $link .= '><i class="fa fa-folder-open-o" aria-hidden="true"></i>';
    $link .= $cat_name;
    if ( ! empty( $args['show_count'] ) ) {
      $link .= ' <span>' . number_format_i18n( $category->count ) . '</span>';
    }
    $link .= '</a>';

    if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
      $link .= ' ';

      if ( empty( $args['feed_image'] ) ) {
        $link .= '(';
      }

      $link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

      if ( empty( $args['feed'] ) ) {
        $alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
      } else {
        $alt = ' alt="' . $args['feed'] . '"';
        $name = $args['feed'];
        $link .= empty( $args['title'] ) ? '' : $args['title'];
      }

      $link .= '>';

      if ( empty( $args['feed_image'] ) ) {
        $link .= $name;
      } else {
        $link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
      }
      $link .= '</a>';

      if ( empty( $args['feed_image'] ) ) {
        $link .= ')';
      }
    }


    if ( 'list' == $args['style'] ) {
      $output .= "\t<li";
      $css_classes = array(
        'cat-item',
        'cat-item-' . $category->term_id,
      );

      if ( ! empty( $args['current_category'] ) ) {
        // 'current_category' can be an array, so we use `get_terms()`.
        $_current_terms = get_terms( $category->taxonomy, array(
          'include' => $args['current_category'],
          'hide_empty' => false,
        ) );

        foreach ( $_current_terms as $_current_term ) {
          if ( $category->term_id == $_current_term->term_id ) {
            $css_classes[] = 'current-cat';
          } elseif ( $category->term_id == $_current_term->parent ) {
            $css_classes[] = 'current-cat-parent';
          }
          while ( $_current_term->parent ) {
            if ( $category->term_id == $_current_term->parent ) {
              $css_classes[] =  'current-cat-ancestor';
              break;
            }
            $_current_term = get_term( $_current_term->parent, $category->taxonomy );
          }
        }
      }

      /**
       * Filters the list of CSS classes to include with each category in the list.
       *
       * @since 4.2.0
       *
       * @see wp_list_categories()
       *
       * @param array  $css_classes An array of CSS classes to be applied to each list item.
       * @param object $category    Category data object.
       * @param int    $depth       Depth of page, used for padding.
       * @param array  $args        An array of wp_list_categories() arguments.
       */
      $css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

      $output .=  ' class="' . $css_classes . '"';
      $output .= ">$link\n";
    } elseif ( isset( $args['separator'] ) ) {
      $output .= "\t$link" . $args['separator'] . "\n";
    } else {
      $output .= "\t$link<br />\n";
    }
  }

  /**
   * Ends the element output, if needed.
   *
   * @since 2.1.0
   * @access public
   *
   * @see Walker::end_el()
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param object $page   Not used.
   * @param int    $depth  Optional. Depth of category. Not used.
   * @param array  $args   Optional. An array of arguments. Only uses 'list' for whether should append
   *                       to output. See wp_list_categories(). Default empty array.
   */
  public function end_el( &$output, $page, $depth = 0, $args = array() ) {
    parent::end_el($output, $depth, $args);
  }
}

function applayers_widget_categories($args) {
  $walker = new Walker_Categories_Applayers();
  $args = array_merge($args, array('walker' => $walker));

  return $args;
}
add_filter('widget_categories_args', 'applayers_widget_categories');


/**
 * Tag cloud
*/

function applayers_tag_cloud($args) {
  $args['format'] = 'list';
  $args['smallest'] = 14;
  $args['largest'] = 14;
  $args['unit'] = 'px';

  return $args;
}
add_filter('widget_tag_cloud_args','applayers_tag_cloud');