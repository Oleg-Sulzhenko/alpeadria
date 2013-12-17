<?php

/* ----------------------------------------------------------------------------------- */
/* Initializes all the theme settings option fields for categories area.             */
/* ----------------------------------------------------------------------------------- */

$options = array(
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
        'value' => 'after_content_widgets',
        'options' => array('none' => __('Without Element', 'tfuse'), 'after_content_widgets' => __('After Content Widgets', 'tfuse')),
        'type' => 'select',
        'divider' => true,
    )
);

?>