<?php

// =============================== Flickr widget ======================================

class TFuse_Holiday_Reservation extends WP_Widget {

	function TFuse_Holiday_Reservation() {
            $widget_ops = array('description' => '' );
            parent::WP_Widget(false, __('TFuse - Holiday Reservation' , 'tfuse'),$widget_ops);
	}

	function widget($args, $instance) {
            global $wp_query;
            if(get_post_type($wp_query->queried_object->ID) !== TF_SEEK_HELPER::get_post_type()) return;
            extract( $args );
            $title = esc_attr($instance['title']);
            $subtitle = esc_attr($instance['subtitle']);
            $field_1 = esc_attr($instance['field_1']);
            $field_2 = esc_attr($instance['field_2']);
            $field_3 = esc_attr($instance['field_3']);
            $submit_btn = esc_attr($instance['submit_btn']);
            $textarea = esc_attr($instance['textarea']);
            wp_enqueue_script( 'jquery-ui.multidatespicker' );
            wp_enqueue_script( 'holiday_reservation' );
            if($subtitle) {
                $price = TF_SEEK_HELPER::get_post_option('property_price', '-', $wp_query->queried_object->ID);
                $subtitle = str_replace('[price]', $price, $subtitle);
            }
            ?>
    <!-- filter -->
    <div class="widget-container widget_item_info widget_holiday_reservation" id="package">
        <?php if($title) : ?><h3 class="widget-title"><?php echo $title; ?></h3> <?php endif; ?>
        <?php if($subtitle) : echo html_entity_decode($subtitle); endif; ?>

        <form action="#" method="get" class="form_white" data-rel="<?php echo $wp_query->queried_object->ID; ?>">

            <div class="row rowCalendar">
                <input type="text" name="date_departure" class="inputField" value="" id="date_departure">
                <p>
                    <span class="date_available"><?php _e('available departure dates', 'tfuse'); ?></span><br>
                    <span class="date_selected"><?php _e('your selected date', 'tfuse'); ?></span>
                </p>
            </div>

            <div class="row rowSelect" style="z-index:2">
                <label class="label_title" for="reservation_field_1"><?php echo ($field_1) ? $field_1 : __('E-mail:', 'tfuse'); ?></label>
                <input type="text" name="reservation_field_1" class="inputField" id="reservation_field_1">
            </div>

            <?php if($field_2) : ?>
            <div class="row rowSelect" style="z-index:1">
                <label class="label_title" for="reservation_field_2"><?php echo $field_2; ?></label>
                <input type="text" name="reservation_field_2" class="inputField" id="reservation_field_2">
            </div>
            <?php endif; ?>

            <?php if($field_3) : ?>
                <div class="row rowSelect" style="z-index:1">
                    <label class="label_title" for="reservation_field_3"><?php echo $field_3; ?></label>
                    <input type="text" name="reservation_field_3" class="inputField" id="reservation_field_3">
                </div>
            <?php endif; ?>
            <?php if($textarea) : ?>
                <div class="row rowSelect" style="z-index:1">
                    <label class="label_title" for="reservation_textarea"><?php echo $textarea ?></label>
                    <textarea rows="6" class="inputField" id="reservation_textarea"></textarea>
                </div>
            <?php endif; ?>
            <div class="row rowSubmit">
                <label style="color: #23ad14; display: none;" class="holiday_reservation_success"><?php _e('Your reservation has been sent.','tfuse'); ?></label>
                <label style="color: #ff0000; display: none;" class="holiday_reservation_error"><?php _e('Oops something went wrong.','tfuse'); ?></label><br />
                <label style="color: #ff0000; display: none;" class="holiday_reservation_error"><?php _e('Please try again later','tfuse'); ?></label>
                <input type="submit" value="<?php echo $submit_btn; ?>" class="btn-submit">
            </div>
        </form>
        <?php
            $dates = TF_SEEK_HELPER::get_post_option('property_availability_from', '/', $wp_query->queried_object->ID);
            $dates = explode('/', $dates);
            $start = $dates[0];
            $end = $dates[1];
        ?>
        <script>
            // <![CDATA[
            jQuery(document).ready(function($) {
                <?php if ($start) { ?> var date_1 = new Date('<?php echo date("F d, Y",strtotime($start)); ?>'); <?php } else { ?> var date_1 = new Date(); <?php } ?>
                <?php if ($end) { ?> var date_2 = new Date('<?php echo date("F d, Y",strtotime($end)); ?>'); <?php } else { ?> var date_2 = '+1y'; <?php } ?>
                function assignCalendar(id){
                    jQuery('<div class="calendar" />')
                            .insertAfter( jQuery(id) )
                            .multiDatesPicker({
                                //addDates: activeDates,
                                //dateFormat: 'yy-mm-dd',
                                minDate: date_1,
                                maxDate: date_2,
                                //altField: id,
                                //firstDay: 1,
                                showOtherMonths: true
                            }).prev().hide();
                }

                assignCalendar('#date_departure');
            });
            // ]]>
        </script>


    </div>
    <!--/ filter -->
	   <?php
   }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['subtitle'] = $new_instance['subtitle'];
        $instance['field_1'] = $new_instance['field_1'];
        $instance['field_2'] = $new_instance['field_2'];
        $instance['field_3'] = $new_instance['field_3'];
        $instance['textarea'] = $new_instance['textarea'];
        $instance['submit_btn'] = $new_instance['submit_btn'];

