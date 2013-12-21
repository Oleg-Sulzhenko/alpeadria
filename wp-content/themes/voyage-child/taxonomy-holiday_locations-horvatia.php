<?php
$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));

global $wp_query, $TFUSE;
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
    $total_properties = (int) $count[0]['count'];
    $num_pages = (int) ($count[0]['count'] / $default->query_vars['posts_per_page']);
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
    if (sizeof($obj)) :
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

<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php
            if (tfuse_options('disable_tfuse_seo_tab')) {
                wp_title('|', true, 'right');
                bloginfo('name');
                $site_description = get_bloginfo('description', 'display');
                if ($site_description && ( is_home() || is_front_page() ))
                    echo " | $site_description";
            }
            else
                wp_title('');
            ?></title>
        <?php tfuse_meta(); ?>
        <link rel="profile" href="http://gmpg.org/xfn/11" />	
        <link href="http://fonts.googleapis.com/css?family=Lato:400,400italic,700|Sorts+Mill+Goudy:400,400italic" rel="stylesheet">

        <!-- Mobile viewport optimized: h5bp.com/viewport -->
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <link type="text/css" media="screen" href="<?php echo get_stylesheet_uri(); ?>" rel="stylesheet">
        <link type="text/css" media="screen" href="<?php echo get_template_directory_uri() . '/screen.css'; ?>" rel="stylesheet">
        <script>document.cookie = 'resolution=' + Math.max(screen.width, screen.height) + '; path=/';</script>
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo tfuse_options('feedburner_url', get_bloginfo_rss('rss2_url')); ?>" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <?php
        tfuse_head();
        wp_head();
        TF_SEEK_HELPER::register_search_parameters(array(
            'form_id' => 'tfseekfid',
            'page' => 'tfseekpage',
            'orderby' => 'tfseekorderby'
        ));
        ?>
        <script>
            $(function() {
                $(window).stellar();
            });
        </script>
        <style>
            .horvatia-foto-banner{
                background: url(<?php the_field('main_img', 112); ?>) no-repeat;
            }
        </style>
    </head>
    <body <?php body_class(); ?>>
        <div class="body_wrap">
            <div class="header">
                <div class="container_12">
                    <div class="logo">
                        <a href="<?php bloginfo('url'); ?>"><img src="<?php echo tfuse_logo(); ?>" alt="<?php bloginfo('name'); ?>"></a>
                        <strong><?php bloginfo('name'); ?></strong>
                    </div>
                    <!--/ .logo -->

                    <div class="header_right">

                        <?php if (!tfuse_options('disable_header_search')) : ?>
                            <div class="topsearch">
                                <form id="searchForm" action="<?php echo home_url('/') ?>" method="get">
                                    <input type="submit" onclick="if (!document.getElementById('stext').value)
                        return false;" id="searchSubmit" value="" class="btn-search" >
                                    <input type="text" name="s" id="stext" value="" class="stext">
                                </form>
                            </div>
                        <?php endif; ?>

                        <?php if (!tfuse_options('disable_header_login')) : ?>
                            <div class="toplogin">
                                <p><a href="<?php echo wp_login_url(); ?>"><?php _e('SIGN IN', 'tfuse'); ?></a> <span class="separator">|</span> <a href="<?php echo site_url('/wp-login.php?action=register'); ?>"><?php _e('REGISTER', 'tfuse'); ?></a></p>
                            </div>
                        <?php endif; ?>

                        <div class="header_phone">
                            <?php echo tfuse_options('header_text_box'); ?>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <?php tfuse_menu('default'); ?>
                    <div class="clear"></div>
                </div>
            </div>
            <!--/ header -->
            <?php tfuse_header_content('header'); ?>

            <div class="horvatia-foto-banner" data-stellar-vertical-offset="0" data-stellar-background-ratio="0"></div>

            <input type="hidden" id="tax_permalink" value="<?php echo get_term_link($tag->slug, $tag->taxonomy); ?>">
            <input type="hidden" id="tax_results" page="<?php print $page ?>" num_pages="<?php print $num_pages ?>"
                   get_order="<?php print $order; ?>" get_order_by="<?php print $get_order_by; ?>">
            <div <?php tfuse_class('middle'); ?>>

                <div class="container_12">
                    <?php
                    if (!tfuse_options('disable_breadcrumbs')) {
                        tfuse_breadcrumbs();
                    }
                    ?>

                    <?php
                    $args = array(
                        'page_id' => 112,
                    );

                    query_posts($args);
                    ?>

                    <?php if (have_posts()) : ?>

                        <?php while (have_posts()) : the_post(); ?> 

                            <div class="page-horvatia-content">

                                <div  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                                    <div class="description"><?php the_content(); ?></div>
                                    <div class="choose-region-horvatia">
                                        <h3 class="widget-title">Тури по регіонам</h3>
                                        <?php
                                        $args = array(
                                            'child_of' => '24',
                                            'taxonomy' => $term->taxonomy,
                                            'hide_empty' => 1,
                                            'hierarchical' => true,
                                            'depth' => 1,
                                            'title_li' => ''
                                        );
                                        ?>

                                        <ul>
                                            <?php wp_list_categories($args); ?>
                                        </ul>

                                    </div>


                                </div>

                            <?php endwhile; ?>

                        <?php endif; ?>

                    </div>

                    <?php if ($sidebar_position == 'left') : ?>
                        <div class="grid_4 sidebar">
                            <?php get_sidebar(); ?>
                        </div><!--/ .sidebar -->
                    <?php endif; ?>

                    <!-- content -->
                    <div class="content">

                        <div class="title">
                            <?php if ($tag->count > 0) : ?>
                                <h1 style="text-align: center;">
                                    Оберіть подорож для себе
                                </h1>
                            <?php endif; ?>
                        </div>

                        <!-- sorting, pages -->
                        <div class="block_hr list_manage">
                            <form action="#" method="post" class="form_sort">
                                <span class="manage_title"><?php _e('Відсортувати за', 'tfuse'); ?>:</span>
                                <select class="select_styled white_select" name="sort_list" id="sort_list">
                                    <option value="1"<?php
                                    if ($sel == 1) {
                                        echo ' selected';
                                    }
                                    ?>><?php _e('Останні додані', 'tfuse'); ?></option>
                                    <option value="2"<?php
                                    if ($sel == 2) {
                                        echo ' selected';
                                    }
                                    ?>><?php _e('Ціна + -', 'tfuse'); ?></option>
                                    <option value="3"<?php
                                    if ($sel == 3) {
                                        echo ' selected';
                                    }
                                    ?>><?php _e('Ціна - +', 'tfuse'); ?></option>
                                </select>
                            </form>

                            <div class="pages_jump">
                                <span class="manage_title"><?php _e('Перейти до сторінки', 'tfuse'); ?>:</span>

                                <form action="#" method="post">
                                    <input type="text" name="jumptopage" value="<?php print $num_pages ?>" class="inputSmall"
                                           id="jumptopage"><input id="jumptopage_submit" type="submit" class="btn-arrow" value="Go">
                                </form>
                            </div>

                            <div class="pages">
                                <span class="manage_title"><?php _e('Сторінка', 'tfuse'); ?> : &nbsp;<strong><?php
                                        if ($page == 0) {
                                            echo $page + 1 . ' ';
                                        } else {
                                            echo $page . ' ';
                                        } _e('з', 'tfuse');
                                        echo ' ' . $num_pages;
                                        ?></strong></span> <a href="#" <?php
                                if ($page == 0 || $page == 1) {
                                    echo 'rel="first" style="opacity:0.4;"';
                                }
                                ?>
                                                    class="link_prev"><?php _e('Previous', 'tfuse'); ?></a><a href="#" <?php
                                                    if ($page == $num_pages) {
                                                        echo 'rel="last" style="opacity:0.4;"';
                                                    }
                                                    ?>
                                                    class="link_next"><?php _e('Next', 'tfuse'); ?></a>
                            </div>

                            <div class="clear"></div>
                        </div>
                        <!--/ sorting, pages -->

                        <!-- offers list -->
                        <div class="re-list"  id="horvatia-page-re-list">
                            <?php
                            if (count($holidays)):
                                $price_suffix = TF_SEEK_HELPER::get_option('seek_property_regular_price_suffix', '');
                                foreach ($holidays as $holiday):
                                    ?>
                                    <div class="re-item">
                                        <div class="re-image">
                                            <?php if ($holiday['reduction'] != 0) : ?><a href="<?php print(get_permalink($holiday['ID'])); ?>"><span class="ribbon off-<?php echo $holiday['reduction']; ?>"><?php
                                                _e('SALE:', 'tfuse');
                                                echo $holiday['reduction'];
                                                _e('% OFF', 'tfuse');
                                                ?></span></a> <?php endif; ?>
                                            <a href="<?php print(get_permalink($holiday['ID'])); ?>"><?php tfuse_media($holiday['ID']); ?><span class="caption"><?php print(esc_attr($holiday['post_title'])); ?></span></a>
                                        </div>

                                        <!--                                        <div class="re-short">
                                                                                    <div class="re-top">
                                                                                        <h2><a href="<?php print(get_permalink($holiday['ID'])); ?>"><?php print(esc_attr($holiday['post_title'])); ?></a></h2>
                                        <?php
                                        $tags = get_the_terms($holiday['ID'], TF_SEEK_HELPER::get_post_type() . '_tag');
                                        if (!is_wp_error($tags) && $tags)
                                            $tags = array_values($tags);
                                        else
                                            $tags = array();
                                        $categories = get_the_terms($holiday['ID'], TF_SEEK_HELPER::get_post_type() . '_category');
                                        ?>
                                                                                        <div class="re-subtitle"><?php
                                        if (!is_wp_error($categories) && sizeof($categories)) : echo ucfirst(strtolower(reset($categories)->name));
                                            _e(':', 'tfuse');
                                        endif;
                                        ?>
                                        <?php
                                        if ($tags && !is_wp_error($tags) && sizeof($tags)) : echo '<strong>';
                                            foreach ($tags as $key => $tag) : echo '<a href="' . get_tag_link($tag) . '">' . tfuse_qtranslate($tag->name) . '</a>';
                                                if ($key != (sizeof($tags) - 1))
                                                    _e(', ', 'tfuse');
                                            endforeach;
                                            echo'</strong>';
                                        endif;
                                        ?>
                                        <?php
                                        if ($holiday['sale_type'] == 2) : _e(' (', 'tfuse');
                                            echo tfuse_page_options('during', '1', $holiday['ID']);
                                            _e(' nights', 'tfuse');
                                            _e(')');
                                        endif;
                                        ?>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="re-descr">
                                                                                        <p><?php echo tfuse_get_short_text($holiday['post_content'], 25); ?></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="re-bot">
                                                                                    <span class="re-price"><?php _e('Price:', 'tfuse'); ?> <strong><?php
                                        print( TF_SEEK_HELPER::get_option('seek_property_currency_symbol', '$'));
                                        print $holiday['price'];
                                        if ($holiday['sale_type'] == 1)
                                            echo $price_suffix;
                                        ?></strong></span>
                                        <?php tfuse_get_holiday_images($holiday['ID']); ?>
                                                                                </div>-->
                                        <div class="clear"></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php
                            if (($page > $num_pages) || (!count($holidays))) :
                                echo '<p>' . __('Page not found.', 'tfuse') . '</p>';
                                echo '<p>' . __('The page you were looking for doesn&rsquo;t seem to exist.', 'tfuse') . '</p>';
                            endif;
                            ?>
                        </div>
                        <!-- offers list -->

                        <!-- sorting, pages -->
                        <div class="block_hr list_manage">
                            <form action="#" method="post" class="form_sort">
                                <span class="manage_title"><?php _e('Відсортувати за', 'tfuse'); ?>:</span>
                                <select class="select_styled white_select" name="sort_list" id="sort_list">
                                    <option value="1"<?php
                                    if ($sel == 1) {
                                        echo ' selected';
                                    }
                                    ?>><?php _e('Останні додані', 'tfuse'); ?></option>
                                    <option value="2"<?php
                                    if ($sel == 2) {
                                        echo ' selected';
                                    }
                                    ?>><?php _e('Ціна + -', 'tfuse'); ?></option>
                                    <option value="3"<?php
                                    if ($sel == 3) {
                                        echo ' selected';
                                    }
                                    ?>><?php _e('Ціна - +', 'tfuse'); ?></option>
                                </select>
                            </form>

                            <div class="pages_jump">
                                <span class="manage_title"><?php _e('Перейти до сторінки', 'tfuse'); ?>:</span>

                                <form action="#" method="post">
                                    <input type="text" name="jumptopage" value="<?php print $num_pages ?>" class="inputSmall"
                                           id="jumptopage"><input id="jumptopage_submit" type="submit" class="btn-arrow" value="Go">
                                </form>
                            </div>

                            <div class="pages">
                                <span class="manage_title"><?php _e('Сторінка', 'tfuse'); ?> : &nbsp;<strong><?php
                                        if ($page == 0) {
                                            echo $page + 1 . ' ';
                                        } else {
                                            echo $page . ' ';
                                        } _e('з', 'tfuse');
                                        echo ' ' . $num_pages;
                                        ?></strong></span> <a href="#" <?php
                                if ($page == 0 || $page == 1) {
                                    echo 'rel="first" style="opacity:0.4;"';
                                }
                                ?>
                                                    class="link_prev"><?php _e('Previous', 'tfuse'); ?></a><a href="#" <?php
                                                    if ($page == $num_pages) {
                                                        echo 'rel="last" style="opacity:0.4;"';
                                                    }
                                                    ?>
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



                    <?php
                    $taxonomyName = "holiday_locations";

                    $terms = get_terms($taxonomyName, array('parent' => 0));

                    foreach ($terms as $term) {
                        echo '<a href="' . get_term_link($term->slug, $taxonomyName) . '">' . $term->description . '</a>';
                        ?>

                        <a class="single-library-cat" href="<?php echo get_term_link($term->slug, $taxonomyName) ?>">
                            <img src="<?php the_field('flag', $taxonomyName . '_' . $term->term_id); ?>" />
                            <?php echo $term->name; ?>
                        </a>

                    <?php } ?>



                </div>
                <!--/ .container_12 -->

            </div><!--/ .middle -->

            <div class="middle_bot"></div>


            <?php tfuse_header_content('after_content'); ?>
            <?php get_footer(); ?>
