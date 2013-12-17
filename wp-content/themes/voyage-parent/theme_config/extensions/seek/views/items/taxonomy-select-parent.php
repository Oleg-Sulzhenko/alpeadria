<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

if($sql_generator_options['search_on'] != 'taxonomy'){
    die('Error: '.esc_attr($sql_generator_options['search_on']).' specified in select-taxonomy view, taxonomy expected');
}

$getValue  = TF_SEEK_HELPER::get_input_value($parameter_name);

$list = get_terms($sql_generator_options['search_on_id'], 'hide_empty=0&parent='.$settings['select_parent']);

?><div class="row rowInput <?php print( @$vars['select_class'] ); ?>" style="z-index: 9;" id="tf-seek-input-select-<?php print($item_id); ?>">
    <label style="position: relative; top: -11px;"><?php print($vars['label']); ?>:</label>
    <select class="select_styled" name="<?php print $parameter_name; ?>" style="width: 207px;">
        <option value="0" ><?php print esc_attr($vars['default_option']); ?></option>
        <?php
        if(sizeof($list)){
            foreach($list as $term){
                ?>
                <option value="<?php print $term->term_id; ?>" <?php print ($term->term_id==$getValue ? 'selected="selected"' : ''); ?>><?php print esc_attr($term->name); ?></option>
                <?php
            }
        } else {
            ?><option value="0" style="font-style: italic;">(Taxonomy <?php print $sql_generator_options['search_on_id']; ?> has no terms)</option><?php
        }
        ?>
    </select>
</div>