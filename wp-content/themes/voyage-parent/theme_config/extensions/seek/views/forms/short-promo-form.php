<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

?>

<?php
    TF_SEEK_HELPER::print_form_item('short_promo_checkbox_categories');
?>

<?php
    TF_SEEK_HELPER::print_form_item('short_promo_price_range');
?>

<div class="row rowSubmit">
    <input type="submit" value="<?php _e('FIND NOW', 'tfuse'); ?>" class="btn-submit">
</div>

<div class="clear"></div>
