<?php
if (!defined('TFUSE')) {
    exit('Direct access forbidden.');
}

$prefix = 'tf_megamenu_';

// the templates available for this theme
// note that the the keys must be valid templates
// from the $cfg['all_templates'] arr
$cfg['active_templates'] = array(
    'custom' => __('Custom', 'tfuse'),
    'from_children' => 'From Children'
);

$cfg['commun_options'] = array(
    // 0 depth commun options
    array(

        array(
            'name'       => __('MegaMenu ON', 'tfuse'),
            'id'         => $prefix . 'is_mega',
            'type'       => 'checkbox',
            'properties' => array(
                'class' => $prefix . 'nav_parent_switch'
            ),
            'options' => array(
                true  => 'yes',
                false => 'no'
            ),
            'value'      => false
        )

    ),


    // 1 depth common options
    array(

        array(
            'name' => __('Select population method', 'tfuse'),
            'id' => $prefix . 'menu_template',
            'type' => 'select',
            'properties' => array(
                'class' => $prefix . 'template_select'
            ),
            'options' => $cfg['active_templates'],
            'value' => false
        ),

    )

);

// the list of templates available
// for 1 depth menu nav items
$cfg['all_templates'] = array(

    'custom' => array(),

    'from_children' => array(
        array(
            'name' => __('Image URL', 'tfuse'),
            'id' => $prefix . 'image_url',
            'type' => 'text',
            'properties' => array(
                'placeholder' => __('Enter the image url here', 'tfuse')
            ),
            'value' => ''
        ),
        array(
            'name' => __('How many items to display', 'tfuse'),
            'id' => $prefix . 'num_items',
            'type' => 'text',
            'value' => '8'
        ),
        array(
            'name'      => __('Order By', 'tfuse'),
            'id'        => $prefix . 'order_by',
            'type'      => 'select',
            'options'   => array('id' => 'ID', 'count' => 'Count', 'name' => __('Name', 'tfuse'),  'slug' => 'Slug'),
            'value'     => 'name',
            'properties' => array( 'class'     => 'for_taxonomy'),
            
        ),
        array(
            'name' => __('Order', 'tfuse'),
            'id' => $prefix . 'order',
            'type' => 'select',
            'options'   => array('ASC' => __('Ascending', 'tfuse'), 'desc' => __('Descending', 'tfuse')),
            'value' => 'ASC'
        ),
        array(
            'name' => __('Enable \'See more\' link', 'tfuse'),
            'id' => $prefix . 'has_see_more_link',
            'type' => 'checkbox',
            'options' => array(
                true => 'yes',
                false => 'no'
            ),
            'value' => true
        ),
        array(
            'name' => __('\'See more\' text', 'tfuse'),
            'id' => $prefix . 'see_more_url',
            'type' => 'text',
            'value' => __('See all Destinations', 'tfuse')
        )
    )
);

$cfg['megafied_parent_li_css_classes'] = array(
    'mega-nav'
);

$cfg['megafied_child_li_css_classes'] = array(
    'menu-level-1'
);
