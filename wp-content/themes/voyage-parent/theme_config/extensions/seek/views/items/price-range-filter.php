<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

global $TFUSE;

if(!function_exists('__get_num_short_prefix_version')):
    function __get_num_short_prefix_version($num, $prefix = '', $max_round = true)
    {
        $num = intval($num);

        if($num >= 1000000){
            $num /= 10000000;
            $num = round($num,2);
            $num *=10;
            return $prefix . $num . __('Mil', 'tfuse');
        }

        if($num >= 1000){
            $num /= 10000;
            $num = round($num,2);
            $num *=10;
            return $prefix . $num . __('k', 'tfuse');
        }

        return $prefix . $num;
    }
endif;


if(@$settings['auto_options'])
{

    global $wpdb;

    $col_name   = trim($wpdb->prepare('%s', $sql_generator_options['search_on_id']), "'");
    $db_min_max = $wpdb->get_row( "SELECT
        MAX(". $col_name .") as max, MIN(". $col_name .") as min
            FROM ". trim($wpdb->prepare('%s', TF_SEEK_HELPER::get_db_table_name()), "'") ." si
        INNER JOIN " . $wpdb->prefix . "posts AS p ON p.ID = si.post_id
            WHERE p.post_status = 'publish'
        LIMIT 1", ARRAY_A);

    if(!sizeof($db_min_max)) return;
    if($db_min_max['min'] == $db_min_max['max']) return;

    $settings['from']   = $db_min_max['min'];
    $settings['to']     = $db_min_max['max'];
    $settings['scale'] = array();
    $settings['scale'][]  = __get_num_short_prefix_version($settings['from'] , TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'), false);
    $settings['scale'][]  = __get_num_short_prefix_version($settings['to'], TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'));
	$settings['selected_values'] = array();
    unset($settings['step']);
}

$selected_min = $settings['from'];
$selected_max = $settings['to'];
if(isset($settings['selected_values']) && sizeof($settings['selected_values']) && $settings['selected_values']){

    if(isset($settings['selected_values'][0]))$selected_min = $settings['selected_values'][0];
    else $selected_min = $settings['selected_values'];

    if(sizeof($settings['selected_values'])>=2){
        $selected_min = min($settings['selected_values']);
        $selected_max = max($settings['selected_values']);
    } else unset($selected_max);
}
?>
<div class="row rangeField">
    <?php if(isset($vars['label'])): ?><label class="<?php print esc_attr( $vars['label_class'] ); ?>" ><?php print esc_attr( $vars['label'] ); ?>:</label><?php endif; ?>
    <div class="range-slider">
        <input id="range_field_<?php print esc_attr($item_id); ?>" type="text" name="<?php print esc_attr($parameter_name); ?>" value="<?php if($TFUSE->request->isset_GET('price')) { echo $TFUSE->request->GET('price'); } else { echo $selected_min; if(isset($selected_max)) echo ';' . $selected_max; } ?>">
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript" >
    jQuery(document).ready(function() {
        // Price Range Input
        jQuery("#range_field_<?php print esc_attr($item_id); ?>").slider({
            from: <?php print $settings['from']; ?>,
            to: <?php print $settings['to']; ?>,
            smooth: <?php print (@$settings['smooth'] ? 'true' : 'false'); ?>,
            scale: <?php print str_replace('\\/', '/', json_encode($settings['scale']) ); ?>,
            skin: "<?php print @$settings['skin']; ?>",
            limits: <?php print (@$settings['limits'] ? 'true' : 'false'); ?>
            <?php if(isset($settings['heterogeneity'])): ?>,heterogeneity: <?php print str_replace('\\/', '/', json_encode($settings['heterogeneity']) ); endif; ?>
            <?php if(isset($settings['step'])): ?>,step: <?php echo $settings['step']; endif; ?>
            <?php if(isset($settings['dimension'])): ?>,dimension: '<?php print $settings['dimension']; ?>'
            <?php endif; ?>
        });
    });
</script>
