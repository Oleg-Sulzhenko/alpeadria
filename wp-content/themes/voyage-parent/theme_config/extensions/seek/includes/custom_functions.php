<?php
if (!defined('TFUSE'))
    exit('Direct access forbidden.');

// Include extra features
require_once 'post_attachmets/POST_ATTACHMENTS.php';

// Other user's custom functions and classes
//

/**
 * Ajax action for input-text-location autocompleter
 */
function tf_action_ajax_seek_location_autocomplete() {
    global $wpdb, $TFUSE;

    $items_options = TF_SEEK_HELPER::get_items_options();

    if (!( $TFUSE->request->isset_POST('action') || $TFUSE->request->isset_POST('tf_action') )) { // Just to be sure
        die();
    }

    if (!$TFUSE->request->isset_POST('item_id')) {
        die();
    }
    if (!$item_id = trim($TFUSE->request->POST('item_id'))) {
        die();
    }
    if (!isset($items_options[$item_id])) {
        die();
    }
    if (!isset($items_options[$item_id]['sql_generator_options']['search_on']) || $items_options[$item_id]['sql_generator_options']['search_on'] != 'taxonomy') {
        die();
    }
    if (!isset($items_options[$item_id]['sql_generator_options']['search_on_id'])) {
        die();
    }

    $item_options = $items_options[$item_id];

    // search term (term%)
    if (!$TFUSE->request->isset_POST('term')) {
        die();
    }
    if (!($term = trim($TFUSE->request->POST('term')))) {
        die();
    }
    // Replace multiple spaces (no regexp because of utf8)
    while (strpos('  ', $term))
        $term = str_replace('  ', ' ', $term);
    $term = TF_SEEK_HELPER::safe_sql_like($term);
    // Replace spaces with %
    $term = str_replace(' ', '%', $term);
    // Add % to the end to match like prefix
    $term .= '%';

    // prepare WHERE to excule allready autocompleted words (ex: Africa, Moldova, Los Angeles,)
    $without_existing_terms = "";
    if ($TFUSE->request->isset_POST('value') && $values = (string) $TFUSE->request->POST('value')) {

        $exploded = explode(',', $values);
        $counter = 0;
        if (sizeof($exploded)) { // exclude currently typing word (last) if there is more than one words
            array_pop($exploded);
        }
        if (sizeof($exploded)) {
            $sqla = array();

            foreach ($exploded as $search_item) {

                $search_item = trim($search_item);

                if (!$search_item)
                    continue;

                $counter++;

                $sqla[] = $wpdb->prepare("tte.name != %s", $search_item);
            }

            if ($counter) {
                $without_existing_terms = " AND (" . implode(' AND ', $sqla) . ")";
            }
        }
    }

    $sql = "SELECT
        DISTINCT(tte.name) AS name
            FROM " . (TF_SEEK_HELPER::get_db_table_name()) . " as lep
        INNER JOIN " . $wpdb->prefix . "posts AS p               ON p.ID                 = lep.post_id
        LEFT  JOIN " . $wpdb->prefix . "term_relationships AS tr ON tr.object_id         = lep.post_id
        LEFT  JOIN " . $wpdb->prefix . "term_taxonomy AS tt      ON tt.term_taxonomy_id  = tr.term_taxonomy_id
        LEFT  JOIN " . $wpdb->prefix . "terms AS tte             ON tte.term_id          = tr.term_taxonomy_id
            WHERE p.post_status = 'publish'
                AND tte.name    != ''
                AND tt.taxonomy = " . $wpdb->prepare('%s', $item_options['sql_generator_options']['search_on_id']) . "
                AND tte.name LIKE N'" . $term . "'
                " . $without_existing_terms . "
        GROUP BY tte.term_id
        ORDER BY tte.name ASC
        LIMIT 10";
    $rows = $wpdb->get_results($sql, ARRAY_A);

    $result = array();

    if (sizeof($rows)) {
        foreach ($rows as $row) {
            $result[] = htmlentities($row['name'], ENT_QUOTES, 'UTF-8');
        }
    }

    echo json_encode($result);
    die();
}