        return $new_instance;
    }
   function form($instance) {
       $instance = wp_parse_args( (array) $instance, array(  'title' => '', 'field_1' => '', 'field_2' => '', 'field_3' => '',  'textarea' => '', 'submit_btn' => __('BOOK NOW', 'tfuse') ));
       $title = esc_attr($instance['title']);
       $subtitle = esc_attr($instance['subtitle']);
       $field_1 = esc_attr($instance['field_1']);
       $field_2 = esc_attr($instance['field_2']);
       $field_3 = esc_attr($instance['field_3']);
       $textarea = esc_attr($instance['textarea']);
       $sumbit_btn = esc_attr($instance['submit_btn']);
       ?>
       <p>
           <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?></label>
           <input type="text" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
       <p>
           <label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Subtitle:','tfuse'); ?></label>
           <input type="text" name="<?php echo $this->get_field_name('subtitle'); ?>" value="<?php echo $subtitle; ?>" class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" />
       </p>
       <p>
           <label for="<?php echo $this->get_field_id('field_1'); ?>"><?php _e('E-mail','tfuse'); ?>:*</label>
           <input type="text" name="<?php echo $this->get_field_name('field_1'); ?>" value="<?php echo $field_1; ?>" class="widefat" id="<?php echo $this->get_field_id('field_1'); ?>" />
       </p>
       <p>
           <label for="<?php echo $this->get_field_id('field_2'); ?>"><?php _e('Text 2','tfuse'); ?>:</label>
           <input type="text" name="<?php echo $this->get_field_name('field_2'); ?>" value="<?php echo $field_2; ?>" class="widefat" id="<?php echo $this->get_field_id('field_2'); ?>" />
       </p>
       <p>
           <label for="<?php echo $this->get_field_id('field_3'); ?>"><?php _e('Text 3','tfuse'); ?>:</label>
           <input type="text" name="<?php echo $this->get_field_name('field_3'); ?>" value="<?php echo $field_3; ?>" class="widefat" id="<?php echo $this->get_field_id('field_3'); ?>" />
       </p>
       <p>
           <label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Textarea','tfuse'); ?>:</label>
           <input type="text" name="<?php echo $this->get_field_name('textarea'); ?>" value="<?php echo $textarea; ?>" class="widefat" id="<?php echo $this->get_field_id('textarea'); ?>" />
       </p>
       <p>
           <label for="<?php echo $this->get_field_id('submit_btn'); ?>"><?php _e('*Submit button text','tfuse'); ?>:</label>
           <input type="text" name="<?php echo $this->get_field_name('submit_btn'); ?>" value="<?php echo $sumbit_btn; ?>" class="widefat" id="<?php echo $this->get_field_id('submit_btn'); ?>" />
       </p>
   <?php
	}
}
register_widget('TFuse_Holiday_Reservation');
