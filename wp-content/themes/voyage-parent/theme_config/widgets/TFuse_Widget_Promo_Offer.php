<?php

// =============================== Popular post widget ======================================

class TFuse_Promo_Offer extends WP_Widget {

    var $defaults = array(
        'title' => '',
        'promo' => false
    );

    function TFuse_Promo_Offer() {
        $widget_ops = array('description' => '' );
        parent::WP_Widget(false, __('TFuse - Promo Offer', 'tfuse'),$widget_ops);
    }

    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Promo Offer:') : $instance['title'], $instance, $this->id_base);
        $id = intval($instance['promo']['select_value']);
        if(!$id) return '';
        $posts = new WP_Query(array(
            'posts_per_page' => 1,
            'post_type'     => TF_SEEK_HELPER::get_post_type(),
            'p'       => $id
        ));
        $offer = $posts->posts;
        if(!isset($offer[0])) return '';
        $offer = $offer[0];
        if(is_wp_error($offer)) return '';
        $price = TF_SEEK_HELPER::get_post_option('property_price', false, $id);
    ?>
    <!-- widget_products -->
    <div class="widget-container widget_products">
        <div class="inner">
            <?php echo '<h3>' . $title .'</h3>'; ?>
            <div class="prod_item">
                <div class="prod_image">
                    <a href="<?php echo get_permalink($offer); ?>">
                        <?php
                            $image = new TF_GET_IMAGE();
                            $img =  $image->width(140)->height(98)->src(tfuse_get_holiday_thumbnail($id))->get_img();
                            echo $img;
                        ?>
                    </a>
                </div>
                <span class="price_box"><ins><?php if ($price) echo TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'); ?></ins><strong><?php echo ($price) ? $price : '-'; ?></strong></span>
                <div class="prod_title">
                    <a href="<?php echo get_permalink($offer); ?>"><strong><?php echo $offer->post_title; ?></strong></a><br>
                    <span>
                        <a href="<?php echo get_permalink($offer); ?>">
                            <?php
                            $holiday_locations = wp_get_object_terms($id, TF_SEEK_HELPER::get_post_type() . '_locations', array('orderby' => 'term_order', 'order' => 'ASC'));
                            if(!empty($holiday_locations)){
                                if(!is_wp_error( $holiday_locations )){
                                    print $holiday_locations[sizeof($holiday_locations)-1]->name;
                                    if(isset($holiday_locations[sizeof($holiday_locations)-2])) echo __(',', 'tfuse') . $holiday_locations[sizeof($holiday_locations)-2]->name;
                                }
                            }
                            ?>
                        </a>
                    </span>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!--/ widget_products -->
    <?php
    }

   function update($new_instance, $old_instance) {
       $instance['title'] = $new_instance['title'];
       $instance['font'] = isset( $new_instance['promo'] ['select_value'] )? strip_tags( $new_instance['promo'] ['select_value'] ) :  $this->defaults[ 'promo' ];
       return $new_instance;
   }

   function form($instance) {
        $instance = wp_parse_args( (array) $instance, array(  'title' => '', 'promo' => false) );
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $promo = isset( $instance['promo']['select_value'] ) ? $instance['promo']['select_value'] : false;
        ?>
       <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
       </p>

       <p>
           <label for="<?php echo $this->get_field_id('promo'); ?>"><?php _e('Promo', 'tfuse'); ?>:</label>
           <select  class="widefat" id="<?php echo $this->get_field_id('promo'); ?>"  name="<?php echo $this->get_field_name('promo'); ?>[select_value]">
              <?php
                    $args = array(
                        'posts_per_page'    => 200,
                        'post_type'         =>TF_SEEK_HELPER::get_post_type()
                    );
                    $query = new WP_Query($args) ;
                    if(!is_wp_error($query) ) :
                        while($query->have_posts()):
                            $query->next_post();
                            $selected = ($promo == $query->post->ID) ? ' selected="selected" ' : '';
                            echo '<option' . $selected . ' value="' . $query->post->ID . '">' . $query->post->post_title . '</option>';
                        endwhile;
                    endif;
              ?>
           </select>
       </p>

    <?php
    }
} 
register_widget('TFuse_Promo_Offer');
