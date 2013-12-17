<?php
    global $latest_added_nr;
    if (!is_numeric($latest_added_nr)) $latest_added_nr = 8;
    $args = array(
        'post_type'     => TF_SEEK_HELPER::get_post_type(),
        'orderby'         => 'post_date',
        'order'           => 'DESC',
        'numberposts' => $latest_added_nr

    );
    $properties = get_posts($args);

    $k = 0;
    $slides = array();
    foreach ($properties as $property) :

        $property_tx_category = tfuse_get_property_taxonomies($property->ID,'category');
        $property_location = tfuse_get_property_taxonomies($property->ID,'locations');
        if(count($property_tx_category) == 0) $property_tx_category = '-';
        $property_price = TF_SEEK_HELPER::get_post_option(TF_SEEK_HELPER::get_post_type() . '_price', '-', $property->ID);
        $property_beedrooms = TF_SEEK_HELPER::get_post_option(TF_SEEK_HELPER::get_post_type() . '_bedrooms', '- ', $property->ID);
        $property_baths = TF_SEEK_HELPER::get_post_option(TF_SEEK_HELPER::get_post_type() . '_baths', '-', $property->ID);
        $rooms = '';
        if(is_numeric($property_beedrooms))
        {
            $rooms .= $property_beedrooms . ' ' . mb_strtolower( TF_SEEK_HELPER::get_property_pluralization_name('seek_property_bedrooms', $property_beedrooms, true), 'UTF-8');
        }
        else
            $rooms .= $property_beedrooms;

        $rooms .= ',';

        if(is_numeric($property_baths))
        {
            $rooms .= ' ' . $property_baths . ' ' . mb_strtolower( TF_SEEK_HELPER::get_property_pluralization_name('seek_property_baths', $property_baths, true), 'UTF-8');
        }
        else
            $rooms .= $property_baths;


        $img_out = '';
        $image = new TF_GET_IMAGE();
        $src = tfuse_get_property_thumbnail($property->ID);
        $img_out .=  $image->width(218)->height(125)->src($src)->get_img();

        $slides[$k]['ID'] = $property->ID;
        $slides[$k]['img'] = $img_out;
        $slides[$k]['type'] = $property_tx_category;
        $slides[$k]['price'] = $property_price;
        $slides[$k]['rooms'] = $rooms;
        $slides[$k]['baths'] = $property_baths;
        $slides[$k]['location'] = $property_location;
        $k++;
    endforeach;
    wp_reset_query();
    if(sizeof($slides)) :
    wp_enqueue_script( 'jquery.jcarousel' );
?>
    <!-- carousel before content -->
    <div class="before_content">
        <div class="container_12">
            <h2><?php _e('Latest added','tfuse'); ?> Properties</h2>

            <div class="carusel_list">
                <ul id="latest_properties" class="jcarousel-skin-tango">
                    <?php foreach ($slides as $slide) : ?>
                    <?php

                    ?>
                    <li>
                        <div class="item_image"><a href="<?php print get_permalink($slide['ID']); ?>"><?php echo $slide['img']; ?></a></div>
                        <div class="item_row item_type"><span><?php _e('Property Type', 'tfuse'); ?>:</span> <a href="<?php if (isset($slide['type'][0]['slug'])) { echo get_term_link($slide['type'][0]['slug'], $slide['type'][0]['taxonomy']); } else { echo 'javascript:void(0);'; }; ?>"><strong><?php echo (isset($slide['type'][0]['name'])) ? $slide['type'][0]['name'] : '-';?></strong></a></div>
                        <div class="item_row item_price"><span><?php _e('Asking Price', 'tfuse'); ?>:</span> <strong><?php if(is_numeric($slide['price']))
                        {
                            print( TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'));
                            print( number_format( $slide['price']) );
                        } elseif(($slide['price'] != '-') && (!empty($slide['price'])))
                        {
                            print( TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'));
                            echo number_format(intval($slide['price']));
                            $stored_price = TF_SEEK_HELPER::get_post_option(TF_SEEK_HELPER::get_post_type() . '_price', null, $slide['ID']);
                            $int_price = (string)intval($slide['price']);
                            echo str_replace($int_price,'',$stored_price);
                        }
                        else
                        {
                            echo '-';
                        }
                            ?></strong></div>
                        <div class="item_row item_rooms"><span><?php _e('Rooms', 'tfuse'); ?>:</span> <strong><?php echo $slide['rooms']; ?></strong></div>
                        <div class="item_row item_location"><span><?php _e('City', 'tfuse'); ?> / <?php _e('Town', 'tfuse'); ?>:</span> <strong><?php echo (isset($slide['location'][0]['name'])) ? $slide['location'][0]['name'] : '-'; ?></strong></div>
                        <div class="item_row item_view"><a href="<?php print get_permalink($slide['ID']); ?>" class="btn_view"><?php _e('view property', 'tfuse'); ?></a></div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('#latest_properties').jcarousel({
                        <?php
                        if($k <=4 ) :
                            echo 'buttonNextHTML: null,';
                            echo 'buttonPrevHTML: null,';
                        endif;
                        ?>
                        easing: 'easeOutBack',
                        animation: 600,
                        scroll: 1
                    });
                });
            </script>

        </div>
    </div>
    <!--/ carousel before content -->
    <?php endif; ?>