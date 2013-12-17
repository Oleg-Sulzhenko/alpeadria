<?php

// =============================== Newsletetr widget ======================================

class TFuse_newsletter extends WP_Widget {

    function TFuse_newsletter() {
        $widget_ops = array('description' => '');
        parent::WP_Widget(false, __('TFuse - Newsletter', 'tfuse'), $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $newsletter_title = empty($instance['newsletter_title']) ? __('NEWSLETTER SIGNUP:', 'tfuse') : esc_attr($instance['newsletter_title']);
        $rss = empty($instance['rss']) ? '' : esc_attr($instance['rss']);
        $custom_id = '-' . $args['widget_id'];
        ?>

        <div class="widget-container newsletter_subscription_box newsletterBox">
            <div class="inner">
                <?php if ($newsletter_title != '') { ?><h3><?php echo tfuse_qtranslate($newsletter_title); ?></h3><?php } ?>

                <div class="newsletter_subscription_messages before-text" style="margin-left: 10px">

                    <div class="newsletter_subscription_message_success">
                        <?php _e('Thank you for your subscribtion.','tfuse') ?>
                    </div>
                    <div class="newsletter_subscription_message_wrong_email">
                        <?php _e('Your email format is wrong!','tfuse') ?>
                    </div>
                    <div class="newsletter_subscription_message_failed">
                        <?php _e('Sad, but we couldn\'t add you to our mailing list ATM.','tfuse') ?>
                    </div>
                </div>

                <form action="#" method="post" class="newsletter_subscription_form">
                    <input type="text" value="<?php _e('Enter your email address', 'tfuse'); ?>" onfocus="if (this.value == '<?php _e('Enter your email address', 'tfuse'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Enter your email address', 'tfuse'); ?>';}" name="newsletter" class="newsletter_subscription_email inputField" />
                    <?php if ($rss != '') { ?>
                    <div class="rowCheckbox input_styled checklist">
                        <a href="<?php $feedb_url = tfuse_options('feedburner_url','#'); print $feedb_url; ?>" style="color: #666;"><?php _e('Subscribe to RSS', 'tfuse'); ?></a>
                    </div>
                    <?php } ?>
                    <input type="submit" value="<?php _e('SUBMIT', 'tfuse'); ?>" class="btn-submit newsletter_subscription_submit" />
                </form>

                <div class="newsletter_subscription_ajax" style="margin-left: 10px">Loading...</div>
                <div class="clear"></div>
            </div>
        </div>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['newsletter_title'] = $new_instance['newsletter_title'];
        $instance['rss'] = isset($new_instance['rss']);

        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('newsletter_title' => '', 'rss' => ''));
        $newsletter_title = esc_attr($instance['newsletter_title']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('newsletter_title'); ?>"><?php _e('Title:', 'tfuse'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('newsletter_title'); ?>" value="<?php echo $newsletter_title; ?>" class="widefat" id="<?php echo $this->get_field_id('newsletter_title'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('Activate RSS', 'tfuse'); ?>:</label>

            <input type="checkbox" <?php checked(isset($instance['rss']) ? $instance['rss'] : 0); ?> name="<?php echo $this->get_field_name('rss'); ?>" class="checkbox" id="<?php echo $this->get_field_id('rss'); ?>" />
        </p>
        <?php
    }

}

register_widget('TFuse_newsletter');
