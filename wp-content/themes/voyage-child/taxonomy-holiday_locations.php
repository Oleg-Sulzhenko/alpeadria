<?php get_header();
$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
$taxonomyName = "holiday_locations";

global $wp_query,$TFUSE;
$default = $wp_query;
$sidebar_position = tfuse_sidebar_position();
if (!$TFUSE->request->empty_GET('order_by')) {
    $order_by = $TFUSE->request->GET('order_by');
} else {
    $order_by = 'date';
}
if (!$TFUSE->request->empty_GET('order')) {
    $order = $TFUSE->request->GET('order');
} else {
    $order = 'DESC';
}
if (!$TFUSE->request->empty_GET('page')) {
    $page = $TFUSE->request->GET('page');
} else {
    $page = 0;
}
$sel = 1;
if ($order_by == 'date' && $order == 'desc') {
    $sel = 1;
} elseif ($order_by == 'price' && $order == 'DESC') {
    $sel = 2;
} elseif ($order_by == 'price' && $order == 'ASC') {
    $sel = 3;
} elseif ($order_by == 'title' && $order == 'ASC') {
    $sel = 4;
} elseif ($order_by == 'title' && $order == 'DESC') {
    $sel = 5;
}

$get_order_by = $order_by;
$posts_per_page = $wp_query->query_vars['posts_per_page'];
if ($get_order_by == 'price') {
    $posts_per_page = -1;
}

if ($order_by != 'date' && $order_by != 'title') {
    $order_by = 'date';
}
$args = array(
    'order_by' => $order_by,
    'order' => $order,
    'paged' => $page,
    'posts_per_page' => $posts_per_page
);
$args = array_merge($wp_query->query, $args);
$posts = query_posts($args);
$tag = $wp_query->get_queried_object();
$num_pages = $wp_query->max_num_pages;
$holidays = $posts;
foreach ($posts as $key => $value) :
    $holidays [$key] = get_object_vars($value);
    $holidays [$key]['price'] = TF_SEEK_HELPER::get_post_option('property_price', null, $value->ID);
    $holidays [$key]['sale_type'] = TF_SEEK_HELPER::get_post_option('property_sale_type', 1, $value->ID);
    $holidays [$key]['reduction'] = TF_SEEK_HELPER::get_post_option('property_reduction', 0, $value->ID);
endforeach;
$total_properties = $tag->count;
if ($get_order_by == 'price') {
    if (!is_numeric($page)) {
        $page = 0;
    }
    $count = tfuse_get_count_properties_by_taxonomy_id($tag->term_id);
    $total_properties = (int)$count[0]['count'];
    $num_pages = (int)($count[0]['count'] / $default->query_vars['posts_per_page']);
    if ((($count[0]['count']) % $default->query_vars['posts_per_page']) != 0) {
        $num_pages++;
    }
    $page = intval($page);
    $start = $default->query_vars['posts_per_page'] * ($page - 1);
    if ($page == 1 || $page == 0) {
        $start = 0;
    }
    $final = $start + ($default->query_vars['posts_per_page']);
    if ($start != 0) {
        $final--;
    }
    if ($order == 'DESC') {
        $obj = tfuse_get_properties_by_taxonomy_id($tag->term_id, true, $start, $final);
    } else {
        $obj = tfuse_get_properties_by_taxonomy_id($tag->term_id, false, $start, $final);
    }

    $holidays = array();
    if(sizeof($obj)) :
        foreach ($obj as $key => $prop) {
            $holidays[$key]['ID'] = $prop['post_id'];
            $holidays [$key]['price'] = $prop['seek_property_price'];
            $holidays [$key]['sale_type'] = $prop['seek_property_sale_type'];
            $holidays[$key]['post_content'] = tfuse_qtranslate($prop['post_content']);
            $holidays[$key]['post_title'] = tfuse_qtranslate($prop['post_title']);
            $holidays[$key]['reduction'] = $prop['seek_property_reduction'];
        }
    endif;
}
?>
<input type="hidden" id="tax_permalink" value="<?php echo get_term_link($tag->slug, $tag->taxonomy);?>">
<input type="hidden" id="tax_results" page="<?php print $page ?>" num_pages="<?php print $num_pages ?>"
       get_order="<?php print $order; ?>" get_order_by="<?php print $get_order_by; ?>">
