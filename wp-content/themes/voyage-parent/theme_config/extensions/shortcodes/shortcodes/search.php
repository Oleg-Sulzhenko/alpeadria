<?php

/**
 * Search form
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 */

function tfuse_search($atts)
{
    extract(shortcode_atts(array('title'=>''), $atts));

    if( !empty($title) ) $title =  __('SEARCH WIDGET' ,'tfuse') . ':';
   $output = '';
   $output .= '<!-- search widget -->
        <div class="widget-container widget_search">
            <h3>' . $title . '</h3>
             <form method="get" id="searchform" action="' . home_url('/') . '">
                <div>
                    <label class="screen-reader-text" for="s">' . $title . '</label>
                    <input class="inputField" name="s" id="s" value=\'' . tfuse_options('search_box_text') . '\'  onfocus="if (this.value == \'' . tfuse_options('search_box_text') . '\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'' . tfuse_options('search_box_text') . '\';}" type="text">
                    <input id="searchsubmit" class="btn-submit" value="' . __('Submit', 'tfuse') .'" type="submit">
                    </div>
            </form>
        </div>
        <!--/ search widget -->';
    return $output;
}

$atts = array(
    'name' => __('Search', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Title', 'tfuse'),
            'desc' => '',
            'id' => 'tf_shc_search_title',
            'value' => 'SEARCH WIDGET:',
            'type' => 'text'
        ),
    )
);

tf_add_shortcode('search', 'tfuse_search', $atts);
