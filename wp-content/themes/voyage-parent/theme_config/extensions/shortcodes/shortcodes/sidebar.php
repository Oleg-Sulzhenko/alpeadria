<?php

/**
 * Display dynamic sidebar.
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 * 
 * Optional arguments:
 * index: or name: int|string Optional, default is 1. Name or ID of dynamic sidebar.
 * 
 * http://codex.wordpress.org/Function_Reference/dynamic_sidebar
 */

function tfuse_sidebar($atts)
{
    extract(shortcode_atts(array('index' => 1, 'name' => ''), $atts));

    if ( !empty($name) ) $index = $name;

    ob_start();
    dynamic_sidebar($index);
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}

$atts = array(
    'name' => __('Sidebar', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Index', 'tfuse'),
            'desc' => __('Specifies the sidebar ID <br /><br /> OR', 'tfuse'),
            'id' => 'tf_shc_sidebar_index',
            'value' => '',
            'type' => 'text'
        ),
        array(
            'name' => __('Name', 'tfuse'),
            'desc' => __('Specifies the sidebar name (optional)', 'tfuse'),
            'id' => 'tf_shc_sidebar_name',
            'value' => '',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('sidebar', 'tfuse_sidebar', $atts);