<div <?php tfuse_class('middle'); ?>>

    <div class="container_12">

        <?php if (!tfuse_options('disable_breadcrumbs')) {
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
            
            <div class="term-description">
                <img style="float: left; margin-right: 12px;" src="<?php the_field('flag', $taxonomyName . '_' . $term->term_id); ?>" />
                <h1> <?php echo $term->name; ?> </h1>
                <br/>
                <?php the_field('gavnpo', $taxonomyName . '_' . $term->term_id); ?>
            </div>

            <!-- sorting, pages -->
<!--            <div class="block_hr list_manage">
                <form action="#" method="post" class="form_sort">
                    <span class="manage_title"><?php _e('Sort by', 'tfuse'); ?>:</span>
                    <select class="select_styled white_select" name="sort_list" id="sort_list">
                        <option value="1"<?php if ($sel == 1) {
                            echo ' selected';
                        }?>><?php _e('Latest Added', 'tfuse'); ?></option>
                        <option value="2"<?php if ($sel == 2) { echo ' selected'; }?>><?php _e('Price High - Low', 'tfuse'); ?></option>
                        <option value="3"<?php if ($sel == 3) { echo ' selected'; }?>><?php _e('Price Low - Hight', 'tfuse'); ?></option>
                        <option value="4"<?php if ($sel == 4) { echo ' selected'; }?>><?php _e('Names A-Z', 'tfuse'); ?></option>
                        <option value="5"<?php if ($sel == 5) { echo ' selected'; }?>><?php _e('Names Z-A', 'tfuse'); ?></option>
                    </select>
                </form>

                <div class="pages_jump">
                    <span class="manage_title"><?php _e('Jump to page', 'tfuse'); ?>:</span>

                    <form action="#" method="post">
                        <input type="text" name="jumptopage" value="<?php print $num_pages ?>" class="inputSmall"
                               id="jumptopage"><input id="jumptopage_submit" type="submit" class="btn-arrow" value="Go">
                    </form>
                </div>

                <div class="pages">
                    <span class="manage_title"><?php _e('Page', 'tfuse'); ?> : &nbsp;<strong><?php if ($page == 0) {
                            echo $page + 1 . ' ';
                        } else {
                            echo $page . ' ';
                        } _e('of', 'tfuse');  echo ' ' . $num_pages; ?></strong></span> <a href="#" <?php if ($page == 0 || $page == 1) { echo 'rel="first" style="opacity:0.4;"'; } ?>
                        class="link_prev"><?php _e('Previous', 'tfuse'); ?></a><a href="#" <?php if ($page == $num_pages) { echo 'rel="last" style="opacity:0.4;"'; } ?>
                        class="link_next"><?php _e('Next', 'tfuse'); ?></a>
                </div>

                <div class="clear"></div>
            </div>-->
            <!--/ sorting, pages -->

            
            <!-- offers list -->
            <h3>Усі пропозиції</h3>
            <div class="re-list">
                <?php if (count($holidays)):
                $price_suffix = TF_SEEK_HELPER::get_option('seek_property_regular_price_suffix','');
                foreach ($holidays as $holiday): ?>
                    <div class="re-item">
                        <div class="re-image">
                            <?php if($holiday['reduction'] != 0) : ?><span class="ribbon off-<?php echo $holiday['reduction']; ?>"><?php _e('SALE:' ,'tfuse'); echo $holiday['reduction']; _e('% OFF', 'tfuse'); ?></span> <?php endif; ?>
                            <a href="<?php print(get_permalink($holiday['ID'])); ?>"><?php tfuse_media($holiday['ID']);?><span class="caption"><?php _e('View More Details', 'tfuse'); ?></span></a>
                        </div>

                        <div class="re-short">
                            <div class="re-top">
                                <h2><a href="<?php print(get_permalink($holiday['ID'])); ?>"><?php print(esc_attr($holiday['post_title'])); ?></a></h2>
                                <?php
                                $tags  =  get_the_terms($holiday['ID'], TF_SEEK_HELPER::get_post_type() . '_tag');
                                    if (!is_wp_error($tags) && $tags) $tags = array_values($tags); else $tags = array();
                                    $categories = get_the_terms($holiday['ID'], TF_SEEK_HELPER::get_post_type() . '_category');
                                ?>
                                <div class="re-subtitle"><?php if(!is_wp_error($categories) && sizeof($categories)) :  echo ucfirst (strtolower(reset($categories)->name)); _e(':', 'tfuse'); endif; ?>
                                    <?php  if ( $tags && !is_wp_error( $tags ) && sizeof($tags) ) : echo '<strong>'; foreach($tags as $key => $tag) : echo '<a href="' . get_tag_link($tag) . '">'  . tfuse_qtranslate($tag->name) . '</a>'; if ($key != (sizeof($tags)-1)) _e(', ', 'tfuse'); endforeach; echo'</strong>'; endif; ?>
                                    <?php if($holiday['sale_type'] == 2) : _e(' (', 'tfuse'); echo tfuse_page_options('during','1', $holiday['ID']); _e(' nights', 'tfuse'); _e(')'); endif; ?>
                                </div>
                            </div>
                            <div class="re-descr">
                                <p><?php echo tfuse_get_short_text($holiday['post_content'], 25); ?></p>
                            </div>
                        </div>
                        <div class="re-bot">
                            <span class="re-price"><?php _e('Price:', 'tfuse'); ?> <strong><?php print( TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$')); print $holiday['price']; if($holiday['sale_type'] == 1) echo $price_suffix; ?></strong></span>
                            <?php tfuse_get_holiday_images($holiday['ID']);?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif;    ?>
                <?php if (($page > $num_pages) || (!count($holidays))) :
                echo '<p>' . __('Page not found.', 'tfuse') . '</p>';
                echo '<p>' . __('The page you were looking for doesn&rsquo;t seem to exist.', 'tfuse') . '</p>';
            endif;
                ?>
            </div>
            <!-- offers list -->

            <!-- sorting, pages -->
            <div class="block_hr list_manage">
                <form action="#" method="post" class="form_sort">
                    <span class="manage_title"><?php _e('Sort by', 'tfuse'); ?>:</span>
                    <select class="select_styled white_select" name="sort_list" id="sort_list2">
                        <option value="1"<?php if ($sel == 1) {
                            echo ' selected';
                        }?>><?php _e(
                            'Latest Added',
                            'tfuse'
                        ); ?></option>
                        <option value="2"<?php if ($sel == 2) {
                            echo ' selected';
                        }?>><?php _e(
                            'Price High - Low',
                            'tfuse'
                        ); ?></option>
                        <option value="3"<?php if ($sel == 3) {
                            echo ' selected';
                        }?>><?php _e(
                            'Price Low - Hight',
                            'tfuse'
                        ); ?></option>
                        <option value="4"<?php if ($sel == 4) {
                            echo ' selected';
                        }?>><?php _e(
                            'Names A-Z',
                            'tfuse'
                        ); ?></option>
                        <option value="5"<?php if ($sel == 5) {
                            echo ' selected';
                        }?>><?php _e(
                            'Names Z-A',
                            'tfuse'
                        ); ?></option>
                    </select>
                </form>

                <div class="pages_jump">
                    <span class="manage_title"><?php _e('Jump to page', 'tfuse'); ?>:</span>

                    <form action="#" method="post">
                        <input type="text" name="jumptopage" value="<?php print $num_pages ?>" class="inputSmall"
                               id="jumptopage2"><input id="jumptopage_submit2" type="submit" class="btn-arrow"
                                                       value="Go">
                    </form>
                </div>

                <div class="pages">
                    <span class="manage_title"><?php _e('Page', 'tfuse'); ?>
                        : &nbsp;<strong><?php if ($page == 0) {
                            echo $page + 1 . ' ';
                        } else {
                            echo $page . ' ';
                        } _e(
                            'of',
                            'tfuse'
                        );  echo ' ' . $num_pages; ?></strong></span> <a
                        href="#" <?php if ($page == 0 || $page == 1) {
                    echo 'rel="first" style="opacity:0.4;"';
                } ?>
                        class="link_prev"><?php _e('Previous', 'tfuse'); ?></a><a
                        href="#" <?php if ($page == $num_pages) {
                    echo 'rel="last" style="opacity:0.4;"';
                } ?>
                        class="link_next"><?php _e('Next', 'tfuse'); ?></a>
                </div>

                <div class="clear"></div>
            </div>
            <!--/ sorting, pages -->

        </div>
        <!--/ .content -->

        <?php if ($sidebar_position == 'right') : ?>
        <div class="grid_4 sidebar">
            <?php get_sidebar(); ?>
        </div><!--/ .sidebar -->
        <?php endif; ?>

        <div class="clear"></div>

    </div>
    <!--/ .container_12 -->

</div><!--/ .middle -->

<div class="middle_bot"></div>
<?php tfuse_header_content('after_content'); ?>
<?php get_footer(); ?>
