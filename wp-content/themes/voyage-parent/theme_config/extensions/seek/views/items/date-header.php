<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');
    $value  = (!empty($vars['value'])) ? $vars['value'] : '';

    $getValue  = TF_SEEK_HELPER::get_input_value($parameter_name);
    if(!empty($getValue)) $value = $getValue;
?>
<div class="row">
    <input type="text" name="<?php print $parameter_name; ?>" class="inputField" value="<?php print $value; ?>" onfocus="if (this.value == '<?php print $value; ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php print $value; ?>';}" id="date-picker_<?php print($item_id); ?>">
    <span class="input_icon"></span>
</div>
<script>
    jQuery(document).ready(function() {
        jQuery("#date-picker_<?php print($item_id); ?>").datepicker({
            dateFormat: "<?php if(isset($vars['date_format'])) print $vars['date_format']; else print tfuse_options('search_date_format', 'MM dd, yy'); ?>",
            minDate: 0,
            showOtherMonths: true
            <?php if(!empty($vars['dependency']) && is_array($vars['dependency'])){ ?>
            ,onSelect: function( selectedDate ) {
                jQuery("#date-picker_<?php echo($vars['dependency']['item_id']); ?>").datepicker( "option", "<?php echo $vars['dependency']['relation']; ?>", selectedDate).trigger('blur');
            }
            <?php } ?>
        });
    });
</script>