<?php
/**
 * The template for displaying Search Results pages.
 *
 * @since Voyage 1.0
 */

// Hack search <title>
$s_backup = get_query_var('s');
set_query_var('s', __(TF_SEEK_HELPER::get_option('seek_property_name_plural','Holidays'), 'tfuse') );

get_header();

set_query_var('s', $s_backup);
unset($s_backup);
/// ^end-back

$sidebar_position   = tfuse_sidebar_position();

// Seek search

$orderby_options    = array(
    'latest'        => array(
        'label'     => __('Latest Added', 'tfuse'),
        'sql'       => 'p.post_date DESC',
    ),
    'price-high-low'    => array(
        'label'     => __('Price High - Low', 'tfuse'),
        'sql'       => 'options.seek_property_price DESC',
    ),
    'price-low-high'    => array(
        'label'     => __('Price Low - High', 'tfuse'),
        'sql'       => 'options.seek_property_price ASC',
    ),
    'names-a-z'    => array(
        'label'     => __('Names A - Z', 'tfuse'),
        'sql'       => 'p.post_title ASC',
    ),
    'names-z-a'    => array(
        'label'     => __('Names Z - A', 'tfuse'),
        'sql'       => 'p.post_title DESC',
    ),
);


$search_params      = array(
    'return_type'       => ARRAY_A,
    'posts_per_page'    => get_option('posts_per_page',5),
    'orderby_options'   => $orderby_options,
    'debug'             => false,
);
$search_results     = TF_SEEK_HELPER::get_search_results($search_params);
$price_suffix = TF_SEEK_HELPER::get_option('seek_property_regular_price_suffix','');
?>

<div <?php tfuse_class('middle'); ?>>
<div class="container_12">

    <?php   if (!tfuse_options('disable_breadcrumbs')) {
            tfuse_breadcrumbs();
        }
    ?>

    <?php if ($sidebar_position == 'left') : ?>
    <div class="grid_4 sidebar">
        <?php get_sidebar(); ?>
    </div><!--/ .sidebar -->
    <?php endif; ?>

