<?php

/**
 * Tags
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 * Optional arguments:
 */
function tfuse_tags($atts, $content = null) {
    global $tfuse_tag;
    extract(shortcode_atts(array('title' => '', 'latest' => '', 'latest_title'=> ''), $atts));

    $get_tags = do_shortcode($content);
    ob_start();
    ?>
<!-- HOLIDAYS offers list -->
<div class="title">
    <?php if($title) echo '<h2>' . $title . '</h2>'; ?>
    <span class="title_right"><a href="?tfseekfid=main_search&s=~&holidays=all"><?php _e('See all Travel offers', 'tfuse'); ?></a></span>
</div>

<div class="boxed_list">

    <?php
    $i = 0;$k=0;
    while (isset($tfuse_tag['id'][$i])) {
        $term = get_term(intval($tfuse_tag['id'][$i]), TF_SEEK_HELPER::get_post_type() . '_tag');
        if(is_wp_error($term)) { $i++; continue; }
        ?>
        <div class="boxed_item">
            <div class="boxed_icon">
                <?php
                    $image = new TF_GET_IMAGE();
                    echo $image->properties(array('alt'=>$term->name))->width(48)->height(48)->src($tfuse_tag['icon'][$i])->get_img();
                ?>
            </div>
            <div class="boxed_title"><strong><?php echo $term->name; ?></strong></div>
            <span><a href="<?php $term_url =  get_term_link($term, TF_SEEK_HELPER::get_post_type() . '_tag'); if(!is_wp_error($term_url)) echo $term_url; else echo '#'; ?>"><?php echo $term->count . ' '; echo ($term->count > 1) ? __('offers available', 'tfuse') : __('offer available', 'tfuse'); ?></a></span>
        </div>
    <?php
        $i++;
        $k++;
    }
    ?>
    <div class="clear"></div>
</div>
<?php if($latest=='true' && $k) : ?>
<div class="boxed_list boxed_list2">

    <?php if($latest_title) : --$k; ?>
    <div class="boxed_item">
        <div class="boxed_title_arrow"><strong><?php echo $latest_title; ?></strong></div>
    </div>
    <?php endif; ?>
    <?php $offers = tfuse_get_latest_offers ($k);
        $unit = TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$');
        foreach($offers as $offer) :
    ?>
        <div class="boxed_item">
            <div class="boxed_icon"><span class="price_box"><?php if(is_numeric($offer['price']))  echo '<ins>' . $unit . '</ins><strong>' . $offer['price'] . '</strong>'; else echo '<strong>-</strong>'; ?></span></div>
            <div class="boxed_title"><a href="<?php echo $offer['url']; ?>"><strong><?php echo $offer['title']; ?></strong></a></div>
            <span>
                <a href="<?php echo $offer['url']; ?>">
                    <?php
                    $holiday_locations = wp_get_object_terms($offer['id'], TF_SEEK_HELPER::get_post_type() . '_locations', array('orderby' => 'term_order', 'order' => 'ASC'));
                    if(!empty($holiday_locations)){
                        if(!is_wp_error( $holiday_locations )){
                            print $holiday_locations[sizeof($holiday_locations)-1]->name;
                        }
                    }
                    ?>
                </a>
            </span>
        </div>
    <?php endforeach; ?>

    <div class="clear"></div>
</div>
<?php endif; ?>

<!--/ HOLIDAYS offers list -->
    <?php

    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
$tags = get_terms( TF_SEEK_HELPER::get_post_type() . '_tag', 'orderby=count&hide_empty=1' );
$options = array();
if(!is_wp_error($tags) && $tags) :
    foreach($tags as $tag) :
        $options[$tag->term_id] = $tag->name;
    endforeach;
endif;
$atts = array(
    'name' => __('Tags', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Title', 'tfuse'),
            'desc' => '',
            'id' => 'tf_shc_tags_title',
            'value' => 'CHOOSE FROM A WIDE VARIETY OF HOLIDAYS',
            'type' => 'text'
        ),
        array(
            'name' => __('Tag', 'tfuse'),
            'desc' => __('Specifies the title of an shortcode', 'tfuse'),
            'id' => 'tf_shc_tags_id',
            'value' => '',
            'type' => 'select',
            'properties' => array('class' => 'tf_shc_addable_0 tf_shc_addable'),
            'options' => $options

        ),
        array(
            'name' => __('Link Icon', 'tfuse'),
            'desc' => '',
            'id' => 'tf_shc_tags_icon',
            'value' => '',
            'type' => 'text',
            'properties' => array('class' => 'tf_shc_addable_1 tf_shc_addable tf_shc_addable_last'),
        ),
        array(
            'name' => __('Show last offers', 'tfuse'),
            'desc' => __('Show below latest offers?', 'tfuse'),
            'id' => 'tf_shc_tags_latest',
            'value' => true,
            'type' => 'checkbox'
        ),
        array(
            'name' => __('Latest Title', 'tfuse'),
            'desc' => '',
            'id' => 'tf_shc_tags_ltitle',
            'value' => 'Last Minute Deals',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('tags', 'tfuse_tags', $atts);


function tfuse_tag($atts)
{
    global $tfuse_tag;
    extract(shortcode_atts(array('id' => '','icon' => ''), $atts));

    $tfuse_tag['id'][] = $id;
    $tfuse_tag['icon'][] = $icon;
}

$atts = array(
    'name' => __('Tag', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Tag', 'tfuse'),
            'desc' => __('Specifies the title of an shortcode', 'tfuse'),
            'id' => 'tf_shc_tag_id',
            'value' => '',
            'type' => 'select',
            'options' => $options
        ),
        array(
            'name' => __('Link Icon', 'tfuse'),
            'desc' => '',
            'id' => 'tf_shc_tag_icon',
            'value' => '',
            'type' => 'text'
        )
    )
);

add_shortcode('tag', 'tfuse_tag', $atts);