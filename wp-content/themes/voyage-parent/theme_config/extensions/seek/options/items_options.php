<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

global $TFUSE;

/**
 * Define form items
 */

array(
/**
 * Option Structure:
 */
'<id>' => array( // id used as form item name in html and for TF_SEEK_HELPER::print_form_item('<id>'), please use only safe characters [a-z0-9_]
    'parameter_name'        => '<some-name>',
        // $_GET parameter name. Accessible from template and sql_generator as $parameter_name
        // make sure if the value of this parameter is unique within one form items
        // if you want to change that, make sure and search in all files if this value is not used hardcoded somewhere else
    'template'              => '<template-file-name>',
        // without .php, located in ../views/items/
        // if empty ('') , print function filters and actions within it will be executed but no template will be included
    'template_vars'         => array('foo'=>'bar'),
        // accessible from template as $vars['foo']
    'settings'              => array('foo'=>'bar'),
        // item settings accessible from template as $settings['foo'] and from <sql_generator> as third parameter
    'sql_generator'         => '<function-name>',
        // public static function from object located in ../includes/sql_generators.php
    'sql_generator_options' => array(
        // second parameter for sql_generator function
        'search_on'         => '<options>/<taxonomy>',
            // search in options or taxonomy
        'search_on_id'      => 'seek_property_price',
            // if 'search_on'='option': need to match id from ./<post_type>_options.php where 'searchable'=> TRUE,
            // if 'search_on'='taxonomy': need to match taxonomy name registered in ../register_custom_taxonomies.php
         '...something...'  => '...else...',
        ),
    )
);

