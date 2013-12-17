<?php

/**
 * Blog Entries
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 * 
 * Optional arguments:
 * items:
 * title:
 * View All Title:
 */

function tfuse_blog_entries($atts, $content = null)
{
    extract(shortcode_atts(array(
                                'items' => 3,
                                'title' => '',
                                'alltitle' => '',
                                'category' => ''
                           ), $atts));
    $latest_posts = tfuse_shortcode_posts(array(
                                            'sort' => 'recent',
                                            'items' => $items,
                                            'category' => $category,
                                            'image_post' => true,
                                            'image_width' => 219,
                                            'image_height' => 156,
                                            'date_post' => true,
                                            'date_format' => 'D, M j, y',
                                            'excerpt_length' => 46,
                                        ));
    if ($title != '')
    {
        $title = '<em>' . __($title,'tfuse') . '</em>';
    }
    $category_id = get_cat_ID($category);

    if ($alltitle != '')
    {
        $alltitle = '<a href="' . get_category_link($category_id) . '" class="link-more2">' . $alltitle . ' >></a>';
    }
    $falseAllTitle = '';
    $return_html = '';
    $i = 0;
    $k = count($latest_posts);
    foreach ($latest_posts as $post_val):
        $i++;
        if($i>1) $title = '';
        if ($i == $k) $falseAllTitle = $alltitle;
        $return_html .= '<div class="post-item">
        	<div class="post-title"><h2>' . $title . '<a href="' . $post_val['post_link'] . '"> ' . $post_val['post_title'] . '</a></h2></div>
        	<div class="post-image">' . $post_val['post_img'] . '</div>
       	  	<div class="post-short">
   	  	  		<div class="post-meta-top"><span class="meta-date">' . $post_val['post_date_post'] . '</span> ' . __('Posted by', 'tfuse') . ': <span class="author">' . $post_val['post_author_name'] . '</span></div>
            	<div class="post-descr">
                <p>' . $post_val['post_excerpt'] . '</p>
                </div>
                <div class="post-meta-bot">
                    <a href="' . $post_val['post_link'] . '" class="link-more">' . __('Continue reading', 'tfuse') . ' >></a>' . $falseAllTitle . '
                </div>
            </div>
            <div class="clear"></div>
        </div>';
    endforeach;
    return $return_html;
}

$atts = array(
    'name' => __('Blog Entries', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Items', 'tfuse'),
            'desc' => __('Specifies the number of the post to show.', 'tfuse'),
            'id' => 'tf_shc_blog_entries_items',
            'value' => '5',
            'type' => 'text'
        ),
        array(
            'name' => __('Category', 'tfuse'),
            'desc' => __('Specify the category from where to extract posts.', 'tfuse'),
            'id' => 'tf_shc_blog_entries_category',
            'value' => '',
            'type' => 'text'
        ),
        array(
            'name' => __('Title', 'tfuse'),
            'desc' => __('Specifies the title for an shortcode.', 'tfuse'),
            'id' => 'tf_shc_blog_entries_title',
            'value' => 'Recent Posts',
            'type' => 'text'
        ),
        array(
            'name' => __('View all text', 'tfuse'),
            'desc' => __('Specify the text will be showed after the last post to render them all in this category.', 'tfuse'),
            'id' => 'tf_shc_blog_entries_alltitle',
            'value' => 'View All',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('blog_entries', 'tfuse_blog_entries', $atts);
