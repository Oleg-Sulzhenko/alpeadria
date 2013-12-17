<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

    TF_SEEK_HELPER::print_all_not_form_hidden(array($form_id), array( TF_SEEK_HELPER::get_search_parameter('page'), TF_SEEK_HELPER::get_search_parameter('orderby'), TF_SEEK_HELPER::get_post_type() ));

?>

<?php
//    TF_SEEK_HELPER::print_form_item('filter_tag_select');
    //TF_SEEK_HELPER::print_form_item('filter_tags_checkboxes');
?>

<?php
    TF_SEEK_HELPER::print_form_item('filter_price_range');
?>

<?php
    TF_SEEK_HELPER::print_form_item('filter_location_select');
?>

<?php
    TF_SEEK_HELPER::print_form_item('tax_ids_category');
?>

<?php
//    TF_SEEK_HELPER::print_form_item('includes_checkboxes');
?>

<?php
    TF_SEEK_HELPER::print_form_item('filter_date_from');
?>

<?php
    TF_SEEK_HELPER::print_form_item('filter_date_to');
?>

<?php
    TF_SEEK_HELPER::print_form_item('promos');
?>

<div class="row rowSubmit">
    <input type="submit" value="<?php _e('FILTER RESULTS', 'tfuse'); ?>" class="btn-submit">
</div>