$options = array(

    'location_select'  => array(
        'parameter_name'        => 'location_id',
        'template'              => 'taxonomy-select-parent',
        'template_vars'         => array(
            'label'             => __('DESTINATION', 'tfuse'),
            'default_option'    => __('All locations', 'tfuse'),
            'select_class'      => 'tf-seek-long-select-form-item-header',
        ),
        'settings'              => array(
            'select_parent'     => 0 // term_id
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_locations',
        ),
    ),

    'date_from'  => array(
        'parameter_name'        => 'date_in',
        'template'              => 'date-header',
        'template_vars'         => array(
            'value'             => __('Check-in date', 'tfuse'),
            'date_format'       => tfuse_options('search_date_format', 'MM dd, yy'),
            'dependency'        => array(
                'item_id'       => 'date_to',
                'relation'      => 'minDate' // allowed 'minDate' and 'maxDate'
            )
        ),
        'settings'              => array(),
        'sql_generator'         => 'date',
        'sql_generator_options' => array(
            'search_on'         => 'availability_from',
            'relation'          => '<=',
        ),
    ),

    'date_to'  => array(
        'parameter_name'        => 'date_out',
        'template'              => 'date-header',
        'template_vars'         => array(
            'value'             => __('Check-out date', 'tfuse'),
            'date_format'       => tfuse_options('search_date_format', 'MM dd, yy'),
            'dependency'        => array(
                'item_id'       => 'date_from',
                'relation'      => 'maxDate' // allowed 'minDate' and 'maxDate'
            )
        ),
        'settings'              => array(),
        'sql_generator'         => 'date',
        'sql_generator_options' => array(
            'search_on'         => 'availability_to',
            'relation'  => '>='
        ),
    ),

    'main_location'  => array(
        'parameter_name'            => 'main_location',
        'template'                  => 'taxonomy-radio-parent',
        'template_vars'             => array(
            'default_option'        => __('All LOCATIONS', 'tfuse')
        ),
        'settings'                  => array(
               'taxonomy'           => 'locations',
                'args'              => array(
                    'parent'        => 0,
                    'hide_empty'    => 0,
                    'orderby'       =>'count',
                    'order'         =>'desc',
                ),
                'select_parent'     => 0
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_locations',
        ),
    ),

    'sub_terms'  => array(
        'parameter_name'        => 'location_id',
        'template'              => 'sub-terms',
        'template_vars'         => array(
            'default_option'    => __('All Sub Locations', 'tfuse'),
            'no_terms'          => __('No Sub Locations'),
            'all_sub_terms'     => __('All Sub Locations', 'tfuse'),
            'select_class'      => 'tf-seek-long-select-form-item-header',
        ),
        'settings'              => array(
            'default_parent'    => 0, // term_id
            'dependency'        => 'main_location',
            'taxonomy'          => 'locations',
            'select_parent'     => 0
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_locations',
        ),
    ),

    'filter_price_range'     => array(
        'parameter_name'        => 'price',
        'template'              => 'price-range-filter',
        'template_vars'         => array(
            'label'             => __('PRICE RANGE', 'tfuse'),
            'label_class'       => 'label_title2',
        ),
        'settings'              => array(
            'from'              => 100,
            'to'                => 10000,
            'selected_values'   => array(), //one or two default selected values
            'scale'             => array( TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '100', TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '10k'),
            'heterogeneity'     => array(),
            'limits'            => FALSE,
            'step'              => 100,
            'smooth'            => TRUE,
            'dimension'         => TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'),
            'skin'              => "round_green",

            'auto_options'      => true,
        ),
        'sql_generator'         => 'range_slider',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_price',
        ),
    ),

    'includes_checkboxes'  => array(
        'parameter_name'        => 'includes_checkboxes',
        'template'              => 'select-checkboxes',
        'template_vars'         => array(
            'label'             => __('It includes', 'tfuse'),
            'items'       => array(
                1 => __('Without Dining', 'tfuse'),
                2 => __('Breakfast', 'tfuse'),
                3 => __('Half Board', 'tfuse'),
                4 => __('All Inclusive', 'tfuse')
            ),

            'listen_form_id'        => 'main_search',
            'show_counts'           => true,
            'counts_label_singular' => ' '.__('offer', 'tfuse'),
            'counts_label_plural'   => ' '.__('offers', 'tfuse'),
        ),
        'settings'              => array(
            'min'               => 1,
            'max'               => 4,
        ),
        'sql_generator'         => 'options_int',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_includes',
            'relation'          =>'=',
            'relation_max'      =>'='
        ),
    ),

    'tax_ids_category'  => array(
        'parameter_name'        => 'category_ids',
        'template'              => 'taxonomy-checkboxes',
        'template_vars'         => array(
            'label'             => __('Category', 'tfuse'),
            'label_class'       => 'label_title2',
            'get_terms_args'    => '&orderby=count&order=desc',

            'listen_form_id'        => 'main_search',
            'show_counts'           => true,
            'counts_label_singular' => '',
            'counts_label_plural'   => '',
        ),
        'settings'              => array(),
        'sql_generator'         => 'taxonomy_multi_ids',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_category',
        ),
    ),

    'filter_tags_input'  => array(
        'parameter_name'        => 'tags',
        'template'              => 'input-auto-completer-filter',
        'template_vars'         => array(
            'label'             => __('Tags', 'tfuse'),
        ),
        'settings'              => array(),
        'sql_generator'         => 'taxonomy_multivalue',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_tags',
        ),
    ),

    'filter_tag_select'  => array(
        'parameter_name'        => 'tags',
        'template'              => 'taxonomy-select-parent',
        'template_vars'         => array(
            'label'             => __('Tag', 'tfuse'),
            'default_option'    => __('All Tags', 'tfuse'),
        ),
        'settings'              => array(
            'select_parent'     => 0 // term_id
        ),
        'sql_generator'         => 'single_select',
        'sql_generator_options' => array(
            'search_on_field'   => '_tags',
            'search_on'         => 'taxonomy',
            'search_on_id'      =>  TF_SEEK_HELPER::get_post_type().'_tag',
        ),
    ),

    'filter_tags_checkboxes'  => array(
        'parameter_name'        => 'tags',
        'template'              => 'taxonomy-checkboxes',
        'template_vars'         => array(
            'label'             => __('Tags', 'tfuse'),
            'label_class'       => 'label_title2',
            'get_terms_args'    => '&orderby=name&order=asc',
            'listen_form_id'        => 'main_search',
            'show_counts'           => true,
            'counts_label_singular' => '',
            'counts_label_plural'   => '',
        ),
        'settings'              => array(
        ),
        'sql_generator'         => 'taxonomy_multi_ids',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_tag',
        ),
    ),
    //START filter tags checkboxes with AND relation
    /*'filter_tags_checkboxes'  => array(
        'parameter_name'        => 'tags',
        'template'              => 'taxonomy-checkboxes',
        'template_vars'         => array(
            'label'             => __('Tags', 'tfuse'),
            'label_class'       => 'label_title2',
            'get_terms_args'    => '&orderby=name&order=asc',
            'listen_form_id'        => 'main_search',
            'show_counts'           => true,
            'counts_label_singular' => '',
            'counts_label_plural'   => '',
        ),
        'settings'              => array(
        ),
        'sql_generator'         => 'taxonomy_multi_ids_without_framework',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'intern_relation'   => 'AND',//allowed: AND,OR(OR default)
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_tag',
        ),
    ),*/
    //END filter tags checkboxes with AND relation
    'filter_location_select'  => array(
        'parameter_name'        => 'location_id',
        'template'              => 'filter-checkbox-taxonomy-select-parent',
        'template_vars'         => array(
            'listen_items'        => array('location_id', 'main_location'),
            'show_counts'           => true,
            'counts_label_singular' => '',
            'counts_label_plural'   => '',
        ),
        'settings'              => array(
            'parent_item_id'    => 'location_select',
            'only_top_level'    => true
        ),
        'sql_generator'         => 'taxonomy_multi_ids',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_locations',
        ),
    ),

    'filter_date_from'  => array(
        'parameter_name'        => 'date_in',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'value'             => __('Check-in date', 'tfuse'),
        ),
        'settings'              => array(),
        'sql_generator'         => 'date',
        'sql_generator_options' => array(
            'search_on'         => 'availability_from',
            'relation'          => '<=',
        ),
    ),

    'filter_date_to'  => array(
        'parameter_name'        => 'date_out',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'value'             => __('Check-out date', 'tfuse'),
        ),
        'settings'              => array(),
        'sql_generator'         => 'date',
        'sql_generator_options' => array(
            'search_on'         => 'availability_to',
            'relation'          => '>=',
        ),
    ),

    'short_latest_price_range'     => array(
        'parameter_name'        => 'price',
        'template'              => 'price-range-filter',
        'template_vars'         => array(
            'label'             => __('Price range', 'tfuse'),
            'label_class'       => 'label_title',
        ),
        'settings'              => array(
            'from'              => 100,
            'to'                => 10000,
            'selected_values'   => array(400, 3900), //one or two default selected values
            'scale'             => array( TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '100', TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '10k'),
            'heterogeneity'     => array(),
            'limits'            => FALSE,
            'step'              => 100,
            'smooth'            => TRUE,
            'dimension'         => TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'),
            'skin'              => "round_green",

            'auto_options'      => true,
        ),
        'sql_generator'         => 'range_slider',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_price',
        ),
    ),

    'short_latest_checkbox_categories'     => array(
        'parameter_name'        => 'category_ids',
        'template'              => 'short-taxonomy-checkboxes',
        'template_vars'         => array(
            'label'             => __('Show offers from', 'tfuse'),
            'label_class'       => 'label_title',
            'get_terms_args'    => 'hide_empty=0&orderby=count&order=desc',

        ),
        'settings'              => array(),
        'sql_generator'         => 'taxonomy_multi_ids',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_category',
        ),
    ),

    'short_promo_price_range'     => array(
        'parameter_name'        => 'price',
        'template'              => 'price-range-filter',
        'template_vars'         => array(
            'label'             => __('Price range', 'tfuse'),
            'label_class'       => 'label_title',
        ),
        'settings'              => array(
            'from'              => 100,
            'to'                => 10000,
            'selected_values'   => array(100, 2500), //one or two default selected values
            'scale'             => array( TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '100', TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '10k'),
            'heterogeneity'     => array(),
            'limits'            => FALSE,
            'step'              => 100,
            'smooth'            => TRUE,
            'dimension'         => TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'),
            'skin'              => "round_green",

            'auto_options'      => true,
        ),
        'sql_generator'         => 'range_slider',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_price',
        ),
    ),

    'short_promo_checkbox_categories'     => array(
        'parameter_name'        => 'category_ids',
        'template'              => 'short-taxonomy-checkboxes',
        'template_vars'         => array(
            'label'             => __('Show offers from', 'tfuse'),
            'label_class'       => 'label_title',
            'get_terms_args'    => 'hide_empty=0&orderby=count&order=desc',

        ),
        'settings'              => array(),
        'sql_generator'         => 'taxonomy_multi_ids',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_category',
        ),
    ),

    'promos'     => array(
        'parameter_name'        => 'promos',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'no_output'         => !$TFUSE->request->isset_GET('promos')
        ),
        'settings'              => array(),
        'sql_generator'         => 'promos',
        'sql_generator_options' => array(
            'search_on'         => '',
            'search_on_id'      => '',
        ),
    ),
);