class TF_SEEK_CUSTOM_FUNCTIONS {

    public static function html_paging($params = array()) {
        ?>

        <div class="pages">
            <span class="manage_title"><?php _e('Page:', 'tfuse'); ?> &nbsp;<strong><?php print($params['curr_page'] ? $params['curr_page'] : 1); ?> <?php _e('of', 'tfuse'); ?> <?php print( $params['max_pages'] ? $params['max_pages'] : 1); ?></strong></span>
            <?php if ($params['curr_page'] - 1 < 1): ?>
                <span class="link_prev">Previous</span>
            <?php else: ?>
                <a href="<?php print(home_url('/') . TF_SEEK_HELPER::get_qstring_without(array(TF_SEEK_HELPER::get_search_parameter('page'))) . (TF_SEEK_HELPER::get_search_parameter('page')) . '=' . ($params['curr_page'] - 1)); ?>" class="link_prev"><?php _e('Previous', 'tfuse'); ?></a>
            <?php endif; ?>
            <?php if ($params['curr_page'] + 1 > $params['max_pages']): ?>
                <span class="link_next">Next</span>
            <?php else: ?>
                <a href="<?php print(home_url('/') . TF_SEEK_HELPER::get_qstring_without(array(TF_SEEK_HELPER::get_search_parameter('page'))) . (TF_SEEK_HELPER::get_search_parameter('page')) . '=' . ($params['curr_page'] + 1)); ?>" class="link_next"><?php _e('Next', 'tfuse'); ?></a>
            <?php endif; ?>
        </div>

        <?php
    }

    public static function html_jump_to_page($params = array()) {
        ?>

        <div class="pages_jump">
            <span class="manage_title"><?php _e('Jump to page:', 'tfuse'); ?></span>
            <form action="<?php print(home_url('/')); ?>" method="get">
                <input type="hidden" name="s" value="~">
                <?php
                TF_SEEK_HELPER::print_all_hidden(array(TF_SEEK_HELPER::get_search_parameter('page')));
                ?>
                <?php
                $step = 14;
                if ($params['curr_page'] + $step > $params['max_pages']) {
                    $jump_to = ($params['max_pages'] ? $params['max_pages'] : 1);
                } else {
                    $jump_to = $params['curr_page'] + $step;
                }
                ?>
                <input type="text" name="<?php print( TF_SEEK_HELPER::get_search_parameter('page')); ?>" value="<?php print($jump_to); ?>" class="inputSmall"><input type="submit" class="btn-arrow" value="Go">
            </form>
        </div>

        <?php
    }

    public static function orderby($options, $params = array()) {
        ?>
        <?php
        ?>
        <form action="<?php print(home_url('/')); ?>" method="get" class="form_sort">
            <?php
            if (is_page(128)) {
                print ('<input type="hidden" name="page_id" value = "128" />');
            } else {
                print('<input type="hidden" name="s" value="~">');
            }
            ?>
            <?php
            TF_SEEK_HELPER::print_all_hidden(array(TF_SEEK_HELPER::get_search_parameter('page'), TF_SEEK_HELPER::get_search_parameter('orderby')));
            ?>
            <span class="manage_title"><?php _e('Sort by:', 'tfuse'); ?></span>
            <select class="select_styled white_select" name="<?php print( TF_SEEK_HELPER::get_search_parameter('orderby')); ?>" id="<?php print($params['select_id']); ?>" onchange="jQuery(this).closest('form').submit();">
                <?php $input_value = TF_SEEK_HELPER::get_input_value(TF_SEEK_HELPER::get_search_parameter('orderby')); ?>
                <?php foreach ($options as $key => $val): ?>
                    <option value="<?php print(esc_attr($key)); ?>" <?php print($input_value == $key ? 'selected' : ''); ?> ><?php print(esc_attr($options[$key]['label'])); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php
    }

}

