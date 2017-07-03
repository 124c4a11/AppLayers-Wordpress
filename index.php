<?php get_header(); ?>

<section class="error_top_bg secondary-bg">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="top_banner">
          <div class="category_m">
            <h2> App News</h2>

            <div class="breadcrumb_m">
              <ul class="breadcrumbs_m" id="breadcrumbs_m">
                <li><a href="#"><i class="fa fa-home" aria-hidden="true"></i> Home </a></li>
                <li> / </li>
                <li><a href="#">  Blog</a></li>
              </ul>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<section class="post_blog_bg primary-bg">
  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <div class="col-md-8">

          <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
              <article class="blog_post">
                <h4> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h4>

                <div class="blog_category">
                  <ul>
                    <li><?php the_category(', '); ?></li>
                  </ul>
                </div>

                <div class="blog_text">
                  <ul>
                    <li> | </li>
                    <li> Post By : <?php the_author_posts_link(); ?></li>
                    <li> | </li>
                    <li> On : <?php the_time('j F Y'); ?> </li>
                  </ul>
                </div>

                <div class="blog_post_img">
                  <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                </div>

                <?php the_excerpt(); ?>

                <a href="<?php the_permalink(); ?>"> Continue reading <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
              </article>
            <?php endwhile ?>
          <?php endif; ?>

          <div class="next_page">
            <ul class="page-numbers">
              <li><span class="page-numbers current">1</span></li>
              <li><a href="#" class="page-numbers">2</a></li>
              <li><a href="#" class="page-numbers">3</a></li>
              <li><a href="#" class="page-numbers">4</a></li>
              <li><a href="#" class="next page-numbers">Next</a></li>
            </ul>
          </div>

        </div>

        <!--sibar-->
        <?php get_sidebar(); ?>
      </div>
    </div>
  </div>
</section>
<?php get_footer(); ?>