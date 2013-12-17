<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


$value = intval(TF_SEEK_HELPER::get_input_value($parameter_name));
if($value < $settings['min']){
    $value = $settings['min'];
} elseif($value > $settings['max']) {
    $value = $settings['max'];
}
?>
<div class="row form_switch">
    <?php print( isset($vars['label']) ? '<label class="label_title">'.$vars['label'].':</label>' : '' ); ?>
    <div class="switch switch_<?php print($value==1 ? 'on' : 'off'); ?>">
        <label for="sopt_seek_switch_<?php print esc_attr($item_id); ?>_1" class="cb-enable<?php print($value==1 ? ' selected' : ''); ?>"><span>Sale</span></label>
        <label for="sopt_seek_switch_<?php print esc_attr($item_id); ?>_2" class="cb-disable<?php print($value==2 ? ' selected' : ''); ?>"><span>Rent</span></label>
        <input type="radio" value="1" id="sopt_seek_switch_<?php print esc_attr($item_id); ?>_1" name="<?php print esc_attr($parameter_name); ?>" <?php print($value==1 ? 'checked' : ''); ?>>
        <input type="radio" value="2" id="sopt_seek_switch_<?php print esc_attr($item_id); ?>_2" name="<?php print esc_attr($parameter_name); ?>" <?php print($value==2 ? 'checked' : ''); ?>>
    </div>
</div>