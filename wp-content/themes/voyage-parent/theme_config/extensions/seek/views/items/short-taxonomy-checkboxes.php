<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

    $args = (isset($vars['get_terms_args'])) ? $vars['get_terms_args'] : '';
    $terms = get_terms($sql_generator_options['search_on_id'], $args);
    if(!sizeof($terms) || is_wp_error($terms))  return;

    $check_options  = array();
    foreach($terms as $term){
        $check_options[ $term->term_id ] = array(
            'output'    => $term->name
        );
    }
?>
<div class="row input_styled checklist">
    <?php if(isset($vars['label'])): ?><label class="<?php print esc_attr( $vars['label_class'] ); ?>" ><?php print esc_attr( $vars['label'] ); ?>:</label><?php endif; ?>
    <input type="hidden" name="<?php print($parameter_name); ?>" value="" id="sopt-seek-<?php print($item_id); ?>" />
    <?php foreach($check_options as $key => $val): ?>
        <div class="rowCheckbox"><input type="checkbox" onchange="tf_seek_update_hidden_check_<?php print(preg_replace('/[^a-z0-9]/iU', '_', $item_id)); ?>();" class="sopt-seek-<?php print($item_id); ?>" id="sopt-seek-<?php print($item_id); ?>-<?php print($key); ?>" value="<?php print($key); ?>"><label for="sopt-seek-<?php print($item_id); ?>-<?php print($key); ?>"><?php print(tfuse_qtranslate($check_options[$key]['output'])); ?></label></div>
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