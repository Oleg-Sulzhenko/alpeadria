<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');
$args = array();
if(isset($settings['args'])) $args = $settings['args'];
$args['hierarchical'] = false;
$terms = get_terms(TF_SEEK_HELPER::get_post_type() . '_' . @$settings['taxonomy'],$args);
$value  = false;
$getValue  = TF_SEEK_HELPER::get_input_value($parameter_name);
if(!empty($getValue)) $value = $getValue;
?>
<div class="row input_styled inlinelist">
    <div class="rowRadio"><input type="radio" name="<?php print esc_attr($item_id); ?>" value="<?php print $args['parent']; ?>" id="<?php print esc_attr($parameter_name); ?>_all" <?php print (((!$value) || ($value == $args['parent'])) ? 'checked' : ''); ?>> <label for="<?php print esc_attr($parameter_name); ?>_all"><?php print $vars['default_option']; ?></label></div>
    <?php foreach($terms as $term) : ?>
    <div class="rowRadio"><input type="radio" name="<?php print esc_attr($item_id); ?>" value="<?php print $term->term_id; ?>" id="<?php print $term->slug; ?>" <?php print (($value == $term->term_id) ? 'checked' : ''); ?>> <label for="<?php print $term->slug; ?>"><?php print $term->name; ?></label></div>
    <?php endforeach;?>
</div>