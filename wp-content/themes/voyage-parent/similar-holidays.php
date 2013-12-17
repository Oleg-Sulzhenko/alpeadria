<?php
    global $post;

    $tags   = get_the_terms($post->ID,TF_SEEK_HELPER::get_post_type() . '_tag');
    $tag_ids = array();
    if($tags && sizeof($tags) && (!is_wp_error($tags)))  foreach($tags as $tag) $tag_ids[] = intval($tag->term_id);

    function tfuse_get_similar_holidays_by_tag($tag_ids = array(), $operator = 'IN', $max_number = 3, $exclude = array())
    {
        $args = array(
            'post_type'         => TF_SEEK_HELPER::get_post_type(),
            'post_status'       => 'publish',
            'posts_per_page'    => $max_number,
            'post__not_in'      => $exclude,
            'tax_query' => array(
                array(
                    'taxonomy' => TF_SEEK_HELPER::get_post_type() . '_tag',
                    'field' => 'id',
                    'terms' => $tag_ids,
                    'operator'  =>$operator
                )
            ),
        );
        $the_query =  new WP_Query( $args );
        $holidays = $the_query->get_posts();
        return $holidays;
    }

    function tfuse_get_random_holidays($max_number = 3,$exclude = array())
    {
        if(!$max_number) return array();
        $args = array(
            'post_type'         => TF_SEEK_HELPER::get_post_type(),
            'post_status'       => 'publish',
            'posts_per_page'    => $max_number,
            'post__not_in'      => $exclude,
        );
        $the_query =  new WP_Query( $args );
        $holidays = $the_query->get_posts();
        return $holidays;
    }

    $holidays = array();
    $selected_ids = array($post->ID);
    if(sizeof($tag_ids)) :
        $holidays = array_merge($holidays, tfuse_get_similar_holidays_by_tag($tag_ids, 'AND', 3, array($post->ID)));
        foreach($holidays as $holiday) $selected_ids[] = $holiday->ID;
        $holidays = array_merge($holidays, tfuse_get_similar_holidays_by_tag($tag_ids, 'IN', 4-sizeof($selected_ids), $selected_ids));
    endif;

    $selected_ids = array($post->ID);
    foreach($holidays as $holiday) $selected_ids[] = $holiday->ID;
    $holidays = array_merge($holidays, tfuse_get_random_holidays(4-sizeof($selected_ids),$selected_ids));
    if(sizeof($holidays) == 3) :
?>
<!-- after content -->
<div class="after_content wide">
    <div class="after_inner">
        <div class="container_12">

            <!--# widgets area, col 1 -->
            <div class="widgetarea widget_col_1">

                <!-- widget_products -->
                <div class="widget-container widget_products">
                    <div class="inner">
                        <h3><?php _e('OTHER SIMILAR ', 'tfuse'); print mb_strtoupper(TF_SEEK_HELPER::get_option('seek_property_name_plural','Holidays'), 'UTF-8'); _e(':', 'tfuse'); ?></h3>

                            <?php foreach ($holidays as $holiday) : ?>
                            <div class="prod_item">
                                <div class="prod_image">
                                    <a href="<?php echo get_permalink($holiday->ID); ?>">
                                        <?php
                                            $image = new TF_GET_IMAGE();
                                            print $image->width(140)->height(98)->src(tfuse_get_holiday_thumbnail($holiday->ID))->get_img();
                                        ?>
                                    </a>
                                </div>
                                <span class="price_box"><ins><?php print (TF_SEEK_HELPER::get_post_option('property_price', false, $holiday->ID)) ? TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') : '' ; ?></ins><strong><?php print TF_SEEK_HELPER::get_post_option('property_price', '-', $holiday->ID); ?></strong></span>
                                <div class="prod_title">
                                    <a href="<?php echo get_permalink($holiday->ID); ?>"><strong><?php print tfuse_custom_title($holiday->ID, true); ?></strong></a><br>
                                    <span>
                                        <a href="<?php echo get_permalink($holiday->ID); ?>">
                                            <?php
                                                $holiday_locations = wp_get_object_terms($holiday->ID, TF_SEEK_HELPER::get_post_type() . '_locations', array('orderby' => 'term_order', 'order' => 'ASC'));
                                                if(!empty($holiday_locations)){
                                                    if(!is_wp_error( $holiday_locations )){
                                                        print $holiday_locations[sizeof($holiday_locations)-1]->name;
                                                        if(isset($holiday_locations[sizeof($holiday_locations)-2])) echo __(', ', 'tfuse') . $holiday_locations[sizeof($holiday_locations)-2]->name;
                                                    }
                                                }
                                            ?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>

                        <div class="clear"></div>
                    </div>
                </div>
                <!--/ widget_products -->

            </div>
            <!--/ widgets area, col 1 -->

            <div class="clear"></div>
        </div>
    </div>
</div>
<!--/ after content -->
<?php
    endif;
?>