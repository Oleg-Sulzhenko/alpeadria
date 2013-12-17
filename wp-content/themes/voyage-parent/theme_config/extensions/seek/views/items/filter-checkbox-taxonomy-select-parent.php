<?php
global $TFUSE;

$input_value    = TF_SEEK_HELPER::get_input_value($parameter_name,'');
if (isset($vars['listen_items']))
{
    if(is_array($vars['listen_items']))
    {
        foreach($vars['listen_items'] as $item)
        {
            $tmp_input_value    = TF_SEEK_HELPER::get_input_value($item,'');
            if($tmp_input_value)
            {
                $input_value = $tmp_input_value; break;
            }
        }
    }
    else
    {
        $input_value    = TF_SEEK_HELPER::get_input_value($vars['listen_items'],'');
    }
}
$input_values   = explode(';', $input_value);
if(sizeof($input_values)){
    $tmp = array();
    foreach($input_values as $value){
        if(is_numeric($value)){
            $tmp[] = intval($value);
        }
    }
    $input_values = $tmp;
}

$all_items      = TF_SEEK_HELPER::get_items_options();
if(!isset($all_items[ @$settings['parent_item_id'] ])){
    echo 'Wrong "parent_item_id" supplied in settings options for item with id = '.$item_id;
    return;
}

$parent_item        = $all_items[ $settings['parent_item_id'] ];
$parent_input_value = intval(TF_SEEK_HELPER::get_input_value($parent_item['parameter_name'],''));

if(!isset($vars['label'])){
    $vars['label'] = $parent_item['template_vars']['label'];
}

$only_top_level = 'child_of='.$parent_input_value;
if(isset($settings['only_top_level']) && $settings['only_top_level']) $only_top_level = 'parent=' . $parent_input_value;

$list = get_terms($parent_item['sql_generator_options']['search_on_id'], 'hide_empty=0&'.$only_top_level);

if(!sizeof($list) || is_wp_error($list)) {
    if($TFUSE->request->isset_GET($parameter_name))echo '<input type="hidden" name="' . $parameter_name . '" value="' . esc_attr($TFUSE->request->GET($parameter_name)) . '">'; return;
}

$valid_hidden   = array();
$check_options  = array();
foreach($list as $term){
    if(in_array($term->term_id, $input_values)){
        $valid_hidden[] = $term->term_id;
    }

    $check_options[$term->term_id] = array(
        'name'      => $term->name,
        'checked'   => in_array($term->term_id, $input_values),
        'count'     => 0
    );
}

if(@$vars['show_counts']){
    global $wpdb;

    if(sizeof($check_options)){
        $select_counts  = '';
        $inner_options  = '@incrementer:=0';
        $counter        = 0;
        foreach($check_options as $id=>$option){
            $select_counts .= ",\n" . "IF( options._terms REGEXP '(^|".TF_SEEK_HELPER::get_index_table_terms_separator().")+" . $id . TF_SEEK_HELPER::get_index_table_terms_separator() . "'";
            $select_counts .= ", @counter".$id.":=@counter".$id."+1, @counter".$id." ) AS c".$id;

            $inner_options .= ", @counter".$id.":=0";

            $counter++;
        }

        $search_sql = TF_SEEK_HELPER::get_search_sql(array(
                'noJoins'   => true,
                'noWhere'   => true,
            ));
        $search_sql .= "
        INNER JOIN " . $wpdb->prefix . "posts AS p ON p.ID = options.post_id
        INNER JOIN (SELECT ". $inner_options .") as _fake_table";
        $search_sql .= TF_SEEK_HELPER::get_search_sql(array(
                'noFrom'    => true,
                'noJoins'   => true,
            ));;

        if(@$vars['listen_form_id']){
            
            $parent_form_id = $TFUSE->request->GET(TF_SEEK_HELPER::get_search_parameter('form_id'));
            $forms_options = TF_SEEK_HELPER::get_forms_options();
            if(!isset($forms_options[$parent_form_id])) $parent_form_id = $vars['listen_form_id'];

            $sql_where  = TF_SEEK_HELPER::build_form_search_where_sql($parent_form_id, array($parameter_name) );

            if(trim($sql_where['sql'])){
                $search_sql .= " AND ".$sql_where['sql'];
            }
        }

        $search_sql = "SELECT
            (@incrementer:=@incrementer+1) AS incrementator" . $select_counts . "
            " . $search_sql . "
            ORDER BY incrementator DESC LIMIT 1";

        $sql_results = $wpdb->get_row($search_sql);

        if(sizeof($sql_results)){
            foreach($sql_results as $key=>$val){
                $temp   = explode('c', $key);
                $check_option_id = intval(array_pop( $temp ));
                if(isset($check_options[ $check_option_id ])){
                    $check_options[ $check_option_id ]['count'] = $val;
                }
            }
        }
    }
}

?>
<div class="row input_styled checklist">
    <label class="label_title2"><?php print($vars['label']); ?>:</label>
    <input type="hidden" name="<?php print($parameter_name); ?>" value="<?php print( implode(';', $valid_hidden) ); ?>" id="sopt-seek-<?php print($item_id); ?>" />
    <?php foreach($check_options as $option_id=>$option): ?>
    <input type="checkbox" onchange="tf_seek_update_hidden_check_<?php print(preg_replace('/[^a-z0-9]/iU', '_', $item_id)); ?>();" class="sopt-seek-<?php print($item_id); ?>" id="sopt-seek-<?php print($item_id); ?>-<?php print $option_id; ?>" value="<?php print $option_id; ?>" <?php print($option['checked'] ? 'checked' : '') ?> /> <label for="sopt-seek-<?php print($item_id); ?>-<?php print($option_id); ?>"><?php print esc_attr(tfuse_qtranslate($option['name'])); ?><?php print( @$vars['show_counts'] ? ' ('.$option['count'].$vars['counts_label_'.($option['count']==1 ? 'singular' : 'plural')].')' : '' ); ?></label>
    <?php endforeach; ?>
</div>
<script type="text/javascript" >
    function getURLParameter(name) {
        return decodeURI(
                (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
        );
    }
    function tf_seek_update_hidden_check_<?php print(preg_replace('/[^a-z0-9]/iU', '_', $item_id)); ?>(){
        var $ = jQuery;
        var default_val = getURLParameter('<?php print($parameter_name); ?>');
        if(default_val=='null') default_val=0;
        var values = [];
        $(".sopt-seek-<?php print($item_id); ?>").each(function(){
            if($(this).is(':checked')){
                var value = parseInt($(this).val());
                if(-1 == values.indexOf(value)){
                    values.push(value);
                }
            }
        });
        if(!values.length) values.push(default_val);
        values = values.join(';');
        $("#sopt-seek-<?php print($item_id); ?>").val(values);
    }
    tf_seek_update_hidden_check_<?php print(preg_replace('/[^a-z0-9]/iU', '_', $item_id)); ?>();
</script>