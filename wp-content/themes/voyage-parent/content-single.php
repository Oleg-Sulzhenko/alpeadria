<?php
/**
 * The template for displaying content in the single.php template.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since Voyage 1.0
 */
$post_meta_top = (!tfuse_page_options('disable_published_date') || !tfuse_page_options('disable_post_meta')) ? true : false;
?>
<div class="post-detail">
    <h1<?php echo (!$post_meta_top) ? ' style="margin-bottom:0;"' : ''; ?>><?php the_title(); ?><?php ?></h1>
    <?php if ($post_meta_top) : ?>
        <div class="post-meta-top">
            <?php if ( !tfuse_page_options('disable_published_date') ) : ?>
                <span class="meta-date"><?php echo get_the_date('D, M, j, y'); ?></span>
            <?php endif; ?>
            <?php if ( !tfuse_page_options('disable_post_meta') ) : ?>
                <?php _e('Posted by ','tfuse'); ?>: <span class="author"><?php echo get_the_author() ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="entry">
        <?php tfuse_media(); ?>
        <?php the_content(); ?>z
    </div><!--/ .entry -->
    <?php if ( !tfuse_page_options('disable_share_buttons') ) : ?>
    <!-- post share -->
    <div class="block_hr post-share">
        <div class="inner">
            <p><?php _e('Share', 'tfuse'); ?> "<strong><?php the_title(); ?></strong>" <?php _e('via', 'tfuse'); ?>:</p>
            <p> <a href="http://twitter.com/share?url=<?php the_permalink();?>&amp;text=<?php the_title();?>&amp;count=horiztonal" data-count="horiztonal" target="_blank" class="btn-share"><img src="<?php echo get_template_directory_uri(); ?>/images/share_twitter.png" width="79" height="25" alt=""></a>
                <a href="http://www.facebook.com/sharer.php?u=<?php echo encodeURIComponent(get_permalink());?>%2F&t=<?php echo encodeURIComponent(get_the_title());?>" class="btn-share"><img src="<?php echo get_template_directory_uri(); ?>/images/share_facebook.png" width="88" height="25" alt=""></a>
                <a href="https://plus.google.com/share?url=<?php  the_permalink(); ?>" target="_blank" class="btn-share"><img src="<?php echo get_template_directory_uri(); ?>/images/share_google.png" width="67" height="25" alt=""></a>
            </p>
        </div>
    </div>
    <!--/ postshare -->
    <?php endif; ?>
</div><!--/ post details -->
