<?php

/**
 * Twitter
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 * 
 * Optional arguments:
 * items: 5
 * username:
 * title:
 * post_date:
 */

function tfuse_twitter($atts, $content = null)
{
    extract(shortcode_atts(array(
            'items' => 5,
            'username' => '',
            'title' => '',
            'post_date' => '',
    ), $atts));

    $return_html = '';

    if ( !empty($username) )
    {
        $tweets = tfuse_get_tweets($username,$items);
        if(!sizeof($tweets)) return;

        $return_html .= '<div class="twitter">';

        if (!empty($title))
            $return_html .= '<h2>' . $title . '</h2><ul> ';

        foreach ( $tweets as $tweet )
        {
            $return_html .= '<li>';

            $return_html.= '<p>' . $tweet->text . '</p>';

            $return_html .= '</li>';
        }

        $return_html .= '</ul></div>';
    }

    return $return_html;
}

$atts = array(
    'name' => __('Twitter', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Items', 'tfuse'),
            'desc' => __('Enter the number of tweets', 'tfuse'),
            'id' => 'tf_shc_twitter_items',
            'value' => '5',
            'type' => 'text'
        ),
        array(
            'name' => __('Title', 'tfuse'),
            'desc' => __('Specifies the title of an shortcode', 'tfuse'),
            'id' => 'tf_shc_twitter_title',
            'value' => '',
            'type' => 'text'
        ),
        array(
            'name' => __('Username', 'tfuse'),
            'desc' => __('Twitter username', 'tfuse'),
            'id' => 'tf_shc_twitter_username',
            'value' => '',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('twitter', 'tfuse_twitter', $atts);