<!-- content -->
<div class="content">
    <?php
        global $TFUSE;
        $location_error = true;
        $location_id = $TFUSE->request->isset_GET('location_id') ? $TFUSE->request->GET('location_id') : -1;
        if($location_id == -1)
        {
            $location_id = $TFUSE->request->isset_GET('main_location') ? $TFUSE->request->GET('main_location') : -1;
        }
        $location = get_term($location_id,TF_SEEK_HELPER::get_post_type() . '_locations');
        if(!is_wp_error($location) && ($TFUSE->request->isset_GET('location_id') || $TFUSE->request->isset_GET('main_location'))) {$location = $location->name;$location_error = false;}
    ?>
    <div class="title">
        <h1><?php if($TFUSE->request->isset_GET('category_ids') && ($TFUSE->request->GET('category_ids')) && (!strpos(',',$TFUSE->request->GET('category_ids')))) { $cat_id = intval($TFUSE->request->GET('category_ids')); $cat = get_term($cat_id, TF_SEEK_HELPER::get_post_type() . '_category'); if($cat && (isset($cat->name))) $cat_name = $cat->name; else $cat_name = TF_SEEK_HELPER::get_option('seek_property_name_plural','Holidays'); echo mb_strtoupper($cat_name, 'UTF-8'); } else {echo mb_strtoupper(TF_SEEK_HELPER::get_option('seek_property_name_plural','Holidays'), 'UTF-8'); }?> <?php  _e('IN', 'tfuse'); ?> <span><?php if (!$location_error) echo $location; else _e('All Locations', 'tfuse'); ?></span><?php ?></h1>
        <span class="title_right count"><?php print(($search_results['total']) ? $search_results['total'] : __('no', 'tfuse')); ?> <?php _e('available offers', 'tfuse'); ?></span>
    </div>

    <!-- sorting, pages -->
    <div class="block_hr list_manage">
        <div class="inner">
            <?php
                TF_SEEK_CUSTOM_FUNCTIONS::orderby( $orderby_options, array('select_id'=>'sort_list') );
            ?>

            <?php
                TF_SEEK_CUSTOM_FUNCTIONS::html_jump_to_page(array(
                    'curr_page'         => $search_results['curr_page'],
                    'max_pages'         => $search_results['max_pages'],
                ));
            ?>

            <?php
                TF_SEEK_CUSTOM_FUNCTIONS::html_paging(array(
                    'curr_page'         => $search_results['curr_page'],
                    'max_pages'         => $search_results['max_pages'],
                ));
            ?>

            <div class="clear"></div>
        </div>
    </div>
    <!--/ sorting, pages -->

    <!-- real estate list -->
    <div class="re-list">

        <?php $slist = $search_results['rows'];?>
        <?php if(sizeof($slist)): ?>
            <?php  foreach($slist as $spost):  ?>
                <div class="re-item">
                    <div class="re-image">
                        <?php if($spost['seek_property_reduction'] != 0) : ?><span class="ribbon off-<?php echo $spost['seek_property_reduction']; ?>"><?php _e('SALE:' ,'tfuse'); echo $spost['seek_property_reduction']; _e('% OFF', 'tfuse'); ?></span> <?php endif; ?>
                        <a href="<?php print(get_permalink($spost['ID'])); ?>"><?php tfuse_media($spost['ID']);?><span class="caption"><?php _e('View More Details', 'tfuse'); ?></span></a>
                    </div>

                    <div class="re-short">
                        <div class="re-top">
                            <h2><a href="<?php print(get_permalink($spost['ID'])); ?>"><?php print(esc_attr(tfuse_qtranslate($spost['post_title']))); ?></a></h2>
                            <?php
                                    $tags  =  get_the_terms($spost['ID'], TF_SEEK_HELPER::get_post_type() . '_tag');
                                    $categories = get_the_terms($spost['ID'], TF_SEEK_HELPER::get_post_type() . '_category');
                                    $categories = is_array($categories) ? array_values($categories) : array();
                            ?>
                                <div class="re-subtitle"><?php if(!is_wp_error($categories) && isset($categories[0])) : echo ucfirst (strtolower($categories[0]->name)); _e(':', 'tfuse'); endif; ?>
                                <?php  if ( $tags && !is_wp_error( $tags ) && sizeof($tags) ) : echo '<strong>'; foreach($tags as $key => $tag) : echo '<a href="' . get_tag_link($tag) . '">'  . tfuse_qtranslate($tag->name) . '</a>'; if ($key != (sizeof($tags)-1)) _e(', ', 'tfuse'); endforeach; echo'</strong>'; endif; ?>
                                    <?php if($spost['seek_property_sale_type'] == 2) : _e(' (', 'tfuse'); echo tfuse_page_options('during','1', $spost['ID']); _e(' nights', 'tfuse'); _e(')'); endif; ?>
                                </div>
                        </div>
                        <div class="re-descr">
                            <p><?php echo tfuse_get_short_text(tfuse_qtranslate($spost['post_content']),25); ?></p>
                        </div>
                    </div>
                    <div class="re-bot">
                        <span class="re-price"><?php _e('Price:', 'tfuse'); ?> <strong><?php print( TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$')); echo $spost['seek_property_price']; if($spost['seek_property_sale_type'] == 1) echo $price_suffix; ?></strong></span>
                        <?php tfuse_get_holiday_images($spost['ID']);?>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php
                if($search_results['total']<1){
                    if(!$TFUSE->request->isset_GET('favorites')) _e( 'Sorry, but nothing matched your search criteria.', 'tfuse' );
                    else _e( 'Sorry, no favorites added yet', 'tfuse');
                } else {
                    // Wrong page
                }
            ?>
        <?php endif; ?>

    </div>
    <!--/ real estate list -->


    <!-- sorting, pages -->
    <div class="block_hr list_manage">
        <div class="inner">
            <?php
                TF_SEEK_CUSTOM_FUNCTIONS::orderby($orderby_options, array('select_id'=>'sort_list2'));
            ?>

            <?php
                TF_SEEK_CUSTOM_FUNCTIONS::html_jump_to_page(array(
                    'curr_page'         => $search_results['curr_page'],
                    'max_pages'         => $search_results['max_pages'],
                ));
            ?>

            <?php
                TF_SEEK_CUSTOM_FUNCTIONS::html_paging(array(
                    'curr_page'         => $search_results['curr_page'],
                    'max_pages'         => $search_results['max_pages'],
                ));
            ?>

            <div class="clear"></div>
        </div>
    </div>
    <!--/ sorting, pages -->



</div>
<!--/ content -->

    <?php if ($sidebar_position == 'right') : ?>
    <div class="grid_4 sidebar">
        <?php get_sidebar(); ?>
    </div><!--/ .sidebar -->
    <?php endif; ?>

<div class="clear"></div>


</div>
</div>
<!--/ middle -->
<?php tfuse_header_content('after_content'); ?>
<?php get_footer(); ?>