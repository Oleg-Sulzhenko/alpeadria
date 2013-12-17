<?php
    global $TFUSE;
    if ($TFUSE->request->isset_GET('tfseekfid')) {
        get_template_part('template', 'search');
        die();
    }
    get_header();
    global $wp_query;
    $args = array( 'post_type' => array('post', TF_SEEK_HELPER::get_post_type()) );
    $args = array_merge( $args, $wp_query->query );
    query_posts( $args );
    $sidebar_position = tfuse_sidebar_position();
?>
<div <?php tfuse_class('middle'); ?>>

    <div class="container_12">

        <?php if (!tfuse_options('disable_breadcrumbs')) tfuse_breadcrumbs(); ?>

        <?php if ($sidebar_position == 'left') : ?>
        <div class="grid_4 sidebar">
            <?php get_sidebar(); ?>
        </div><!--/ .sidebar -->
        <?php endif; ?>

        <!-- content -->
        <div <?php tfuse_class('content'); ?>>
            <div class="post-list">
                <?php if (have_posts()) : $count = 0; ?>

                <?php while (have_posts()) : the_post(); $count++; ?>

                    <?php get_template_part('listing', 'blog'); ?>

                    <?php endwhile; else: ?>

                <h5><?php _e('Sorry, no posts matched your criteria.', 'tfuse') ?></h5>



                <?php endif; ?>
            </div>

            <?php tfuse_pagination(); ?>

        </div>
        <!--/ content -->

        <?php if ($sidebar_position == 'right') : ?>
        <div class="grid_4 sidebar">
            <?php  get_sidebar(); ?>
        </div><!--/ .sidebar -->
        <?php endif; ?>

        <div class="clear"></div>


    </div>
</div>
<!--/ middle -->
<?php tfuse_header_content('after_content'); ?>
<?php if (!tfuse_options('disable_breadcrumbs')) setcookie('latest_category',get_query_var('cat'),time() + (86400 * 7)); ?>
<?php get_footer(); ?>