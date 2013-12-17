<?php
 $item = $settings['item_settings'];
    if($item['object']['type'] == 'taxonomy')
    {
        $get_terms_settings = array(
            'orderby'       => $settings['tf_megamenu_order_by'],
            'order'         => $settings['tf_megamenu_order'],
            'hide_empty'    => false,
        );
        $visible_childs = $settings['tf_megamenu_num_items'];
        $link_more = false;
        $term = get_term($item['object']['object_id'], $item['object']['object']);
        if(is_wp_error($term)) return;
        $args = array(
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => true,
            'fields'        => 'all',
            'parent'         => $item['object']['object_id']
        );
        $args = wp_parse_args( $get_terms_settings, $args );
        $terms = get_terms($item['object']['object'], $args);
        $enabled_link_more = ($settings['tf_megamenu_has_see_more_link'] && $settings['tf_megamenu_has_see_more_link'] != 'false') ? true : false ;
        $i = 0;
        ?>
        <a href="<?php print get_term_link($term); ?>">
            <?php if($settings['tf_megamenu_image_url']) : ?><img src="<?php echo TF_GET_IMAGE::get_src_link($settings['tf_megamenu_image_url'], 80, 68); ?>" width="80" height="68" alt=""><?php endif; ?>
            <span><?php echo $settings['item_settings']['item_title']; ?></span>
        </a>
        <ul class="submenu-2">
            <?php foreach ($terms as $child) : ?>
            <li class="menu-level-2"><a href="<?php print get_term_link($child); ?>"><span><?php echo $child->name; ?></span></a></li>
            <?php $i++; if($i == $visible_childs) {$link_more = true; break;}; endforeach; ?>
            <?php if($enabled_link_more && $link_more) : ?><li class="menu-level-2 more-nav"><a href="<?php print get_term_link($term); ?>"><span><?php _e($settings['tf_megamenu_see_more_url'], 'tfuse'); ?></span> </a></li><?php endif; ?>
        </ul>
<?php }
    elseif($item['object']['object'] == 'page')
    { 
        $visible_childs = $settings['tf_megamenu_num_items'];
         $posts = get_posts(array('posts_per_page'  => -1,'post_type' => 'page'));
            $terms = array();
            foreach ($posts as $post) {
                if($post->post_parent == $item['object']['object_id'])
                    $terms[] = $post;
            }
            $enabled_link_more = ($settings['tf_megamenu_has_see_more_link'] && $settings['tf_megamenu_has_see_more_link'] != 'false') ? true : false ;
        $i = 0;
        ?>
        <a href="<?php print $item['url']; ?>">
            <?php if($settings['tf_megamenu_image_url']) : ?><img src="<?php echo TF_GET_IMAGE::get_src_link($settings['tf_megamenu_image_url'], 80, 68); ?>" width="80" height="68" alt=""><?php endif; ?>
            <span><?php echo $settings['item_settings']['item_title']; ?></span>
        </a>
        <ul class="submenu-2">
            <?php foreach ($terms as $child) : ?>
            <li class="menu-level-2"><a href="<?php print get_permalink($child->ID); ?>"><span><?php echo $child->post_title; ?></span></a></li>
            <?php $i++; if($i == $visible_childs) {$link_more = true; break;}; endforeach; ?>
            <?php if($enabled_link_more && $link_more) : ?><li class="menu-level-2 more-nav"><a href="<?php print get_term_link($term); ?>"><span><?php _e($settings['tf_megamenu_see_more_url'], 'tfuse'); ?></span> </a></li><?php endif; ?>
        </ul>
   <?php }?>