<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

if(@$settings['auto_options']){
    global $wpdb;

    $col_name   = trim($wpdb->prepare('%s', $sql_generator_options['search_on_id']), "'");
    $db_min_max = $wpdb->get_row( "SELECT
        MAX(". $col_name .") as max
            FROM ". trim($wpdb->prepare('%s', TF_SEEK_HELPER::get_db_table_name()), "'") ." si
        INNER JOIN " . $wpdb->prefix . "posts AS p ON p.ID = si.post_id
            WHERE p.post_status = 'publish'
        LIMIT 1", ARRAY_A);

    if(!sizeof($db_min_max)) return;

    $db_min_max['min'] = 0; //...

    if($db_min_max['min'] == $db_min_max['max']) return;

    $settings['from']   = 0;
    $settings['to']     = $db_min_max['max'];
}

$value      = esc_attr( TF_SEEK_HELPER::get_input_value($parameter_name, $settings['from'].';'.$settings['to'] ) );
$values     = explode(';', $value);
if( intval(@$values[0]) < $settings['from'] ){
    $values[0] = $settings['from'];
}
if( intval(@$values[1]) > $settings['to'] ){
    $values[1] = $settings['to'];
}
?>

<div class="row input_styled">
    <label class="label_title"><?php print esc_attr( $vars['label'] ); ?>:</label>
    <input type="hidden" name="<?php print esc_attr($parameter_name); ?>" value="<?php print( intval( @$values[0] ) . ';' . intval( @$values[1] ) ); ?>" id="sopt_seek_item_<?php print esc_attr($item_id); ?>" />
    <input type="text" id="sopt_seek_item_<?php print esc_attr($item_id); ?>-from" value="<?php print( intval( @$values[0] ) ); ?>" class="inputField inputSmall" onfocus="if (this.value == 'min') {this.value = '';}" onblur="if (this.value == '') {this.value = 'min';}"> &nbsp;&nbsp;&nbsp;
    <input type="text" id="sopt_seek_item_<?php print esc_attr($item_id); ?>-to" value="<?php print( intval( @$values[1] ) ); ?>" class="inputField inputSmall" onfocus="if (this.value == 'max') {this.value = '';}" onblur="if (this.value == '') {this.value = 'max';}">
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $("input#sopt_seek_item_<?php print esc_attr($item_id); ?>-from, input#sopt_seek_item_<?php print esc_attr($item_id); ?>-to").bind('blur change', function(){
            var val_from    = parseInt($("input#sopt_seek_item_<?php print esc_attr($item_id); ?>-from").val());
                val_from    = (val_from ? val_from : <?php print(intval($settings['from'])); ?>);
            var val_to      = parseInt($("input#sopt_seek_item_<?php print esc_attr($item_id); ?>-to").val());
                val_to      = (val_to ? val_to : <?php print(intval($settings['to'])); ?>);

            if(val_to < val_from){
                val_to = val_from;
                $("input#sopt_seek_item_<?php print esc_attr($item_id); ?>-to").val(val_to);
            }

            $("input#sopt_seek_item_<?php print esc_attr($item_id); ?>").val( ( val_from + ';' + val_to ) );
        });
    });
</script>
