<?php
if ( ! function_exists( 'tfuse_comment' ) ) :
    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own tfuse_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     */
    function tfuse_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;

        switch ( $comment->comment_type ) :
            case 'pingback' :
            case 'trackback' :
                ?>
    <li class="post pingback">
        <div id="li-comment-<?php comment_ID() ?>" class="comment-body">

            <p><?php _e( 'Pingback:', 'tfuse' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'tfuse' ), '<span class="edit-link">', '</span>' ); ?></p>
            <div class="comment-entry">
                <?php comment_text() ?>
                <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>
        </div>
                    <?php
                break;
            default :
                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

                    <a name="comment-<?php comment_ID() ?>"></a>

                    <div id="li-comment-<?php comment_ID() ?>" class="comment-body">

                        <div class="comment-avatar">
                            <div class="avatar"><?php echo get_avatar( $comment, 90 ); ?></div>
                            <a href="#" class="link-author"><?php comment_author_link() ?></a>
                        </div>

                        <div class="comment-text">

                            <div class="comment-author">
                                <span class="comment-date"><?php comment_date('j M Y') ?></span>
                            </div>

                            <div class="comment-entry">
                                <?php echo $comment->comment_content; ?> <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?>
                            </div>

                            <?php if ( $comment->comment_approved == '0' ) : ?>

                            <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'tfuse' ); ?></em>
                            <br />
                            <?php endif; ?>

                        </div>
                        <!-- /.comment-head -->
                        <div class="clear"></div>
                        <div id="comment-<?php comment_ID(); ?>"></div>
                        <div class="clear"></div>

                    </div><!-- /.comment-container -->
                <?php
                break;
        endswitch;
    }
endif; // ends check for tfuse_comment()
