<?php
/**
 * The template for displaying posts on archive pages.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since Voyage 1.0
 */
  global $more;
    $more = apply_filters('tfuse_more_tag',0);
?>

    <div class="post-item">
        <div class="post-title"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>
        <?php tfuse_media();?>
        <div class="post-short">
            <div class="post-meta-top"><span class="meta-date"><?php echo get_the_date('D, M j, y'); ?></span> <?php _e('Posted by', 'tfuse'); ?>: <a href="<?php the_permalink(); ?>" class="author"><?php the_author();?></a></div>
            <div class="post-descr">
                <p><?php if ( tfuse_options('post_content') == 'content' ) the_content(''); else the_excerpt(); ?></p>
            </div>
            <div class="post-meta-bot"><a href="<?php the_permalink(); ?>" class="link-more"><?php _e('Continue reading', 'tfuse'); ?> >></a></div>
        </div>
        <div class="clear"></div>
    </div>
