<form role="search" method="get" class="search-form" action="<?php esc_url( home_url( '/' ) ); ?>">
  <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'applayers' ) ?>" value="<?php get_search_query() ?>" name="s" />

  <span class="search-submit-wrap">
    <button type="submit" class="search-submit" value="" /><i class="search-submit-icon fa fa-search"></i></button>
  </span>
</form>