<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


/**
 * Define forms
 */

/*- Help -*/
array(
    // Optins structure
    '<id>'   => array(
        'items'         => array( '<item-id>', '<item-id>', '...' ),
        'template'      => '<template-name>', // Located in views/forms/ (without '.php')
        'template_vars' => array('foo'=>'bar'), // Some $vars accesibile from template (ex: $vars['foo'])
        'attributes'    => array(
            'class'     => 'foo',
            'onsubmit'  => 'bar',
        ), // <form class="foo" onsubmit="bar" >
    ),
);
/*---*/
global $search;
$options = array(

    'main_search'   => array(
        'items'         => array(
            'location_select',
            'date_from',
            'date_to',
            'promos'
        ),
        'template'      => 'header-form',
        'template_vars' => array(),
        'attributes'    => array(
            'class' =>'form_search'
        )
    ),

    'filter_search' => array(
        'items'         => array(
            'filter_tag_select',
            //'filter_tags_checkboxes',
            'filter_price_range',
            'includes_checkboxes',
            'tax_ids_category',
            'filter_location_select',
            'filter_date_from',
            'filter_date_to',
            'promos'
        ),
        'template'      => 'filter-form',
        'template_vars' => array(),
        'attributes'    => array(
            'class'     => 'form_white'
        ),
    ),

    'advanced_search' => array(
        'items'         => array(
            'main_location',
            'date_from',
            'date_to',
            'sub_terms',
            'promos'
        ),
        'template'      => 'advanced-form',
        'template_vars' => array(),
        'attributes'    => array(
            'class'     => 'form_search'
        ),
    ),

    'short_latest_search' => array(
        'items'         => array(
            'short_latest_checkbox_categories',
            'short_latest_price_range'
        ),
        'template'      => 'short-latest-form',
        'template_vars' => array(),
        'attributes'    => array(),
    ),

    'short_promo_search' => array(
        'items'         => array(
            'short_promo_checkbox_categories',
            'short_promo_price_range'
        ),
        'template'      => 'short-promo-form',
        'template_vars' => array(),
        'attributes'    => array(),
    ),
);