<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


/**
 * User defined custom taxonomies
 */
global $seek_post_type;
$post_type = TF_SEEK_HELPER::get_post_type();
$seek_post_type = $post_type;

if (!function_exists('tfuse_holidays_comments')) :
    /**
     *
     *
     * To override tfuse_holidays_comments() in a child theme, add your own tfuse_holidays_comments()
     * to your child theme's file.
     */
    add_action('init', 'tfuse_holidays_comments', 99);

    function tfuse_holidays_comments ()
    {
            $supports = array( 'title', 'editor', 'comments' );
            add_post_type_support( TF_SEEK_HELPER::get_post_type(), $supports );
    }
endif;

register_taxonomy($post_type . '_category', array($post_type), array(
    'hierarchical'  => true,
    'labels'        => array(
        'name'                      => __('Категорії','tfuse'),
        'singular_name'             => __('Категорії','tfuse'),
        'search_items'              => __('Шукати Категорію','tfuse'),
        'all_items'                 => __('Всі Категорії','tfuse'),
        'parent_item'               => __('Parent Category','tfuse'),
        'parent_item_colon'         => __('Parent Category:','tfuse'),
        'edit_item'                 => __('Редагувати Категорії','tfuse'),
        'update_item'               => __('Обновини Категорії','tfuse'),
        'add_new_item'              => __('Додати Категорію','tfuse'),
        'new_item_name'             => __('Нове імя Категорії','tfuse'),
        'choose_from_most_used'     => __('Choose from the most used categories','tfuse'),
        'separate_items_with_commas'=> __('Separate categories with commas','tfuse')
    ),
    'show_ui'       => true,
    'query_var'     => true
));
add_action( $post_type . '_category_add_form', 'tfuse_taxonomy_redirect_note_form_3',10);
register_taxonomy($post_type . '_tag', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => ucwords($post_type) .' '. __('Tags', 'tfuse' ),
            'singular_name' => ucwords($post_type) .' '. __('Tag', 'tfuse' ),
            'search_items' =>  __('Search','tfuse') .' '. ucwords($post_type) .' '.__('Tags','tfuse'),
            'all_items' => __('All','tfuse') .' '. ucwords($post_type) .' '. __('Tags','tfuse'),
            'edit_item' => __('Edit','tfuse') .' '. ucwords($post_type) .' '. __('Tag','tfuse'),
            'update_item' => __('Update','tfuse') .' '. ucwords($post_type) .' '. __('Tag','tfuse'),
            'add_new_item' => __('Add New Tag','tfuse' ),
            'new_item_name' => __('New','tfuse') .' '. ucwords($post_type) .' '. __('Tag Name','tfuse'),
            'menu_name' => __('Tags','tfuse'),
        ),
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => $post_type . '_tag', 'with_front' => true),
    )
);
register_taxonomy($post_type . '_locations', array($post_type), array(
    'hierarchical'  => true,
    'labels'        => array(
        'name'                      => __('Країни','tfuse'),
        'singular_name'             => __('Країни','tfuse'),
        'search_items'              => __('Шукати Країну','tfuse'),
        'all_items'                 => __('Всі Країни','tfuse'),
        'parent_item'               => __('Parent Location','tfuse'),
        'parent_item_colon'         => __('Parent Location:','tfuse'),
        'edit_item'                 => __('Редагувати Країни','tfuse'),
        'update_item'               => __('Обновити Країни','tfuse'),
        'add_new_item'              => __('Додати Країну','tfuse'),
        'new_item_name'             => __('Нове імя Країни','tfuse'),
        'choose_from_most_used'     => __('Виберіть напопулярніші Країни','tfuse'),
        'separate_items_with_commas'=> __('Separate location with commas','tfuse')
    ),
    'show_ui'       => true,
    'query_var'     => true
));
add_action( $post_type . '_locations_add_form', 'tfuse_taxonomy_redirect_note_form_1',10);
function tfuse_taxonomy_redirect_note_form_1($taxonomy){
    global $seek_post_type;
    $taxonomy = str_replace($seek_post_type . '_','',$taxonomy);
    $taxonomy = substr_replace($taxonomy ,"",-1);
    $taxonomy = mb_ucfirst($taxonomy);
    echo '<p><strong>Note:</strong> More options are available after you add the '.$taxonomy.'. <br />
        Click on the Edit button under the '.$taxonomy.' name.</p>';
}
function tfuse_taxonomy_redirect_note_form_2($taxonomy){
    global $seek_post_type;
    $taxonomy = str_replace($seek_post_type . '_','',$taxonomy);
    $taxonomy = substr_replace($taxonomy ,"",-3);
    $taxonomy .= 'y';
    $taxonomy = mb_ucfirst($taxonomy);
    echo '<p><strong>Note:</strong> More options are available after you add the '.$taxonomy.'. <br />
        Click on the Edit button under the '.$taxonomy.' name.</p>';
}
function tfuse_taxonomy_redirect_note_form_3($taxonomy){
    global $seek_post_type;
    $taxonomy = str_replace($seek_post_type . '_','',$taxonomy);
    $taxonomy = mb_ucfirst($taxonomy);
    echo '<p><strong>Note:</strong> More options are available after you add the '.$taxonomy.'. <br />
        Click on the Edit button under the '.$taxonomy.' name.</p>';
}