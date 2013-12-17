<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

    //TF_SEEK_HELPER::print_all_not_form_hidden($form_id, array( TF_SEEK_HELPER::get_search_parameter('page'), TF_SEEK_HELPER::get_search_parameter('orderby'), TF_SEEK_HELPER::get_post_type() ));

?>
<div class="search_col_1">

    <?php TF_SEEK_HELPER::print_form_item('location_select'); ?>

    <?php TF_SEEK_HELPER::print_form_item('date_from'); ?>

    <?php TF_SEEK_HELPER::print_form_item('date_to'); ?>

    <?php TF_SEEK_HELPER::print_form_item('promos'); ?>

    <div class="row rowSubmit"><input type="submit" value="<?php _e('FIND VACATIONS', 'tfuse'); ?>" class="btn btn-find"></div>

</div>

<div class="clear"></div>
