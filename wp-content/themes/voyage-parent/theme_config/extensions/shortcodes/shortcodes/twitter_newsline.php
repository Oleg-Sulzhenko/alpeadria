<?php

/**
 * Twitter Newsline
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 * 
 * Optional arguments:
 * items: 5
 * username:
 * title:
 */

function tfuse_twitter_newsline($atts, $content = null)
{
    global $twitter_uniq;
    wp_enqueue_script( 'jcarousel' );

    $twitter_uniq = rand(1, 300);

    extract(shortcode_atts(array(
            'items' => 5,
            'username' => '',
            'title' => '',
    ), $atts));

    $return_html = '';

    if ( !empty($username) )
    {
        $tweets = tfuse_get_tweets($username,$items);
        if(!sizeof($tweets)) return;

        $tweets_content = '';

        foreach ( $tweets as $tweet )
        {
            $tweets_content .= '<li>';

            $tweets_content.= $tweet->text;

            $tweets_content .= '</li>';
        }
    }

    $return_html = '<!-- latest news line --><div class="newsline">';
    $return_html .= '<h2><a href="http://twitter.com/#!/' . $username . '" onmouseover="this.style.color = \'#fff\'">' . $title . '</a></h2>';
    $return_html .= '<ul id="twitterlist' . $twitter_uniq . '" class="jcarousel-skin-newsline">';
    $return_html .= $tweets_content;
    $return_html .= '</ul></div>';
    $return_html .= '<script type="text/javascript">
    jQuery(document).ready(function($) {
        jQuery("#twitterlist' . $twitter_uniq . '").jcarousel({
            vertical: true,
            scroll: 1,
            animation: 300,
            auto: 5, // Time interval in sec.
            wrap: "circular",
            initCallback: mycarousel_initCallback
        });
    });
    </script>
<!--/ latest news line -->';

    return $return_html;
}

$atts = array(
    'name' => __('Twitter Newsline', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Items', 'tfuse'),
            'desc' => __('Enter the number of tweets', 'tfuse'),
            'id' => 'tf_shc_twitter_newsline_items',
            'value' => '5',
            'type' => 'text'
        ),
        array(
            'name' => __('Title', 'tfuse'),
            'desc' => __('Specifies the title of an shortcode', 'tfuse'),
            'id' => 'tf_shc_twitter_newsline_title',
            'value' => 'Latest News',
            'type' => 'text'
        ),
        array(
            'name' => __('Username', 'tfuse'),
            'desc' => __('Twitter username', 'tfuse'),
            'id' => 'tf_shc_twitter_newsline_username',
            'value' => '',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('twitter_newsline', 'tfuse_twitter_newsline', $atts);
