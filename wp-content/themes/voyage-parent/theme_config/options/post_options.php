<?php

/* ----------------------------------------------------------------------------------- */
/* Initializes all the theme settings option fields for posts area. */
/* ----------------------------------------------------------------------------------- */

$options = array(
    /* ----------------------------------------------------------------------------------- */
    /* Sidebar */
    /* ----------------------------------------------------------------------------------- */

    /* Single Post */
    array('name' => __('Single Post', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_side_media',
        'type' => 'metabox',
        'context' => 'side',
        'priority' => 'low' /* high/low */
    ),
    // Disable Single Post Image
    array('name' => __('Disable Image', 'tfuse'),
        'desc' => '',
        'id' => TF_THEME_PREFIX . '_disable_image',
        'value' => tfuse_options('disable_image','false'),
        'type' => 'checkbox'
    ),
    // Disable Single Post Video
    array('name' => __('Disable Video', 'tfuse'),
        'desc' => '',
        'id' => TF_THEME_PREFIX . '_disable_video',
        'value' => tfuse_options('disable_video','false'),
        'type' => 'checkbox'
    ),
    // Disable Single Post Comments
    array('name' => __('Disable Comments', 'tfuse'),
        'desc' => '',
        'id' => TF_THEME_PREFIX . '_disable_comments',
        'value' => tfuse_options('disable_posts_comments','false'),
        'type' => 'checkbox',
        'divider' => true
    ),
    // Author Info
    array('name' => __('Disable Author Info', 'tfuse'),
        'desc' => '',
        'id' => TF_THEME_PREFIX . '_disable_author_info',
        'value' => tfuse_options('disable_author_info','false'),
        'type' => 'checkbox'
    ),
    // Post Meta
    array('name' => __('Disable Meta', 'tfuse'),
        'desc' => '',
        'id' => TF_THEME_PREFIX . '_disable_post_meta',
        'value' => tfuse_options('disable_post_meta','false'),
        'type' => 'checkbox'
    ),
    // Published Date
    array('name' => __('Disable Published Date', 'tfuse'),
        'desc' => '',
        'id' => TF_THEME_PREFIX . '_disable_published_date',
        'value' => tfuse_options('disable_published_date','false'),
        'type' => 'checkbox'
    ),
    // Share Buttons
    array('name' => __('Disable Share Buttons', 'tfuse'),
        'desc' => '',
        'id' => TF_THEME_PREFIX . '_disable_share_buttons',
        'value' => tfuse_options('_disable_share_buttons','false'),
        'type' => 'checkbox'
    ),
    /* ----------------------------------------------------------------------------------- */
    /* After Textarea */
    /* ----------------------------------------------------------------------------------- */

    /* Post Media */
    array('name' => __('Media', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_media',
        'type' => 'metabox',
        'context' => 'normal'
    ),
    // Single Image
    array('name' => __('Image', 'tfuse'),
        'desc' => __('This is the main image for your post. Upload one from your computer, or specify an online address for your image (Ex: http://yoursite.com/image.png).', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_single_image',
        'value' => '',
        'type' => 'upload',
        'hidden_children' => array(
            TF_THEME_PREFIX . '_single_img_dimensions',
            TF_THEME_PREFIX . '_single_img_position'
        )
    ),
    // Single Image Dimensions
    array('name' => __('Image Resize (px)', 'tfuse'),
        'desc' => __('These are the default width and height values. If you want to resize the image change the values with your own. If you input only one, the image will get resized with constrained proportions based on the one you specified.', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_single_img_dimensions',
        'value' => tfuse_options('single_image_dimensions'),
        'type' => 'textarray'
    ),
    // Single Image Position
    array('name' => __('Image Position', 'tfuse'),
        'desc' => __('Select your preferred image  alignment', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_single_img_position',
        'value' => tfuse_options('single_image_position'),
        'options' => array(
            'noalign' => array($url . 'full_width.png', __('Don\'t apply an alignment', 'tfuse')),
            'alignleft' => array($url . 'left_off.png', __('Align to the left', 'tfuse')),
            'alignright' => array($url . 'right_off.png', __('Align to the right', 'tfuse'))
            ),
        'type' => 'images',
        'divider' => true
    ),    
    // Thumbnail Image
    array('name' => __('Thumbnail', 'tfuse'),
        'desc' => __('This is the thumbnail for your post. Upload one from your computer, or specify an online address for your image (Ex: http://yoursite.com/image.png).', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_thumbnail_image',
        'value' => '',
        'type' => 'upload',
        'hidden_children' => array(
            TF_THEME_PREFIX . '_thumbnail_dimensions',
            TF_THEME_PREFIX . '_thumbnail_position'
        )
    ),
    // Posts Thumbnail Dimensions
    array('name' => __('Thumbnail Dimension (px)', 'tfuse'),
        'desc' => __('These are the default width and height values. If you want to resize the thumbnail change the values with your own. If you input only one, the thumbnail will get resized with constrained proportions based on the one you specified.', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_thumbnail_dimensions',
        'value' => tfuse_options('thumbnail_dimensions'),
        'type' => 'textarray'
    ),
    // Thumbnail Position
    array('name' => __('Thumbnail Position', 'tfuse'),
        'desc' => __('Select your preferred thumbnail alignment', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_thumbnail_position',
        'value' => tfuse_options('thumbnail_position'),
        'options' => array(
            'noalign' => array($url . 'full_width.png', __('Don\'t apply an alignment', 'tfuse')),
            'alignleft' => array($url . 'left_off.png', __('Align to the left', 'tfuse')),
            'alignright' => array($url . 'right_off.png', __('Align to the right', 'tfuse'))
            ),
        'type' => 'images',
        'divider' => true
    ),
    // Custom Post Video
    array('name' => __('Video', 'tfuse'),
        'desc' => __('Copy paste the video URL or embed code. The video URL works only for Vimeo and YouTube videos. Read <a target="_blank" href="http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/">prettyPhoto documentation</a>
                    for more info on how to add video or flash in this text area
                    ', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_video_link',
        'value' => '',
        'type' => 'textarea',
        'hidden_children' => array(
            TF_THEME_PREFIX . '_video_dimensions',
            TF_THEME_PREFIX . '_video_position'
        )
    ),
    // Video Dimensions
    array('name' => __('Video Size (px)', 'tfuse'),
        'desc' => __('These are the default width and height values. If you want to resize the video change the values with your own. If you input only one, the video will get resized with constrained proportions based on the one you specified.', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_video_dimensions',

        'value' => tfuse_options('video_dimensions'),

        'type' => 'textarray'
    ),
    // Video Position
    array('name' => __('Video Position', 'tfuse'),
        'desc' => __('Select your preferred video alignment', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_video_position',

        'value' => tfuse_options('video_position'),

        'options' => array(
            '' => array($url . 'full_width.png', __('Don\'t apply an alignment', 'tfuse')),
            'alignleft' => array($url . 'left_off.png', __('Align to the left', 'tfuse')),
            'alignright' => array($url . 'right_off.png', __('Align to the right', 'tfuse'))
            ),
        'type' => 'images'
    ),   
    /* Header Options */
    array('name' => __('Page elements', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_header_option',
        'type' => 'metabox',
        'context' => 'normal'
    ),
    // Element of Hedear
    array('name' => __('Hedear Element', 'tfuse'),
        'desc' => __('Select what do you want in your post header', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_header_element',
        'value' => 'none',
        'options' => array('none' => __('Without Element', 'tfuse'), 'slider' => __('Slider', 'tfuse')),
        'type' => 'select',
    ),
    // Select Slider
    $this->ext->slider->model->has_sliders() ?
        array(
            'name' => __('Slider', 'tfuse'),
            'desc' => __('Select a slider for your post. The sliders are created on the', 'tfuse') . '<a href="' . admin_url( 'admin.php?page=tf_slider_list' ) . '" target="_blank">' . __('Sliders page', 'tfuse') . '</a>.',
            'id' => TF_THEME_PREFIX . '_select_slider',
            'value' => '',
            'options' => $TFUSE->ext->slider->get_sliders_dropdown('slidesjs'),
            'type' => 'select'
        ) :
        array(
            'name' => __('Slider', 'tfuse'),
            'desc' => '',
            'id' => TF_THEME_PREFIX . '_select_slider',
            'value' => '',
            'html' => __('No sliders created yet. You can start creating one', 'tfuse') . '<a href="' . admin_url('admin.php?page=tf_slider_list') . '">' . __('here', 'tfuse') . '</a>.',
            'type' => 'raw'
        ),
    // Before Content Element
    array('name' => __('Before Content Element', 'tfuse'),
        'desc' => __('Select type of element before content.', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_before_content_element',
        'value' => 'search',
        'options' => array( 'none' => __('Without Element', 'tfuse'), 'search' => __('Search', 'tfuse')),
        'type' => 'select',
    ),
    array(
        'name' => __('Search Type', 'tfuse'),
        'desc' => __('Do you want your search to be Collapsed or Expanded when the page loads?', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_search_type',
        'value' => 'closed',
        'options' =>  array('closed' => __('Collapsed', 'tfuse'), 'expanded' => __('Expanded', 'tfuse')),
        'type' => 'select',
        'divider'   => true
    ),
    // After Content Element
    array('name' => __('After Content Element', 'tfuse'),
        'desc' => __('Select type of element after content.', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_after_content_element',
        'value' => 'after_content_widgets',
        'options' => array('none' => __('Without Element', 'tfuse'), 'after_content_widgets' => __('After Content Widgets', 'tfuse')),
        'type' => 'select',
    )
);

?>