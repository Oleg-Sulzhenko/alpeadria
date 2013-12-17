<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

/* ----------------------------------------------------------------------------------- */
/* Initializes all the seek settings option fields. */
/* ----------------------------------------------------------------------------------- */

$options = array(
    'tabs' => array(
        array(
            'name'      => TF_SEEK_HELPER::get_option('seek_property_name_plural', 'Seek Posts'),
            'type'      => 'tab',
            'id'        => TF_THEME_PREFIX . '_seek_general',
            'headings'  => array(
                array(
                    'name'      => __('General Settings', 'tfuse'),
                    'options'   => array(
                        array(
                            'name'      => __('Holiday Name, singular', 'tfuse'),
                            'desc'      => __('The name of the holiday being sold. (i.e. property, house, automobile) in singular form.', 'tfuse'),
                            'id'        => 'seek_property_name_singular',
                            'value'     => 'Holiday',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Holiday Name, plural', 'tfuse'),
                            'desc'      => __('The name of the holiday being sold. (i.e. properties, houses, automobiles) in plural form.', 'tfuse'),
                            'id'        => 'seek_property_name_plural',
                            'value'     => 'Holidays',
                            'type'      => 'text',
                            'divider'   => true
                        ),
                        array(
                            'name'      => __('Money Currency, singular', 'tfuse'),
                            'desc'      => __('The name of the currency being used. (i.e. Dollar, Euro, Pound) in singular form.', 'tfuse'),
                            'id'        => 'seek_property_currency_singular',
                            'value'     => 'Dollar',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Money Currency, plural', 'tfuse'),
                            'desc'      => __('The name of the currency being used. (i.e. Dollars, Euros, Pounds) in plural form.', 'tfuse'),
                            'id'        => 'seek_property_currency_plural',
                            'value'     => 'Dollars',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Money Currency, symbol','tfuse'),
                            'desc'      => __('The symbol of the currency being used. (i.e. $, €, £) in singular form.', 'tfuse'),
                            'id'        => 'seek_property_currency_symbol',
                            'value'     => '$',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Suffix after price','tfuse'),
                            'desc'      => __('The suffix for price for regular offers.(per day,per moth, /day)', 'tfuse'),
                            'id'        => 'seek_property_regular_price_suffix',
                            'value'     => ' /day',
                            'type'      => 'text',
                            'divider'   => true
                        ),
                        array(
                            'name'      => __('Default ', 'tfuse') . mb_strtolower(TF_SEEK_HELPER::get_option('seek_property_name_singular', 'holiday'), 'UTF-8') . ' type',
                            'desc'      => __('Select default type for when you add a new ', 'tfuse') . mb_strtolower(TF_SEEK_HELPER::get_option('seek_property_name_singular', 'holiday'), 'UTF-8'),
                            'id'        => 'seek_property_default_offer_type',
                            'value'     => '1',
                            'type'      => 'select',
                            'options'   => array(1 => __('Regular', 'tfuse'), 2 => __('Package', 'tfuse'))
                        )
                    )
                )
            )
        ),
    )
);