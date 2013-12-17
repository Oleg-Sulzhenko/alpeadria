<?php get_header(); ?>

<?php $sidebar_position = tfuse_sidebar_position(); ?>

<div <?php tfuse_class('middle'); ?>>

    <div class="container_12">

        <?php if (!tfuse_options('disable_breadcrumbs')) tfuse_breadcrumbs(); ?>

        <!-- content -->
        <div <?php tfuse_class('content'); ?>>

            <div class="post-detail">

                <h1><?php _e('Page not found', 'tfuse') ?></h1>

                <div class="entry">

                    <p><?php _e('The page you were looking for doesn&rsquo;t seem to exist', 'tfuse') ?>.</p>

                </div><!--/ .entry -->
                <?php tfuse_comments(); ?>

            </div><!--/ .post-item -->

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