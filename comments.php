<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage AppLayers
 * @since Applayers 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
  return;
?>

<div id="comments" class="comments-area">

  <?php if ( have_comments() ) : ?>
    <h3 class="comments-title">
      <?php
        printf( _nx( 'One thought on "%2$s"', '%1$s thoughts on "%2$s"', get_comments_number(), 'comments title', 'twentythirteen' ),
          number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
      ?>
    </h3>

    <ol class="comment-list">
      <?php
        wp_list_comments( array(
          'style'       => 'ol',
          'short_ping'  => true,
          'avatar_size' => 74,
        ) );
      ?>
    </ol><!-- .comment-list -->

    <?php
      // Are there comments to navigate through?
      if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
    ?>
    <nav class="navigation comment-navigation" role="navigation">
      <h1 class="screen-reader-text section-heading"><?php _e( 'Comment navigation', 'twentythirteen' ); ?></h1>
      <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentythirteen' ) ); ?></div>
      <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentythirteen' ) ); ?></div>
    </nav><!-- .comment-navigation -->
    <?php endif; // Check for comment navigation ?>

    <?php if ( ! comments_open() && get_comments_number() ) : ?>
    <p class="no-comments"><?php _e( 'Comments are closed.' , 'twentythirteen' ); ?></p>
    <?php endif; ?>

  <?php endif; // have_comments() ?>

  <div class="contact_form">
    <?php
      $comments_args = array(
        'fields' => array(
          'author' => '<div class="comment-form-author form-group">' . '<label for="author">' . esc_html__( 'Full Name : ', 'applayers' ) . ( $req ? '<span class="required">*</span>' : '' )  . ' </label>' .
          '<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' /></div>',

          'email'  => '<div class="comment-form-email form-group"><label for="email">' . esc_html__( 'Email Addres : ', 'applayers' ) . ( $req ? '<span class="required">*</span>' : '' ) . ' </label>' . '<input id="email" class="form-control" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"' . $aria_req . ' /></div>'
        ),

        'class_submit' => 'btn btn-default',
        'submit_field' => '<div class="section_sub_btn">%1$s %2$s</div>',
        'label_submit' => esc_html__('Send Message', 'applayout'),

        'comment_field' => '<div class="comment-form-comment form-group"><label for="comment">' . esc_html_x( 'Messsage : ', 'applayers' ). ( $req ? '<span class="required">*</span>' : '' ) . '</label> <textarea id="comment" class="form-textarea" name="comment" rows="3" aria-required="true"></textarea></div>'
      );

      comment_form($comments_args);
    ?>
  </div>

</div><!-- #comments -->