<?php

global $TFUSE;

$values      = explode(';', TF_SEEK_HELPER::get_input_value($parameter_name,''));

// cleanup input
$old_values  = $values;
$values      = array();
if(sizeof($old_values)){
    foreach($old_values as $id){
        if(1 > ( $id = intval($id)) ) continue;

        $values[ $id ] = '...';
    }
}
$values = array_keys($values);
unset($old_values);

$check_options  = array();
$terms          = get_terms($sql_generator_options['search_on_id'], 'hide_empty=0' . (@$vars['get_terms_args']) );
if(sizeof($terms)){
    foreach($terms as $term){
        $check_options[ $term->term_id ] = array(
            'output'    => $term->name
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
    <?php if(isset($vars['label'])): ?><label class="<?php print esc_attr( $vars['label_class'] ); ?>" ><?php print esc_attr( $vars['label'] ); ?>:</label><?php endif; ?>
    <input type="hidden" name="<?php print($parameter_name); ?>" value="<?php print(esc_attr( implode(';', $values) )); ?>" id="sopt-seek-<?php print($item_id); ?>" />
    <?php foreach($check_options as $key=>$val): ?>
    <?php if (!isset($check_options[$key]['count'])) $check_options[$key]['count'] = 0;?>
        <input type="checkbox" name="sopt-seek-<?php print($item_id); ?>-<?php print($key); ?>" onchange="tf_seek_update_hidden_check_<?php print(preg_replace('/[^a-z0-9]/iU', '_', $item_id)); ?>();" class="sopt-seek-<?php print($item_id); ?>" id="sopt-seek-<?php print($item_id); ?>-<?php print($key); ?>" value="<?php print($key); ?>" <?php print( (in_array( $key, $values ) ? 'checked' : '' ) ); ?> /> <label for="sopt-seek-<?php print($item_id); ?>-<?php print($key); ?>"><?php print(tfuse_qtranslate($check_options[$key]['output'])); ?><?php print( @$vars['show_counts'] ? ' ('.$check_options[$key]['count'].$vars['counts_label_'.($check_options[$key]['count']==1 ? 'singular' : 'plural')].')' : '' ); ?></label>
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