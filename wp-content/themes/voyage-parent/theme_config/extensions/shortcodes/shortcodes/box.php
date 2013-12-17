<?php

/**
 * Styled Boxes
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 * Optional arguments:
 * title: Shortcode title
 * class: custom class
 */

function tfuse_styled_box($atts, $content = null)
{
    extract( shortcode_atts(array('title' => '','type' => 'sb', 'class' => ''), $atts) );

    if ($type == 'box')
    {
        $html = '<div class="box '.$class.'">'
            . do_shortcode($content) .
                    ' <div class="clear"></div>
                </div>';
    }
    else
    {
        $html = '<div class="sb '.$class.'">
                <div class="box_title">' . $title . '</div>
                <div class="box_content">'
            . do_shortcode($content) .
            '<div class="clear"></div>
                </div>
            </div>';
    }


    return $html;
}
$atts = array(
    'name' => __('Boxes', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 7,
    'options' => array(
        array(
            'name' => __('Title', 'tfuse'),
            'desc' => __('Text to display above the box', 'tfuse'),
            'id' => 'tf_shc_styled_box_title',
            'value' => __('Styled box Title', 'tfuse'),
            'type' => 'text'
        ),
        array(
            'name' => __('Type', 'tfuse'),
            'desc' => __('Type of boxes', 'tfuse'),
            'id' => 'tf_shc_styled_box_type',
            'value' => 'sb',
            'options' => array(
                'sb' => __('Styled Box', 'tfuse'),
                'box' => __('Box', 'tfuse'),
            ),
            'type' => 'select'
        ),
        array(
            'name' => __('Class', 'tfuse'),
            'desc' => __('Specifies one or more class names for an shortcode, separated by space.
                <br /><b>predefined classes:</b> sb_pink, sb_yellow, sb_blue, sb_green, sb_dark_gray, sb_purple, sb_orange', 'tfuse'),
            'id' => 'tf_shc_styled_box_class',
            'value' => '',
            'type' => 'text'
        ),
        /* add the fllowing option in case shortcode has content */
        array(
            'name' => __('Content', 'tfuse'),
            'desc' => __('Enter shortcode content', 'tfuse'),
            'id' => 'tf_shc_styled_box_content',
            'value' => 'Insert the content here',
            'type' => 'textarea'
        )
    )
);

tf_add_shortcode('styled_box', 'tfuse_styled_box', $atts);
