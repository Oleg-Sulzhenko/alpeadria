<?php
/*---------------------------------------------------------------------------------*/
/* Twitter widget */
/*---------------------------------------------------------------------------------*/
class Tfuse_Twitter extends WP_Widget {

   function Tfuse_Twitter() {
	   $widget_ops = array('description' => __('Add your Twitter feed to your sidebar.', 'tfuse') );
       parent::WP_Widget(false, __('Tfuse - Twitter Stream', 'tfuse'),$widget_ops);
   }

   function widget($args, $instance) {
    extract( $args );

    $title = ( !empty($instance['title']) ) ? $instance['title'] : '';
    $limit = ( !empty($instance['limit']) ) ? $instance['limit'] : 5;
    $username = ( !empty($instance['username']) ) ? $instance['username'] : 'themefuse';

    $unique_id = $args['widget_id'].rand(0,100);
    $before_widget = '<!-- widget_twitter --><div class="widget-container widget_twitter">';
    if ($args['id'] == 'after-content-1' || $args['id'] == 'after-content-1' || $args['id'] == 'after-content-3') $after_widget = '<br /><a href="' . tfuse_options('twitter', '#') . '" class="fallow">' . __('Follow us', 'tfuse') . '</a></div><!--/ widget_twitter -->';
       else $after_widget = '</div><!--/ widget_twitter -->';
    $before_title = '<h3>';
    $after_title = '</h3>';
    ?>
    <?php echo $before_widget; ?>
    <?php if ($title) echo $before_title . $title . $after_title; ?>
    <div class="back" id="twitter_update_list_<?php echo $unique_id; ?>"></div>
    <?php
        if ( !empty($username) )
        {
            $return_html = '';
            $tweets = tfuse_get_tweets($username,$limit);
            //if(!sizeof($tweets)) return;

            foreach ( $tweets as $tweet )
            {
                $return_html .= '<div class="tweet_item">';
                $return_html .= '<div class="tweet_image"><img src="'.$tweet->user->profile_image_url.'" width="58" height="58" alt="" /></div>';
                if( isset($tweet->text) )
                {
                    $return_html .= '<div class="tweet_text"><div class="inner">'.$tweet->text.'</div></div>' ;
                }

                $return_html .= '<div class="clear"></div></div>';
            }
            echo $return_html;
        }
    echo $after_widget; ?>


    <?php
   }

   function update($new_instance, $old_instance) {
       return $new_instance;
   }

   function form($instance) {

       (isset($instance['title'])) ? $title = esc_attr($instance['title']) : $title = '';
       (isset($instance['username'])) ? $username = esc_attr($instance['username']) : $username = '';
       (isset($instance['limit'])) ? $limit = esc_attr($instance['limit']) : $limit = '';
       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (optional):','tfuse'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:','tfuse'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('username'); ?>"  value="<?php echo $username; ?>" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" />
       </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:','tfuse'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('limit'); ?>"  value="<?php echo $limit; ?>" class="" size="3" id="<?php echo $this->get_field_id('limit'); ?>" />

       </p>
      <?php
   }

}
register_widget('Tfuse_Twitter');
