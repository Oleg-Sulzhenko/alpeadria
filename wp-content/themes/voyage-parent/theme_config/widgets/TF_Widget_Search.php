<?php

// =============================== Search widget ======================================

class TF_Widget_Search extends WP_Widget {

	function TF_Widget_Search() {
            $widget_ops = array('classname' => 'widget_search', 'description' => __( "A search form for your site") );
            $this->WP_Widget('search', __('TFuse Search'), $widget_ops);
	}

	function widget($args, $instance) { 
            extract($args);
            $title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Search widget:','tfuse' ) : $instance['title'], $instance, $this->id_base);
            $template = empty( $instance['template'] ) ? '' : $instance['template'];
            //locate_template('searchform', true, false);
        ?>
        <!-- search widget -->
        <div class="widget-container widget_search">
            <div class="inner">
            <h3><?php  echo $title; ?></h3>
            <form method="get" id="searchform" action="<?php echo home_url( '/' ) ?>">
                <div>
                    <label class="screen-reader-text" for="s"><?php _e('Search for','tfuse'); ?>:</label>
                    <input class="inputField" name="s" id="s" value="<?php echo tfuse_options('search_box_text'); ?>" onfocus="if (this.value == '<?php echo tfuse_options('search_box_text'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo tfuse_options('search_box_text'); ?>';}" type="text">
                    <input id="searchsubmit" class="btn-submit" value="<?php _e('Submit','tfuse');?>" type="submit">
                </div>
            </form>
            </div>
        </div>
        <!--/ search widget -->
         <?php
        }

	function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
            $instance['title'] = $new_instance['title'];
            $instance['template'] = strip_tags($new_instance['template']);
            return $instance;
	}

	function form( $instance ) {
            $instance = wp_parse_args( (array) $instance, array(  'template' => 'box_white',) );
            $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
            $title = $instance['title'];
            $template = esc_attr( $instance['template'] );
?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
<?php
	}
}

function TFuse_Unregister_WP_Widget_Search() {
	unregister_widget('WP_Widget_Search');
}
add_action('widgets_init','TFuse_Unregister_WP_Widget_Search');

register_widget('TF_Widget_Search');
