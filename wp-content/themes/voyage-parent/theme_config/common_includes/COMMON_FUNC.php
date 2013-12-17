<?php
/**
 * Functions to select homepage category
 *
 * @since Voyage 1.0
 */


if (!function_exists('tfuse_get_property_terms')) :
    /**
     *
     *
     * To override tfuse_get_property_terms() in a child theme, add your own tfuse_get_property_terms()
     * to your child theme's file.
     */

    function tfuse_get_property_terms( $taxonomy) {

        global $wpdb;
        $SQL = "SELECT $wpdb->terms.term_id, $wpdb->terms.name
            FROM  $wpdb->terms INNER JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
            WHERE $wpdb->term_taxonomy.taxonomy = '" . $taxonomy ."'";

        $results =  $wpdb->get_results($SQL);
        return $results;

    }
endif;

if (!function_exists('tfuse_list_page_options')) :
    function tfuse_list_page_options() {
        $pages = get_pages();
        $result = array();
        $result[0] = 'Select a page';
        foreach ( $pages as $page ) {
            $result[ $page->ID ] = $page->post_title;
        }
        return $result;
    }
endif;