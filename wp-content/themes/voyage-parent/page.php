<?php
    global $wp_query, $is_tf_blog_page;
    get_header();
    if ($is_tf_blog_page) die();
    $sidebar_position = tfuse_sidebar_position();
?>

<div <?php tfuse_class('middle'); ?>>

    <div class="container_12">

        <?php if (!tfuse_options('disable_breadcrumbs', false)) tfuse_breadcrumbs(); ?>

        <!-- content -->
        <div <?php tfuse_class('content'); ?>>

            <div class="post-detail">

                <?php tfuse_custom_title(); ?>

                <div class="entry">

                    <?php while ( have_posts() ) : the_post(); ?>

                            <?php the_content(); ?>

                    <?php endwhile; // end of the loop. ?>
                   <div class="clear"></div>
                </div><!--/ .entry -->
                <?php tfuse_comments(); ?>

            </div><!--/ .post-item -->

            <?php tfuse_shortcode_content('bottom'); ?>

        </div><!--/ content -->

        <?php if ($sidebar_position == 'left' || $sidebar_position == 'right') : ?>
        <div class="sidebar">
            <?php get_sidebar(); ?>
        </div><!--/ .sidebar -->
        <?php endif; ?>

        <div class="clear"></div>
    </div>
</div>
<!--/ middle -->
<?php tfuse_header_content('after_content'); ?>
<?php get_footer(); ?>