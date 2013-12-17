<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


/**
 * Object with static Funtions that return pieces of sql (for WHERE) with data from search inputs for main seek search sql
 */

/**
 * Function Structure:
 * public static function <Function-Name>($item_id, $sql_options, $settings, $template_vars, $all_item_options)
 *
 * return: array(
 *      'sql' => '...',  // Please, create safe sql!
 * )
 */
class TF_SEEK_SQL_GENERATORS {

    // Convert "<number1>;<number2>" to "<number1> <= <field> AND <number2> >= <filed>"
    public static function range_slider($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb, $TFUSE;

        $value  = TF_SEEK_HELPER::get_input_value($parameter_name);

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        do {
            if(!$value) break;

            $value  = explode(';', $value);

            // if( sizeof($value)!=2 ) break;

            if( !is_numeric(@$value[0]) || !is_numeric(@$value[1]) ) break;
            $value[0] = intval(@$value[0]);
            $value[1] = intval(@$value[1]);

            if($value[1] < $value[0]){
                $value[1] = $value[0];
            }

            if(@$settings['auto_options']){

                $settings['auto_steps'] = intval(isset($settings['auto_steps']) ? $settings['auto_steps'] : 2);

                $append_sql_where = "";
                if(isset($template_vars['listen_checkbox_id'])){
                    $items_options = TF_SEEK_HELPER::get_items_options();

                    if(!isset($items_options[$template_vars['listen_checkbox_id']])) die('Undefined item id "'.esc_attr($template_vars['listen_checkbox_id']).'" in "listen_checkbox_id" option');

                    $listen_item = $items_options[$template_vars['listen_checkbox_id']];

                    $_GET_backup = $TFUSE->request->GET($listen_item['parameter_name']);
                    $TFUSE->request->GET($listen_item['parameter_name'], (TF_SEEK_HELPER::get_input_value($listen_item['parameter_name']) == $listen_item['settings']['max'] ? $listen_item['settings']['max'] : $listen_item['settings']['min']));

                    $append_sql_where = TF_SEEK_SQL_GENERATORS::$listen_item['sql_generator']($template_vars['listen_checkbox_id'], $listen_item['parameter_name'], $listen_item['sql_generator_options'], $listen_item['settings'], $listen_item['template_vars'], $listen_item);
                    $append_sql_where = ($append_sql_where['sql'] ? " AND ".$append_sql_where['sql'] : "");

                    $TFUSE->request->GET($listen_item['parameter_name'], $_GET_backup);
                    unset($_GET_backup);
                }

                $col_name   = trim($wpdb->prepare('%s', $sql_options['search_on_id']), "'");
                $db_min_max = $wpdb->get_row( "SELECT
                    MAX(". $col_name .") as max
                        FROM ". trim($wpdb->prepare('%s', TF_SEEK_HELPER::get_db_table_name()), "'") ." options
                    INNER JOIN " . $wpdb->prefix . "posts AS p ON p.ID = options.post_id
                        WHERE p.post_status = 'publish' " . $append_sql_where . "
                    LIMIT 1", ARRAY_A);

                if(!sizeof($db_min_max)) break;

                $db_min_max['min'] = 0; //...

                if($db_min_max['min'] == $db_min_max['max']) break;

                $diff = $db_min_max['max']-$db_min_max['min'];
                if($diff < $settings['auto_steps']) break;

                if(isset($settings['step'])){
                    if($db_min_max['max'] % $settings['step']){
                        $db_min_max['max'] = $db_min_max['max'] - ($db_min_max['max'] % $settings['step']) + $settings['step'];
                    }
                }

                $settings['from']   = $db_min_max['min'];
                $settings['to']     = $db_min_max['max'];
            }

            $sql    = array();

            if( (int)$value[1] >= $settings['to'] ){
                $sql[] = "( {$sql_options['search_on']}.{$sql_options['search_on_id']} >= {$wpdb->prepare('%d', $value[0])} )";
            } else {
                $sql[] = "( {$sql_options['search_on']}.{$sql_options['search_on_id']} >= {$wpdb->prepare('%d', $value[0])} )";
                $sql[] = "( {$sql_options['search_on']}.{$sql_options['search_on_id']} <= {$wpdb->prepare('%d', $value[1])} )";
            }

            $sql = implode(" AND ", $sql);

            $result['sql'] = $sql;
        } while(false);

        return $result;
    }

    // convert "<name1>, <name2>, ..." to "<fieldX>='<some-value> AND (<field> LIKE(<name1>%) OR <field> LIKE(<name1>% OR ...) )'"
    public static function taxonomy_multivalue($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $value  = TF_SEEK_HELPER::get_input_value($parameter_name);

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        do {
            $sql = "(";

            $exploded   = explode(',', $value);
            $counter    = 0;
            if( sizeof($exploded) ){
                $sql_like = array();

                foreach( $exploded as $search_item ){

                    $search_item = trim($search_item);

                    if(!$search_item) continue;

                    $counter++;

                    // Replace multiple spaces (no regexp because of utf8)
                    while (strpos('  ', $search_item)) $search_item = str_replace('  ', ' ', $search_item);

                    // safe
                    $search_item = TF_SEEK_HELPER::safe_sql_like($search_item);

                    // Replace spaces with %
                    $search_item = str_replace(' ', '%', $search_item);

                    $sql_like[]  = "({$sql_options['search_on']}_terms.name LIKE N'$search_item%')";
                }

                if($counter){
                    $sql .= "(".implode(' OR ', $sql_like).")";
                } else {
                    break;
                }
            }

            $sql .= ")";
            if(!$counter) $sql = '';

            $result['in_taxonomy']  = $sql_options['search_on_id'];
            $result['sql']          = $sql;
        }while(false);
        return $result;
    }

    public static function date($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options)
    {
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $searched_time = strtotime(TF_SEEK_HELPER::get_input_value($parameter_name));
        $emty_time = strtotime('0000-00-00');
        if( (!$searched_time) || ($searched_time == -1)) return $result;

        if($sql_options['relation'] == '>='){
            $result['sql'] = '( options.seek_property_' . $sql_options['search_on'] . ' ' . $sql_options['relation'] . '\'' . date('Y-m-d\TH:i:sO', $searched_time) . '\') or ( options.seek_property_' . $sql_options['search_on'] . ' = \'0000-00-00\' )';
        }else $result['sql'] = 'options.seek_property_' . $sql_options['search_on'] . ' ' . $sql_options['relation'] . '\'' . date('Y-m-d\TH:i:sO', $searched_time) . '\'';
                //$result['sql'] = '';
        return $result;
    }
    // Simple int option
    public static function option_int($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        do{
            $value  = intval(TF_SEEK_HELPER::get_input_value($parameter_name));
            if($value < $settings['min'] || $value > $settings['max'] || ( $value==0 && isset($sql_options['skip_zero']) ) ){
                break;
            }

            $relation = $sql_options['relation'];
            if( $value >= $settings['max'] && isset($sql_options['relation_max']) ){
                $relation = $sql_options['relation_max'];
            } elseif( $value <= $settings['min'] && isset($sql_options['relation_min']) ){
                $relation = $sql_options['relation_min'];
            }

            $sql = " {$sql_options['search_on']}.{$sql_options['search_on_id']} {$relation} {$value} ";

            $result['sql'] = $sql;
        }while(false);

        return $result;
    }

    // Simple int options (ex:'1;4;7')
    public static function options_int($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $values = explode(';', TF_SEEK_HELPER::get_input_value($parameter_name));
        $sqls   = array();
        if(sizeof($values)){
            foreach($values as $value){
                $value = intval($value);

                if($value < $settings['min'] || $value > $settings['max']){
                    continue;
                }

                $relation = $sql_options['relation'];
                if( $value >= $settings['max'] && isset($sql_options['relation_max']) ){
                    $relation = $sql_options['relation_max'];
                } elseif( $value <= $settings['min'] && isset($sql_options['relation_min']) ){
                    $relation = $sql_options['relation_min'];
                }

                $sqls[] = " {$sql_options['search_on']}.{$sql_options['search_on_id']} {$relation} {$value} ";
            }
        }

        if(sizeof($sqls)){
            $result['sql'] = implode(' OR ', $sqls);
        } else {
            $result['sql'] = '';
        }

        return $result;
    }

    // Ex: 1;4 => ... => ( (option >= 1000 AND option <=1500) OR (option >= 2500) )
    public static function intervals_options_int($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $values = explode(';', TF_SEEK_HELPER::get_input_value($parameter_name));
        $sqls   = array();
        if(sizeof($values)){
            foreach($values as $value){
                $value = intval($value);

                if($value < 1 || $value > $settings['max_steps']){
                    continue;
                }

                $interval = array(
                    'from'  => $settings['start'] + ( $settings['step'] * ($value-1) ),
                    'to'    => $settings['start'] + ( $settings['step'] * ($value) ) - 1
                );

                if($value>=$settings['max_steps'] && @$settings['max_unlimited']){
                    $sqls[] = " {$sql_options['search_on']}.{$sql_options['search_on_id']} >= {$interval['from']} ";
                } else {
                    $sqls[] = " ( {$sql_options['search_on']}.{$sql_options['search_on_id']} >= {$interval['from']} AND {$sql_options['search_on']}.{$sql_options['search_on_id']} <= {$interval['to']} ) ";
                }
            }
        }

        if(sizeof($sqls)){
            $result['sql'] = implode(' OR ', $sqls);
        } else {
            $result['sql'] = '';
        }

        return $result;
    }

    public static function favorites($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb, $TFUSE;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        do{
            $value  = TF_SEEK_HELPER::get_input_value($parameter_name);
            if($value === NULL) break;

            $cookieFavorites    = (!$TFUSE->request->empty_COOKIE('favorite_posts')) ? $TFUSE->request->COOKIE('favorite_posts') : '';
            $cookieValues       = explode(',', $cookieFavorites);
            $validValues        = array();
            foreach($cookieValues as $val){
                $val = intval(trim($val));

                if($val<1) continue;

                $validValues[ $val ] = $val;
            }

            if(!sizeof($validValues)) $validValues[0] = 0;

            $stringValidValues = implode(',', array_keys($validValues));

            $result['sql'] = 'p.ID IN (' . $stringValidValues . ')';

            /// $validValues contains only valid/clean/unique values of properties ids
            /// so replace this clean values in cookie
            /// uncoment an correct this:
            // set_cookie('favorite_posts', $stringValidValues);
        }while(false);

        return $result;
    }

    // taxonomy %taxonomy%
    public static function taxonomy_univalue($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $value  = TF_SEEK_HELPER::get_input_value($parameter_name);

        do {
            $sql = "";

            $search_item = trim($value);

            if(!$search_item) continue;

            if(mb_strlen($search_item, 'UTF-8') < 2) continue; // minimum length

            // Replace multiple spaces (no regexp because of utf8)
            while (strpos('  ', $search_item)) $search_item = str_replace('  ', ' ', $search_item);

            // safe
            $search_item = TF_SEEK_HELPER::safe_sql_like($search_item);

            // Replace spaces with %
            $search_item = str_replace(' ', '%', $search_item);

            $sql_like  = "{$sql_options['search_on']}_terms.name LIKE N'%$search_item%'";

            $sql .= $sql_like;

            $result['in_taxonomy']  = $sql_options['search_on_id'];
            $result['sql']          = $sql;
        }while(false);

        return $result;
    }

    // search by taxonomy parent_id and his childs
    public static function taxonomy_parent($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        do {
            $sql = "";

            $is_child = false;
            if(isset($settings['parent_item_id'])){

                $all_items      = TF_SEEK_HELPER::get_items_options();
                if(!isset($all_items[ @$settings['parent_item_id'] ])) break;

                $parent_item    = $all_items[ $settings['parent_item_id'] ];

                $is_child = true;

                $child__parameter_name  = $parameter_name;
                $child__settings        = $settings;
                $child__sql_options     = $sql_options;

                $parameter_name         = $parent_item['parameter_name'];
                $settings               = $parent_item['settings'];
                $sql_options            = $parent_item['sql_generator_options'];
            }

            $value  = TF_SEEK_HELPER::get_input_value($parameter_name);

            if(!is_numeric($value)) break;

            $value = intval( $value );

            // Check if this taxonomy exists
            if(!term_exists($value, $sql_options['search_on_id'], $settings['select_parent'])) break;

            $search_terms = array($value);

            $list = get_terms($sql_options['search_on_id'], 'hide_empty=0&fields=ids&child_of='.$value);

            if(sizeof($list)){

                if($is_child){
                    $child_input_value    = TF_SEEK_HELPER::get_input_value($child__parameter_name,'');
                    $child_input_values   = explode(';', $child_input_value);

                    // Check if input values has at least one valid id, else ignore it as it is wrong
                    $is_valid_child_values= false;
                    foreach($list as $term){
                        if(in_array($term, $child_input_values)){
                            $is_valid_child_values = true;
                            $search_terms = array(); // remove parent from search
                            break;
                        }
                    }
                }

                foreach($list as $term){
                    if($is_child && $is_valid_child_values){
                        if(!in_array($term, $child_input_values)){
                            continue;
                        }
                    }

                    $search_terms[] = $term;
                }
            }

            $result['in_taxonomy_ids'] = implode(',', $search_terms);

            //$result['in_taxonomy']  = $sql_options['search_on_id'];
            $result['sql']          = $sql;
        }while(false);

        return $result;
    }

    public static function taxonomy_multi_ids($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        do{
            $sql = '';

            $value       = TF_SEEK_HELPER::get_input_value($parameter_name);
            if(!$value) break;

            $values      = explode(';', (string)$value );

            // cleanup input
            $old_values  = $values;
            $values      = array();
            if(sizeof($old_values)){
                foreach($old_values as $id){
                    if(1 > ( $id = intval($id)) ) continue;

                    $values[ $id ] = '~';
                }
            }
            $values = array_keys($values);
            unset($old_values);

            if(!sizeof($values)) break;

            $terms = get_terms($sql_options['search_on_id'], 'hide_empty=0&' . (@$template_vars['get_terms_args']) . '&fields=ids' . '&include='.implode(',', $values) );
            if(!sizeof($terms)) break;

            $result['in_taxonomy_ids'] = implode(',', $terms);

            //$result['in_taxonomy']  = $sql_options['search_on_id'];
            $result['sql']          = $sql;

        }while(false);

        return $result;
    }

    public static function promos($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb, $TFUSE;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        if ($TFUSE->request->isset_GET('promos'))
        {
            $sql = 'options.seek_property_reduction <> 0';
            $result['sql'] = $sql;
        }

        return $result;
    }

    public static function single_select($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $sql = '';
        $value       = intval(TF_SEEK_HELPER::get_input_value($parameter_name));
        if(!$value) return $result;

        $sql .= "options." . $sql_options['search_on_field'] . " REGEXP '((^|,)+" . (string)$value . ",)'";

        $result['sql'] = $sql;
        return $result;
    }

    public static function taxonomy_multi_ids_without_framework($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );
        if(!empty($sql_options['intern_relation']) && ($sql_options['intern_relation']=='AND')) $intern_relation = 'AND'; else $intern_relation = 'OR';
        do{
            $sql = '';

            $value       = TF_SEEK_HELPER::get_input_value($parameter_name);
            if(!$value) break;

            $values      = explode(';', (string)$value );

            // cleanup input
            $old_values  = $values;
            $values      = array();

            if(sizeof($old_values)){
                foreach($old_values as $id){
                    if(1 > ( $id = intval($id)) ) continue;

                    $values[] = $id;
                }
            }
            unset($old_values);

            $len = count($values);
            if(sizeof($values)){
                foreach($values as $key => $term){
                    if($key != $len-1){
                        $sql .= " options._terms REGEXP '((^|,)+" . $term . ",)' " . $intern_relation;
                    }else{
                        $sql .= " options._terms REGEXP '((^|,)+" . $term . ",)' ";
                    }

                }
            }
            $result['sql']          = $sql;

        }while(false);

        return $result;
    }
}
