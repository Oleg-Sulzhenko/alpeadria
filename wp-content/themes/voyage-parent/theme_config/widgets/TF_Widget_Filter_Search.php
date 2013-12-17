<?php

// =============================== Search widget ======================================

class TF_Widget_Filter_Results extends WP_Widget {

    function TF_Widget_Filter_Results() {
        $widget_ops = array('classname' => 'widget_adv_filter', 'description' => __( "TFuse - Filter Results") );
        $this->WP_Widget('filter_results', __('TFuse Filter Results'), $widget_ops);
    }

    function widget($args, $instance) {
        global $TFUSE;
        extract($args);
        if(!($TFUSE->request->isset_GET('tfseekfid'))) {
            return '';
        }
        $title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'FILTER THE RESULTS','tfuse' ) : $instance['title'], $instance, $this->id_base);
        ?>
    <!-- filter -->
    <div class="widget-container widget_adv_filter">
        <h3 class="widget-title"><?php _e($title, 'tfuse');?></h3>

        <?php TF_SEEK_HELPER::print_form('filter_search'); ?>
    </div>
    <!--/ filter -->
    <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
        $title = $instance['title'];
        ?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
    <p><?php _e('Note: "This widget appear only on seach page for holidays"','tfuse'); ?></p>
	<?php
    }
}

function TFuse_Unregister_WP_Widget_Filter_Results() {
    unregister_widget('WP_Widget_Search');
}
add_action('widgets_init','TFuse_Unregister_WP_Widget_Filter_Results');

register_widget('TF_Widget_Filter_Results');
