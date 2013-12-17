<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

$values      = explode(';', TF_SEEK_HELPER::get_input_value($parameter_name,''));

// cleanup input
$old_values  = $values;
$values      = array();
if(sizeof($old_values)){
    foreach($old_values as $id){
        if(0 == ( $id = intval($id)) ) continue;

        $values[ $id ] = '...';
    }
}
$values = array_keys($values);
unset($old_values);

$check_options  = array();
$items = (isset($vars['items'])) ? $vars['items'] : array();
if(sizeof($items)){
    foreach($items as $key => $item){
        $check_options[ $key ] = array(
            'output'    => $item
        );
    }
}

if(!sizeof($check_options)) return;

if(@$vars['show_counts']){
    global $wpdb;

    if(sizeof($check_options)){
        $select_counts  = '';
        $inner_options  = '@incrementer:=0';
        $counter        = 0;
        foreach($check_options as $id=>$option){
            $select_counts .= ",\n" . "IF( options." . $sql_generator_options['search_on_id'] . " = " . $id;
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

            $parent_form_id = $vars['listen_form_id'];

            if($parent_form_id == $form_id){
                die('Error: Item option "listen_form_id" cannot be the same as its parent form');
            }

            $sql_where  = TF_SEEK_HELPER::build_form_search_where_sql('main_search', array($parameter_name) );

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
/*if(@$vars['include_all_option'])
{
    $all_option = array(
        'output'    => $vars['all_label'],
    );
    if(@$vars['show_counts'] && sizeof($sql_results)) $all_option['count'] = $sql_results->incrementator;
    $temp[-1] = $all_option;
    if(@$vars['all_show_top']) $check_options = $temp+ $check_options; else $check_options = $check_options + $temp;
}*/

?>
<div class="row input_styled checklist hide">
    <?php if(isset($vars['label'])): ?><label class="label_title2" ><?php print esc_attr( $vars['label'] ); ?>:</label><br><?php endif; ?>
    <input type="hidden" name="<?php print($parameter_name); ?>" value="<?php print(esc_attr( implode(';', $values) )); ?>" id="sopt-seek-<?php print($item_id); ?>" />
    <?php foreach($check_options as $key=>$val): ?>
    <?php if (!isset($check_options[$key]['count'])) $check_options[$key]['count'] = 0;?>
        <input type="checkbox" name="sopt-seek-<?php print($item_id); ?>-<?php print($key); ?>" onchange="tf_seek_update_hidden_check_<?php print(preg_replace('/[^a-z0-9]/iU', '_', $item_id)); ?>();" class="sopt-seek-<?php print($item_id); ?>" id="sopt-seek-<?php print($item_id); ?>-<?php print($key); ?>" value="<?php print($key); ?>" <?php print( (in_array( $key, $values ) ? 'checked' : '' ) ); ?> /> <label for="sopt-seek-<?php print($item_id); ?>-<?php print($key); ?>"><?php print($check_options[$key]['output']); ?><?php print( @$vars['show_counts'] ? ' ('.$check_options[$key]['count'].$vars['counts_label_'.($check_options[$key]['count']==1 ? 'singular' : 'plural')].')' : '' ); ?></label>
    <?php endforeach; ?>
</div>
<script type="text/javascript" >
    function tf_seek_update_hidden_check_<?php print(preg_replace('/[^a-z0-9]/iU', '_', $item_id)); ?>(){
        var $ = jQuery;

        var values = [];
        $(".sopt-seek-<?php print($item_id); ?>").each(function(){
            if($(this).is(':checked')){
                var value = parseInt($(this).val());
                if(-1 == values.indexOf(value)){
                    values.push(value);
                }
            }
        });
        values = values.join(';');
        $("#sopt-seek-<?php print($item_id); ?>").val(values);
    }
</script>