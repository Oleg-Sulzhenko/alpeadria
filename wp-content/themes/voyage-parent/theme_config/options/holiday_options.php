<?php

$options = array(

    /* Single Page */
    array('name' => __('Single Page', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_side_media',
        'type' => 'metabox',
        'context' => 'side',
        'priority' => 'low' /* high/low */
    ),
    // Disable Comments
    array('name' => __('Disable Comments', 'tfuse'),
            'desc' => '',
            'id' => TF_THEME_PREFIX . '_disable_comments',
            'value' => tfuse_options('disable_holidays_comments', true),
            'type' => 'checkbox',
            'divider' => true
    ),
    // Page Title
    array('name' => __('Page Title', 'tfuse'),
        'desc' => __('Select your preferred Page Title.', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_page_title',
        'value' => 'default_title',
        'options' => array('hide_title' => __('Hide Page Title', 'tfuse'), 'default_title' => __('Default Title', 'tfuse'), 'custom_title' => __('Custom Title', 'tfuse')),
        'type' => 'select'
    ),
    // Custom Title
    array('name' => __('Custom Title', 'tfuse'),
        'desc' => __('Enter your custom title for this page.', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_custom_title',
        'value' => '',
        'type' => 'text'
    ),

    /* ----------------------------------------------------------------------------------- */
    /* After Textarea */
    /* ----------------------------------------------------------------------------------- */
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
        'divider' => true

    ),
    // After Content Element
    array('name' => __('After Content Element', 'tfuse'),
        'desc' => __('Select type of element after content.', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_after_content_element',
        'value' => 'similar_holidays',
        'options' => array('none' => __('Without Element', 'tfuse'), 'after_content_widgets' => __('After Content Widgets', 'tfuse'), 'similar_holidays' => __('SIMILAR ', 'tfuse') . mb_strtoupper(TF_SEEK_HELPER::get_option('seek_property_name_plural','Holidays'), 'UTF-8')),
        'type' => 'select',
    )
);
