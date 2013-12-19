<?php get_header(); ?>
<?php $sidebar_position = tfuse_sidebar_position(); ?>
<?php global $post; ?>
<div <?php tfuse_class('middle'); ?>>

    <div class="container_12">

        <?php if (!tfuse_options('disable_breadcrumbs')) tfuse_breadcrumbs(); ?>

        <?php if (TF_SEEK_HELPER::get_post_type() === get_post_type()) : ?>
            <div class="title">
                <?php tfuse_custom_title(); ?>
                <?php
                $holiday_locations = wp_get_object_terms($post->ID, TF_SEEK_HELPER::get_post_type() . '_locations', array('orderby' => 'term_order', 'order' => 'ASC'));
                if (!empty($holiday_locations)) {
                    if (!is_wp_error($holiday_locations)) {
                        $term_link = get_term_link($holiday_locations[sizeof($holiday_locations) - 1], TF_SEEK_HELPER::get_post_type() . '_locations');
                        ?>
                        <span class="title_right count">
                            <a href="<?php
                            if (!is_wp_error($term_link))
                                echo $term_link;
                            else
                                echo 'javascript: return false;';
                            ?>" class="link-map">
                               <?php
                               print $holiday_locations[sizeof($holiday_locations) - 1]->name;
                               if (isset($holiday_locations[sizeof($holiday_locations) - 2]))
                                   echo __(', ', 'tfuse') . $holiday_locations[sizeof($holiday_locations) - 2]->name;
                               ?>
                            </a>
                        </span>
                        <?php
                    }
                }
                ?>
            </div>
            <?php get_template_part('photo', 'gallery'); ?>
<?php endif; ?>
        <div <?php tfuse_class('content'); ?>>

            <?php while (have_posts()) : the_post(); ?>

                <?php
                if (TF_SEEK_HELPER::get_post_type() === get_post_type()) :
                    get_template_part('content', 'holiday');
                else :
                    get_template_part('content', 'single');
                    get_template_part('content', 'author');
                endif;
                ?>

                <?php tfuse_comments(); ?>

<?php endwhile; // end of the loop.   ?>


        </div>
        <!--/ content -->



            <?php if ($sidebar_position == 'left' || $sidebar_position == 'right') : ?>
            <div class="sidebar">
                <?php echo do_shortcode('[tfuse_reservationform tf_rf_formid="28"]'); ?>
            <?php // get_sidebar();   ?>
            </div><!--/ .sidebar -->
<?php endif; ?>

        <div class="clear"></div>
    </div>
</div>
<!--/ middle -->
<?php tfuse_header_content('after_content'); ?>
<?php get_footer(); ?>