function _filter_tf_seek_post_option_save($value, $params) {
    if (($params['options']['valtype'] == 'date')) {

        $dates = explode('/', $value);
        if (strpos($params['id'], '_from')) {
            if ($dates[0])
                $value = trim($dates[0]);
            else
                $value = '0000-00-00';
        }elseif (strpos($params['id'], '_to')) {
            if ($dates[1])
                $value = trim($dates[1]);
            else
                $value = '0000-00-00';
        }
    }

    return $value;
}

add_filter('tf_seek_post_option_save', '_filter_tf_seek_post_option_save', 10, 2);

function _filter_tf_seek_post_content_tabs($options, $type) {
    if ($type == TF_SEEK_HELPER::get_post_type()) {
        /* Content Tabs */
        $options[] = array(
            'name' => __('Content Tabs', 'tfuse'),
            'id' => TF_THEME_PREFIX . '_content_tabs',
            'type' => 'metabox',
            'context' => 'normal'
        );
        $options[] = array(
            'id' => TF_THEME_PREFIX . '_content_tabs_table',
            'btn_labels' => array('Add Tab', 'Delete Tab'),
            'class' => 'tf-post-table',
            'style' => '',
            'default_value' => array(
                'tab_title' => '',
                'tab_content' => ''
            ),
            'value' => array(
                array(
                    'tab_title' => '',
                    'tab_content' => ''
                )
            ),
            'type' => 'div_table',
            'columns' => array(
                array(
                    'id' => 'tab_title',
                    'type' => 'text',
                    'properties' => array('placeholder' => __('Tab name', 'tfuse'))
                ),
                array(
                    'id' => 'tab_content',
                    'value' => '',
                    'type' => 'textarea',
                    'properties' => array('placeholder' => __('Tab content', 'tfuse'))
                )
            )
        );
    }
    return $options;
}

add_filter('tfuse_options_filter', '_filter_tf_seek_post_content_tabs', 10, 2);

function _remove_non_available_offers() {
    if (date("Y/m/d") === get_option('tfuse_last_check_for_remove_non_available_offers'))
        return;

    $args = array(
        'post_type' => TF_SEEK_HELPER::get_post_type(),
        'post_per_page' => -1
    );
    $query = new WP_Query($args);

    while ($query->have_posts()):
        $query->next_post();
        $id = $query->post->ID;
        $dates = TF_SEEK_HELPER::get_post_option('property_availability_from', false, $id);
        if (!$dates)
            continue;
        $dates = explode('/', $dates);
        $end = $dates[1];
        if (date("Y/m/d") > date("Y/m/d", strtotime($end))) {
            $query->post->post_status = 'draft';
            wp_update_post($query->post);
        }

    endwhile;

    update_option('tfuse_last_check_for_remove_non_available_offers', date("Y/m/d"));
}

add_action('admin_init', '_remove_non_available_offers', 10, 0);
add_action('init', '_remove_non_available_offers', 10, 0);

if (!function_exists('tfuse_add_additional_variables_in_admin_js')) :

    /**
     *
     *
     * To override tfuse_add_additional_variables_in_admin_js() in a child theme, add your own tfuse_add_additional_variables_in_admin_js()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function tfuse_add_additional_variables_in_admin_js() {
        global $TFUSE;
        $TFUSE->include->js_enq('seek_post_type', TF_SEEK_HELPER::get_post_type());
        $TFUSE->include->js_enq('currency_symbol', TF_SEEK_HELPER::get_option('currency_symbol', '$'));
        $TFUSE->include->js_enq('regular_price_suffix', TF_SEEK_HELPER::get_option('seek_property_regular_price_suffix', __(' /day', 'tfuse')));
    }

    add_action('admin_footer', 'tfuse_add_additional_variables_in_admin_js');

endif;

add_action('admin_init', '_remove_non_available_offers', 10, 0);
add_action('init', '_remove_non_available_offers', 10, 0);

function tfuse_remove_filter_seek_setup_options() {
    global $TFUSE;
    remove_filter('tf_filter_seek_setup_options', array(&$TFUSE->ext->seek, 'filter_seek_setup_options'), 10);
}

add_action('tf_ext_seek_init_end', 'tfuse_remove_filter_seek_setup_options');
