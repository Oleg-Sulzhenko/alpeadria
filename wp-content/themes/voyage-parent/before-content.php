<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


    global $search;
?>
<!-- before content -->
<div class="before_content">
    <div class="before_inner">
        <div class="container_12">

            <div class="title">
                <?php if(isset($search['type']) && $search['type'] == 'expanded') : echo '<h2>'; _e('WHERE DO YOU WANT TO TRAVEL?', 'tfuse'); echo'</h2>'; endif; ?>
                <span class="title_right"><a href="<?php echo site_url('/'); ?>?tfseekfid=main_search&s=~&holidays=all"><?php _e('See all', 'tfuse'); echo ' ' . __(TF_SEEK_HELPER::get_option('seek_property_name_singular','Holiday'), 'tfuse') . ' '; _e('offers', 'tfuse'); ?></a></span>
            </div>

            <div class="search_main">
    <?php if (isset($search['type']) && $search['type'] == 'closed')
                TF_SEEK_HELPER::print_form('main_search');
            elseif(isset($search['type']) && $search['type'] == 'expanded')
                TF_SEEK_HELPER::print_form('advanced_search');
    ?>
            </div>

        </div>
    </div>
</div>
<!--/ before content -->