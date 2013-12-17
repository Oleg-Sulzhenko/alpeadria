<?php
class TF_Widget_Contact extends WP_Widget
{

    function TF_Widget_Contact()
    {
        $widget_ops = array('classname' => 'widget_contact', 'description' => __( 'Add Contact in Sidebar') );
        $this->WP_Widget('contact', __('TFuse Contact Widgets'), $widget_ops);
    }

    function widget( $args, $instance )
    {
        extract($args);
        if(strpos($args['id'],'footer') === 0) $isFooter = true; else $isFooter = false;

        $template_directory = get_template_directory_uri();

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        $before_widget = '<div class="widget-container widget_contact">';
        $after_widget = '</div>';

        echo $before_widget;
        ?>
            <?php if($isFooter) : ?> <h3 class="widget-title"><?php echo $title; ?></h3> <?php endif; ?>
            <div class="inner">
                <?php if($isFooter) TF_Widget_Contact::socialContacts(); ?>
                <div class="contact-address">
                    <?php if (!empty($title) && (!$isFooter)) : ?><div class="name"><strong><?php echo $title;?></strong></div><?php endif; ?>
                    <?php if (!empty($instance['adress']) && (!$isFooter)) : ?><div class="address"><?php echo $instance['adress']; ?></div><?php endif; ?>
                    <?php if (!empty($instance['phone'])) : ?><div class="phone"><em><?php _e('Phone', 'tfuse'); ?>:</em><span><?php echo $instance['phone']; ?></span></div><?php endif; ?>
                    <?php if (!empty($instance['fax'])) : ?><div class="fax"><em><?php _e('Fax', 'tfuse'); ?>:</em><span><?php echo $instance['fax']; ?></span></div><?php endif; ?>
                    <?php if (!empty($instance['email'])) : ?><div class="mail"><em><?php _e('Email', 'tfuse'); ?>:</em><?php echo $instance['email']; ?></div><?php endif; ?>
                </div>
               <?php if (!$isFooter) TF_Widget_Contact::socialContacts(); ?>
            </div>
        <?php
        echo $after_widget;
    }

    private static function socialContacts()
    {   ?>
            <?php if ((tfuse_options('skype')) || (tfuse_options('twitter')) || (tfuse_options('facebook'))) : ?>
                <div class="contact-social">
                    <?php if (tfuse_options('skype')) : ?>
                    <div><strong><?php _e('Call us on', 'tfuse'); ?>:</strong> <br>
                        <a href="skype:<?php echo tfuse_options('skype'); ?>?call" class="btn btn_skype"></a></div>
                    <?php endif; ?>
                    <?php if (tfuse_options('twitter')) : ?>
                    <div><strong><?php _e('Follow on','tfuse'); ?>:</strong> <br>
                        <a href="<?php echo tfuse_options('twitter'); ?>" class="btn btn_twitter"></a></div>
                    <?php endif; ?>
                    <?php if (tfuse_options('facebook')) : ?>
                    <div><strong><?php _e('Join us on', 'tfuse'); ?>:</strong> <br>
                        <a href="<?php echo tfuse_options('facebook'); ?>" class="btn btn_fb"></a></div>
                    <?php endif; ?>
                    <div class="clear"></div>
                </div>
            <?php endif; ?>
        <?php
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $new_instance = wp_parse_args( (array) $new_instance, array( 'title'=>'', 'email' => '', 'adress' => '', 'phone' => '', 'fax' => '') );

        $instance['title']      = $new_instance['title'];
        $instance['adress']      = $new_instance['adress'];
        $instance['phone']      = $new_instance['phone'];
        $instance['fax']      = $new_instance['fax'];
        $instance['email']      = $new_instance['email'];

        return $instance;
    }

    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, array( 'title'=>'', 'email' => '', 'adress' => '', 'phone' => '', 'fax' => '') );
        $title = $instance['title'];
?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label><br/>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('adress'); ?>"><?php _e('Adress:'); _e(' (Will not be appear in footer.)', 'tfuse'); ?></label><br/>
        <input class="widefat " id="<?php echo $this->get_field_id('adress'); ?>" name="<?php echo $this->get_field_name('adress'); ?>" type="text" value="<?php echo esc_attr($instance['adress']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone:'); ?></label><br/>
       <input class="widefat " id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo esc_attr($instance['phone']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('fax'); ?>"><?php _e('Fax:'); ?></label><br/>
        <input class="widefat " id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" type="text" value="<?php echo esc_attr($instance['fax']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:'); ?></label><br/>
        <input class="widefat " id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo  esc_attr($instance['email']); ?>"  />
    </p>
    <?php
    }
}
register_widget('TF_Widget_Contact');
