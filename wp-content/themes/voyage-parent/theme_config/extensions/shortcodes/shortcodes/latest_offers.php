<?php

/**
 * Latest Offers
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 * Optional arguments:
 * items:
 * title:
 */

function tfuse_latest_offers($atts)
{
    extract(shortcode_atts(array(
                'items' => 6,
                'title' => '',
                'link'  => '',
                'href'  => '#',
            ), $atts));
    $offers = tfuse_get_latest_offers ($items);
    $html = '<!-- LATEST offers list --><div class="title">';
    if($title)  $html .= '<h2>' . $title .'</h2>';
    if($link)   $html .= '<span class="title_right"><a href="' . $href . '">' . $link . '</a></span></div><!-- filter_mid --><div class="block_hr filter_mid">';
    ob_start(); TF_SEEK_HELPER::print_form('short_latest_search'); $serch_form = ob_get_contents(); ob_end_clean();  $html .= $serch_form;
    $html .= '</div><!--/ filter_mid -->';

    $unit = TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$');
    $html .= '<div class="grid_list">';
    foreach($offers as $offer) :
        $cur_unit = (is_numeric($offer['price'])) ? $unit : '';
        $html .= '<div class="list_item">';
        $html .= '<div class="item_img">';
        $image = new TF_GET_IMAGE();
        $image->removeSizeParams = true;
        $html .=  '<a href="' . $offer['url'] . '">' . $image->width(300)->height(210)->src($offer['thumb'])->get_img() . '</a>';
        $of_title = $offer['title'];
        if($offer['during']!=0) $of_title .=  __(' - ', 'tfuse') . $offer['during'] . __(' nights', 'tfuse');
        $html .='<p class="caption"><a href="' . $offer['url'] . '">' . $of_title . '</a> <span class="price"><strong>' . $offer['price'] . '</strong> <ins>' . $cur_unit . '</ins></span></p>';
        if($offer['reduction']>0) $html .= '<span class="ribbon off-' . $offer['reduction'] . '">' . __('SALE: ','tfuse') .  $offer['reduction'] . __('%', 'tfuse') . __(' OFF', 'tfuse') . '</span>';
        $html .= '</div>';
        $html .= '</div>';
    endforeach;
    $html .= '<div class="clear"></div></div><!--/ LATEST offers list -->';

    return $html;
}

$atts = array(
    'name' => __('Latest Offers', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Items', 'tfuse'),
            'desc' => __('Specifies the number of the offers to show.', 'tfuse'),
            'id' => 'tf_shc_latest_offers_items',
            'value' => '6',
            'type' => 'text'
        ),
        array(
            'name' => __('Title', 'tfuse'),
            'desc' => __('Specifies the title for an shortcode.', 'tfuse'),
            'id' => 'tf_shc_latest_offers_title',
            'value' => 'EXPLORE OUR LATEST OFFERS',
            'type' => 'text'
        ),
        array(
            'name' => __('Link', 'tfuse'),
            'desc' => __('A custom link', 'tfuse'),
            'id' => 'tf_shc_latest_offers_link',
            'value' => 'See all Latest offers',
            'type' => 'text'
        ),
        array(
            'name' => __('URL', 'tfuse'),
            'desc' => __('A url for custom link', 'tfuse'),
            'id' => 'tf_shc_latest_offers_href',
            'value' => '#',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('latest_offers', 'tfuse_latest_offers', $atts);
