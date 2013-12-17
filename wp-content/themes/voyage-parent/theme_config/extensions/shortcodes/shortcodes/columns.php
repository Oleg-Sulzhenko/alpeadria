<?php

/**
 * Columns
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 * 
 * Optional arguments:
 * type: 1, 1_2, 1_3, 1_4, 2_3 etc.
 * class:

 */

function tfuse_col($atts, $content = null)
{
    extract(shortcode_atts(array('type' => '1', 'class' => '', 'rem' => ''), $atts));
    if ($rem == '')
    {
        return '<div class="col col_' . $type . ' ' . $class . '"><div class="inner">' . do_shortcode($content) . '</div></div>';
    }
    else
    {
        return '<div class="col col_' . $type . ' ' . $class . '">' . do_shortcode($content) . '</div>';
    }

}

$atts = array(
    'name' => __('Columns', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the button shortcode.', 'tfuse'),
    'category' => 4,
    'options' => array(
        array(
            'name' => __('Type', 'tfuse'),
            'desc' => __('Select column type', 'tfuse'),
            'id' => 'tf_shc_col_type',
            'value' => '_self',
            'options' => array(
                '1' => __('One column', 'tfuse'),
                '1_2' => __('One half column (1/2)', 'tfuse'),
                '1_3' => __('One third column (1/3)', 'tfuse'),
                '1_4' => __('A fourth column (1/4)', 'tfuse'),
                '1_5' => __('A fifth column (1/5)', 'tfuse'),
                '2_3' => __('Two thirds column (2/3)', 'tfuse'),
                '2_5' => __('Two fifths of the column (2/5)', 'tfuse'),
                '3_4' => __('Three fourths column (3/4)', 'tfuse'),
                '3_5' => __('Three fifts column (3/5)', 'tfuse'),
                '4_5' => __('Four fifths column (4/5)', 'tfuse')
            ),
            'type' => 'select'
        ),
        array(
            'name' => __('Class', 'tfuse'),
            'desc' => __('Specifies one or more class names for an shortcode. To specify multiple classes,<br /> separate the class names with a space, e.g. <b>"left important"</b>.', 'tfuse'),
            'id' => 'tf_shc_col_class',
            'value' => '',
            'type' => 'text'
        ),
        /* add the fllowing option in case shortcode has content */
        array(
            'name' => __('Content', 'tfuse'),
            'desc' => __('Enter shortcode content', 'tfuse'),
            'id' => 'tf_shc_col_content',
            'value' => '',
            'type' => 'textarea'
        ),
        array(
            'name' => __('Remove inner', 'tfuse'),
            'desc' => __('Remove inner div', 'tfuse'),
            'id' => 'tf_shc_col_rem',
            'value' => '',
            'type' => 'checkbox'
        )
    )
);

tf_add_shortcode('col', 'tfuse_col', $atts);
