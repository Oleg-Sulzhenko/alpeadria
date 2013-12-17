<?php
/**
 * Create custom posts types
 *
 * @since Voyage 1.0
 */

if ( !function_exists('tfuse_create_custom_post_types') ) :
    /**
     * Retrieve the requested data of the author of the current post.
     *
     * @param array $fields first_name,last_name,email,url,aim,yim,jabber,facebook,twitter etc.
     * @return null|array The author's spefified fields from the current author's DB object.
     */
    function tfuse_create_custom_post_types()
    {
        // TESTIMONIALS
        $labels = array(
            'name' => __('Testimonials', 'tfuse'),
            'singular_name' => __('Testimonial', 'tfuse'),
            'add_new' => __('Add New', 'tfuse'),
            'add_new_item' => __('Add New Testimonial', 'tfuse'),
            'edit_item' => __('Edit Testimonial', 'tfuse'),
            'new_item' => __('New Testimonial', 'tfuse'),
            'all_items' => __('All Testimonials', 'tfuse'),
            'view_item' => __('View Testimonial', 'tfuse'),
            'search_items' => __('Search Testimonials', 'tfuse'),
            'not_found' =>  __('Nothing found', 'tfuse'),
            'not_found_in_trash' => __('Nothing found in Trash', 'tfuse'),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => true,
            'menu_position' => 5,
            'supports' => array('title','editor')
        );

        register_post_type( 'testimonials' , $args );

        $labels = array(
            'name' => __('Reservation', 'tfuse'),
            'singular_name' => __('Reservation', 'tfuse'),
            'add_new' => __('Add New', 'tfuse'),
            'add_new_item' => __('Add New Reservation', 'tfuse'),
            'edit_item' => __('Edit Reservation info', 'tfuse'),
            'new_item' => __('New Reservation', 'tfuse'),
            'all_items' => __('All Reservations', 'tfuse'),
            'view_item' => __('View Reservation info', 'tfuse'),
            'parent_item_colon' => ''
        );

        $reservationform_rewrite = apply_filters('tfuse_reservationform_rewrite','reservationform_list');

        $res_args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => false,
            'query_var' => true,
            'exclude_from_search'=>true,
            'has_archive' => true,
            'rewrite' => array('slug'=> $reservationform_rewrite),
            'menu_position' => 6,
            'supports' => array(null)
        );

        register_taxonomy('reservations', array('reservations'), array(
            'hierarchical' => true,
            'labels' => array(
                'name' => __('Reservation Forms', 'tfuse'),
                'singular_name' => __('Reservation Form', 'tfuse'),
                'add_new_item' => __('Add New Reservation Form', 'tfuse'),
            ),
            'show_ui' => false,
            'query_var' => true,
            'rewrite' => array('slug' => $reservationform_rewrite)
        ));

        register_post_type( 'reservations' , $res_args );

    }
    tfuse_create_custom_post_types();

endif;

add_action('category_add_form', 'tfuse_taxonomy_redirect_note');



if (!function_exists('tfuse_taxonomy_redirect_note')) :
    /**
     *
     *
     * To override tfuse_taxonomy_redirect_note() in a child theme, add your own tfuse_taxonomy_redirect_note()
     * to your child theme's file.
     */
    function tfuse_taxonomy_redirect_note($taxonomy){
        echo '<p><strong>Note:</strong> More options are available after you add the '.$taxonomy.'. <br />
        Click on the Edit button under the '.$taxonomy.' name.</p>';
    }

endif;