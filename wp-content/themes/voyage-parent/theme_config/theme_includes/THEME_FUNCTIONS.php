<?php

if (!function_exists('tfuse_browser_body_class')):

    /* This Function Add the classes of body_class()  Function
     * To override tfuse_browser_body_class() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
    */

    add_filter('body_class', 'tfuse_browser_body_class');

    function tfuse_browser_body_class() {

        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

        if ($is_lynx)
            $classes[] = 'lynx';
        elseif ($is_gecko)
            $classes[] = 'gecko';
        elseif ($is_opera)
            $classes[] = 'opera';
        elseif ($is_NS4)
            $classes[] = 'ns4';
        elseif ($is_safari)
            $classes[] = 'safari';
        elseif ($is_chrome)
            $classes[] = 'chrome';
        elseif ($is_IE) {
            $browser = $_SERVER['HTTP_USER_AGENT'];
            $browser = substr("$browser", 25, 8);
            if ($browser == "MSIE 7.0")
                $classes[] = 'ie7';
            elseif ($browser == "MSIE 6.0")
                $classes[] = 'ie6';
            elseif ($browser == "MSIE 8.0")
                $classes[] = 'ie8';
            else
                $classes[] = 'ie';
        }
        else
            $classes[] = 'unknown';

        if ($is_iphone)
            $classes[] = 'iphone';

        return $classes;
    } // End function tfuse_browser_body_class()
endif;


if (!function_exists('tfuse_class')) :
    /* This Function Add the classes for middle container
     * To override tfuse_class() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
    */

    function tfuse_class($param, $return = false) {
        $tfuse_class = '';
        $sidebar_position = tfuse_sidebar_position();
        if ($param == 'middle')
        {
            if ($sidebar_position == 'left')
                $tfuse_class = ' id="middle" class="cols2 sidebar_left"';
            elseif($sidebar_position == 'right')
                $tfuse_class = ' id="middle" class="cols2"';
            else
                $tfuse_class = ' id="middle" class="full_width"';
        }
        elseif ($param == 'content')
        {
            $tfuse_class = ( isset($sidebar_position) && $sidebar_position != 'full' ) ? ' class="grid_8 content"' : ' class="content"';
        }

        if ($return)
            return $tfuse_class;
        else
            echo $tfuse_class;
    }
endif;


if (!function_exists('tfuse_sidebar_position')):
    /* This Function Set sidebar position
     * To override tfuse_sidebar_position() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
    */
    function tfuse_sidebar_position() {
        global $TFUSE;

        $sidebar_position = $TFUSE->ext->sidebars->current_position;
        if ( empty($sidebar_position) ) $sidebar_position = 'full';

        return $sidebar_position;
    }

// End function tfuse_sidebar_position()
endif;


if (!function_exists('tfuse_count_post_visits')) :
    /**
     * tfuse_count_post_visits.
     *
     * To override tfuse_count_post_visits() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_count_post_visits()
    {
        if ( !is_single() ) return;

        global $post;

        $views = get_post_meta($post->ID, TF_THEME_PREFIX . '_post_viewed', true);
        $views = intval($views);
        tf_update_post_meta( $post->ID, TF_THEME_PREFIX . '_post_viewed', ++$views);
    }
    add_action('wp_head', 'tfuse_count_post_visits');

// End function tfuse_count_post_visits()
endif;


if (!function_exists('tfuse_custom_title')):

    function tfuse_custom_title($customID = false,$return = false) {
        global $post;

        if (is_numeric($customID))
            $ID = $customID;
        else
            $ID = $post->ID;

        $tfuse_title_type = tfuse_page_options('page_title', '', $ID);

        if ($tfuse_title_type == 'hide_title')
            $title = '';
        elseif ($tfuse_title_type == 'custom_title')
            $title = tfuse_page_options('custom_title', '', $ID);
        else
            $title = get_the_title($ID);

        if( $return ) return $title;

        echo ( $title ) ? '<h1>' . $title . '</h1>' : '';
    }

endif;

if (!function_exists('tfuse_archive_custom_title')):
    /**
     *  Set the name of post archive.
     *
     * To override tfuse_archive_custom_title() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_archive_custom_title()
    {
        $cat_ID = 0;
        if ( is_category() )
        {
            $cat_ID = get_query_var('cat');
            $title = single_term_title( '', false );
        }
        elseif ( is_tax() )
        {
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            $cat_ID = $term->term_id;
            $title = single_term_title( $term->name , false );
        }
        elseif ( is_post_type_archive() )
        {
            $title = post_type_archive_title('',false);
        }

        $tfuse_title_type = tfuse_options('page_title',null,$cat_ID);

        if ($tfuse_title_type == 'hide_title')
            $title = '';
        elseif ($tfuse_title_type == 'custom_title')
            $title = tfuse_options('custom_title',null,$cat_ID);

        echo !empty($title) ? '<h1>' . $title . '</h1>' : '';
    }

endif;



if (!function_exists('tfuse_user_profile')) :
    /**
     * Retrieve the requested data of the author of the current post.
     *
     * @param array $fields first_name,last_name,email,url,aim,yim,jabber,facebook,twitter etc.
     * @return null|array The author's spefified fields from the current author's DB object.
     */
    function tfuse_user_profile( $fields = array() )
    {
        $tfuse_meta = null;

        // Get stnadard user contact info
        $standard_meta = array(
            'first_name' => get_the_author_meta('first_name'),
            'last_name' => get_the_author_meta('last_name'),
            'email'     => get_the_author_meta('email'),
            'url'       => get_the_author_meta('url'),
            'aim'       => get_the_author_meta('aim'),
            'yim'       => get_the_author_meta('yim'),
            'jabber'    => get_the_author_meta('jabber')
        );

        // Get extended user info if exists
        $custom_meta = (array) get_the_author_meta('theme_fuse_extends_user_options');

        $_meta = array_merge($standard_meta,$custom_meta);

        foreach ($_meta as $key => $item) {
            if ( !empty($item) && in_array($key, $fields) ) $tfuse_meta[$key] = $item;
        }

        return apply_filters('tfuse_user_profile', $tfuse_meta, $fields);
    }

endif;


if (!function_exists('tfuse_action_comments')) :
    /**
     *  This function disable post commetns.
     *
     * To override tfuse_action_comments() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_action_comments() {
        global $post;

        if (!tfuse_page_options('disable_comments') && isset($post) && $post->comment_status == 'open')
            comments_template( '', true );
    }

    add_action('tfuse_comments', 'tfuse_action_comments');
endif;


if (!function_exists('tfuse_get_comments')):
    /**
     *  Get post comments for a specific post.
     *
     * To override tfuse_get_comments() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_get_comments($return = TRUE, $post_ID) {
        $num_comments = get_comments_number($post_ID);

        if (comments_open($post_ID)) {
            if ($num_comments == 0) {
                $comments = __('No Comments');
            } elseif ($num_comments > 1) {
                $comments = $num_comments . __(' Comments');
            } else {
                $comments = "1 Comment";
            }
            $write_comments = '<a class="link-comments" href="' . get_comments_link($post_ID) . '">' . $comments . '</a>';
        } else {
            $write_comments = __('Comments are off');
        }
        if ($return)
            return $write_comments;
        else
            echo $write_comments;
    }

endif;


if (!function_exists('tfuse_shortcode_content')) :
    /**
     *  Get post comments for a specific post.
     *
     * To override tfuse_shortcode_content() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_shortcode_content($position, $return = false)
    {
        global $wp_query;
        $ID = $wp_query->queried_object->ID;

        $page_shortcodes = '';

        if (is_singular()) {

            $page_shortcodes = tfuse_page_options('content_'.$position, '', $ID);
        }

        $page_shortcodes = tfuse_qtranslate($page_shortcodes);

        $page_shortcodes = apply_filters('themefuse_shortcodes', $page_shortcodes);

        if ($return)
            return $page_shortcodes; else
            echo $page_shortcodes;
    }

// End function tfuse_shortcode_content()
endif;


if (!function_exists('tfuse_category_on_front_page')) :
    /**
     * Dsiplay homepage category
     *
     * To override tfuse_category_on_front_page() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_category_on_front_page()
    {
        if ( !is_front_page() ) return;

        global $is_tf_front_page,$homepage_categ;
        $is_tf_front_page = false;

        $homepage_category = tfuse_options('homepage_category');
        $homepage_category = explode(",",$homepage_category);
        foreach($homepage_category as $homepage)
        {
            $homepage_categ = $homepage;
        }

        if($homepage_categ == 'specific')
        {
            $is_tf_front_page = true;
            $archive = 'archive.php';
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            $specific = tfuse_options('categories_select_categ');

            $items = get_option('posts_per_page');
            $ids = explode(",",$specific);
            $posts = array(); $num_post = 0;
            foreach ($ids as $id){
                $posts[] = get_posts(array('category' => $id));
            }
            foreach($posts as $post)
            {
                $num_posts = count($post);
                $num_post += $num_posts;
            }
            $max = $num_post/$items;
            if($num_posts%$items) $max++;

            $args = array(
                'cat' => $specific,
                'orderby' => 'date',
                'paged' => $paged
            );
            query_posts($args);


            wp_localize_script(
                'tf-load-posts',
                'nr_posts',
                array(
                    'max' => $max
                )
            );

            include_once(locate_template($archive));

            return;
        }
        elseif($homepage_categ == 'page')
        {
            global $front_page;
            $is_tf_front_page = true;
            $front_page = true;
            $archive = 'page.php';
            $page_id = tfuse_options('home_page');

            $args=array(
                'page_id' => $page_id,
                'post_type' => 'page',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'ignore_sticky_posts'=> 1
            );
            query_posts($args);
            include_once(locate_template($archive));
            wp_reset_query();
            return;
        }
        elseif($homepage_categ == 'all')
        {
            $archive = 'archive.php';

            $is_tf_front_page = true;
            wp_reset_postdata();
            include_once(locate_template($archive));
            return;
        }

    }

// End function tfuse_category_on_front_page()
endif;


if (!function_exists('tfuse_action_footer')) :
    /**
     * Dsiplay footer content
     *
     * To override tfuse_action_footer() in a child theme, add your own tfuse_action_footer()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_action_footer()
    {

        if ( !tfuse_options('enable_footer_shortcodes') )
        {
            ?>
        <div class="widgetarea f_col_1">
            <?php dynamic_sidebar('footer-1'); ?>
        </div><!--/ footer col 1 -->

        <div class="widgetarea f_col_2">
            <?php dynamic_sidebar('footer-2'); ?>
        </div><!--/ footer col 2 -->

        <div class="widgetarea f_col_3">
            <?php dynamic_sidebar('footer-3'); ?>
        </div><!--/ footer col 3 -->

        <?php
        } 
        else
        {
            $footer_shortcodes = tfuse_options('footer_shortcodes');
            echo $page_shortcodes = apply_filters('themefuse_shortcodes', $footer_shortcodes);
        } 
    }

    add_action('tfuse_footer', 'tfuse_action_footer');
endif;


if (!function_exists('tfuse_social_footer')) :
    /**
     * Display social icons in footer
     *
     * To override tfuse_category_on_front_page() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_social_footer()
    {
        $template_directory = get_template_directory_uri() . '/';

        $rss_url = tfuse_options('feedburner_url',null);
        $fb_url = tfuse_options('facebook',null);
        $twitter_url = tfuse_options('twitter',null);

        if( ($rss_url == null) && ($fb_url == null) && ($twitter_url == null) ) return;

        ?>
    <div class="footer_social">
        <?php if ( $fb_url != null){?><a href="<?php echo $fb_url; ?>"><img src="<?php echo $template_directory ;?>images/icons/social_facebook_16.png" alt="" width="16" height="16"></a><?php } ?>
        <?php if ( $twitter_url != null){?><a href="<?php echo $twitter_url; ?>"><img src="<?php echo $template_directory ;?>images/icons/social_twitter_16.png" alt="" width="16" height="16"></a><?php } ?>
        <?php if ( $rss_url != null){?><a href="<?php echo $rss_url; ?>"><img src="<?php echo $template_directory ;?>images/icons/social_rss_16.png" alt="" width="16" height="16"></a><?php } ?>
        <div class="clear"></div>
    </div>
    <?php
    }

endif;




if (!function_exists('tfuse_shorten_string')) :
    /**
     *
     *
     * To override tfuse_shorten_string() in a child theme, add your own tfuse_shorten_string()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_shorten_string($string, $wordsreturned)

    {
        $retval = $string;

        $array = explode(" ", $string);
        if (count($array)<=$wordsreturned)

        {
            $retval = $string;
        }
        else

        {
            array_splice($array, $wordsreturned);
            $retval = implode(" ", $array)." ...";
        }
        return $retval;
    }

endif;


if (!function_exists('tfuse_header_background')) :
    /**
     * Display style for header
     *
     * To override tfuse_header_background() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_header_background()
    {
        global $TFUSE;

        $template_directory = get_template_directory_uri();

        $pattern = tfuse_options('custom_header_image',null);

        $default_pattern = tfuse_options('default_header_image',null);

        $color = tfuse_options('header_color',null);
        if(!preg_match('/^#[a-f0-9]{6}$/i', $color)){ $color = null;}

        if ($TFUSE->request->isset_GET('image')) $pattern = $template_directory . '/images/' . $TFUSE->request->GET('image');
        if ($TFUSE->request->isset_GET('color') ) $color =  '#' . $TFUSE->request->GET('color');

        $html = ' style="';
        if ( $pattern != null )
            $html .= 'background-image:url(' . $pattern . ');';
        else
            $html .= 'background-image:url(' . $template_directory. '/images/' . $default_pattern . ');';

        if ( $color != null )
            $html .= 'background-color:' . $color;
        $html .= '"';

        echo $html;
    }

endif;


if (!function_exists('encodeURIComponent')) :
    /**
     *
     *
     * To override encodeURIComponent() in a child theme, add your own encodeURIComponent()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function encodeURIComponent($str) {
        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        return strtr(rawurlencode($str), $revert);
    }

endif;


if (!function_exists('tfuse_pagination')) :
    /**
     * Display pagination to next/previous pages when applicable.
     *
     * To override tfuse_pagination() in a child theme, add your own tfuse_pagination()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function tfuse_pagination($query = '', $args = array()){

        global $wp_rewrite, $wp_query;
        $template_directory = get_template_directory_uri() . '/';

        if ( $query ) {

            $wp_query = $query;

        } // End IF Statement


        /* If there's not more than one page, return nothing. */
        if ( 1 >= $wp_query->max_num_pages )
            return false;

        /* Get the current page. */
        $current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );

        /* Get the max number of pages. */
        $max_num_pages = intval( $wp_query->max_num_pages );

        /* Set up some default arguments for the paginate_links() function. */
        $defaults = array(
            'base' => add_query_arg( 'paged', '%#%' ),
            'format' => '',
            'total' => $max_num_pages,
            'current' => $current,
            'prev_next' => false,
            'show_all' => false,
            'end_size' => 2,
            'mid_size' => 1,
            'add_fragment' => '',
            'type' => 'plain',
            'before' => '',
            'after' => '',
            'echo' => true,
        );

        /* Add the $base argument to the array if the user is using permalinks. */
        if( $wp_rewrite->using_permalinks() )
            $defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );

        /* If we're on a search results page, we need to change this up a bit. */
        if ( is_search() ) {
            $search_permastruct = $wp_rewrite->get_search_permastruct();
            if ( !empty( $search_permastruct ) )
                $defaults['base'] = user_trailingslashit( trailingslashit( get_search_link() ) . 'page/%#%' );
        }

        /* Merge the arguments input with the defaults. */
        $args = wp_parse_args( $args, $defaults );

        /* Don't allow the user to set this to an array. */
        if ( 'array' == $args['type'] )
            $args['type'] = 'plain';

        /* Get the paginated links. */
        $page_links = paginate_links( $args );

        /* Remove 'page/1' from the entire output since it's not needed. */
        $page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );

        /* Wrap the paginated links with the $before and $after elements. */
        $page_links = $args['before'] . $page_links . $args['after'];

        /* Return the paginated links for use in themes. */
        if ( $args['echo'] )
        {
            ?>
        <!-- pagination -->
        <div class="block_hr tf_pagination">
            <div class="inner">
                <?php $prev_posts = get_previous_posts_link(__('<span>PREVIOUS</span>', 'tfuse')); ?>
                <?php $next_posts = get_next_posts_link(__('<span>NEXT</span>', 'tfuse')); ?>
                <?php if ($prev_posts != '') { echo $prev_posts;} else { echo '<a class="page_prev" href="javascript:void(0);"><span>'; _e('PREVIOUS', 'tfuse'); echo '</span></a>'; }?>
                <?php if ($next_posts != '') { echo $next_posts;} else { echo '<a class="page_next" href="javascript:void(0);"><span>'; _e('NEXT', 'tfuse'); echo '</span></a>'; } ?>
                <?php echo $page_links; ?>

            </div>
        </div>
        <!--/ pagination -->
        <?php
        }
        else
            return $page_links;

    }

endif; // tfuse_pagination


if (!function_exists('next_posts_link_css')) :
    /**
     * Display pagination to next/previous pages when applicable.
     *
     * To override next_posts_link_css() in a child theme, add your own next_posts_link_css()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function next_posts_link_css() {

        return 'class="page_next"';
    }
    add_filter('next_posts_link_attributes', 'next_posts_link_css' );
endif;


if (!function_exists('previous_posts_link_css')) :
    /**
     * Display pagination to next/previous pages when applicable.
     *
     * To override previous_posts_link_css() in a child theme, add your own previous_posts_link_css()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function previous_posts_link_css() {

        return 'class="page_prev"';
    }
    add_filter('previous_posts_link_attributes', 'previous_posts_link_css' );
endif; // tfuse_pagination


if (!function_exists('tfuse_enqueue_comment_reply')) :
    /**
     *
     * To override tfuse_enqueue_comment_reply() in a child theme, add your own tfuse_enqueue_comment_reply()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
function tfuse_enqueue_comment_reply() {
    // on single blog post pages with comments open and threaded comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        // enqueue the javascript that performs in-link comment reply fanciness
        wp_enqueue_script( 'comment-reply' );
    }
}
// Hook into wp_enqueue_scripts
add_action( 'wp_head', 'tfuse_enqueue_comment_reply' );
endif;


if (!function_exists('tfuse_new_excerpt_more')) :
    /**
     *
     * To override tfuse_new_excerpt_more() in a child theme, add your own tfuse_new_excerpt_more()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
function tfuse_new_excerpt_more() {
    return '...';
}

add_filter('excerpt_more', 'tfuse_new_excerpt_more' );
endif;


if (!function_exists('tfuse_custom_excerpt_length')) :
    /**
     *
     * To override tfuse_custom_excerpt_length() in a child theme, add your own tfuse_custom_excerpt_length()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function tfuse_custom_excerpt_length( $length) {
        return 44;
    }
    add_filter( 'excerpt_length', 'tfuse_custom_excerpt_length', 99 );

endif;


if (!function_exists('tfuse_page_content')) :
    /**
     * Display post media.
     *
     * To override tfuse_page_content() in a child theme, add your own tfuse_page_content()
     * to your child theme's file.
     */
    function tfuse_page_content($arg ,$ID)
    {
       if (!is_numeric($ID)) return false;
       if ($arg == 'before')
       {
           ?>
           <!-- page content -->
           <div class="entry">
                <?php echo tfuse_page_options('before_page_content',null,$ID); ?>
           </div>
           <!--/ page content -->
           <?php
       }

    }

endif;


if (!function_exists('tfuse_get_short_text')) :
    /**
     *
     *
     * To override tfuse_get_short_text() in a child theme, add your own tfuse_get_short_text()
     * to your child theme's file.
     */
    function tfuse_get_short_text($text,$limit=20)
    {
        if (mb_strlen($text, 'UTF-8') <= $limit or (!strpos($text, ' '))) return $text;
        $explode = explode(' ',$text);
        if(sizeof($explode)<$limit) $limit = sizeof($explode);
        $string  = '';

        $dots = '...';
        if(count($explode) <= $limit){
            $dots = '';
        }
        for($i=0;$i<$limit;$i++){
            $string .= $explode[$i]." ";
        }
        if ($dots) {
            $string = substr($string, 0, mb_strlen($string, 'UTF-8'));
        }

        return $string.$dots;
    }

endif;

if (!function_exists('tfuse_get_main_attachement')) :
    /**
     *
     *
     * To override tfuse_get_main_attachement() in a child theme, add your own tfuse_get_main_attachement()
     * to your child theme's file.
     */
    function tfuse_get_short_text_custom($text,$limit=20)
    {
        $text = strip_shortcodes( $text );

        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text);
        $excerpt_length = apply_filters('excerpt_length', $limit);
        $excerpt_more = apply_filters('excerpt_more', ' ' . '...');
        $words = preg_split("/[\n\r\t ]+/", $text, $limit + 1, PREG_SPLIT_NO_EMPTY);
        if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }

        return apply_filters('wp_trim_excerpt', $text, '');
    }

endif;


if (!function_exists('tfuse_get_main_attachement')) :
    /**
     *
     *
     * To override tfuse_get_main_attachement() in a child theme, add your own tfuse_get_main_attachement()
     * to your child theme's file.
     */
    function tfuse_get_main_attachement($ID)
    {
       $attachements = tfuse_get_gallery_images($ID,TF_THEME_PREFIX . '_slider_images');
       $main_img  = false;
       foreach($attachements as $attachement)
       {
           if (isset($attachement->image_options['imgmain_check']))
           {
               $main_img = $attachement->guid;
               break;
           }
       }
        if(!$main_img && sizeof($attachements)) $main_img = array_shift(array_values($attachements))->guid;
        return $main_img;
    }

endif;


if (!function_exists('tfuse_get_property_taxonomies')) :
    /**
     *
     *
     * To override tfuse_get_property_taxonomies() in a child theme, add your own tfuse_get_property_taxonomies()
     * to your child theme's file.
     */
    function tfuse_get_property_taxonomies($ID, $in_taxonomy = '')
    {
       if(empty($ID) || (!is_numeric($ID))) return false;
       if(!empty($in_taxonomy)) $in_taxonomy = TF_SEEK_HELPER::get_post_type() . '_' . $in_taxonomy;
       global $wpdb;

        $sql = "SELECT $wpdb->terms.term_id
                 , $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.taxonomy
                FROM
                  $wpdb->term_relationships
                INNER JOIN $wpdb->term_taxonomy
                ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id
                INNER JOIN $wpdb->terms
                ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
                WHERE
                  $wpdb->term_relationships.object_id = '" . $ID . "'
                AND $wpdb->term_taxonomy.taxonomy = '" . $in_taxonomy . "'";

      $result =  $wpdb->get_results($sql, ARRAY_A );
      return $result;
    }

endif;

if (!function_exists('tfuse_get_count_properties_by_taxonomy_id')) :
    /**
     *
     *
     * To override tfuse_get_count_properties_by_taxonomy_id() in a child theme, add your own tfuse_get_count_properties_by_taxonomy_id()
     * to your child theme's file.
     */
    function tfuse_get_count_properties_by_taxonomy_id($ID)
    {
        $term_id = intval($ID);
        if (!is_numeric($term_id)) return false;
        global $wpdb;
        $term_id = (string)$term_id;

        $sql = "SELECT COUNT(*) as count
FROM
  " . TF_SEEK_HELPER::get_db_table_name() . "
WHERE
  " . TF_SEEK_HELPER::get_db_table_name() . "._terms LIKE '%". $term_id . ",%'";

        $result =  $wpdb->get_results($sql, ARRAY_A );

        return $result;
    }

endif;


if (!function_exists('tfuse_get_properties_by_taxonomy_id')) :
    /**
     *
     *
     * To override tfuse_get_properties_by_taxonomy_id() in a child theme, add your own tfuse_get_properties_by_taxonomy_id()
     * to your child theme's file.
     */
    function tfuse_get_properties_by_taxonomy_id($ID,$order_desc, $start, $final)
    {
        $term_id = intval($ID);
        if (!is_numeric($term_id)) return false;
        global $wpdb;
        $term_id = (string)$term_id;
        if(!$order_desc)
        {
            $order = '';
        }
        else
        {
            $order = ' DESC';
        }
        $sql = "SELECT " . TF_SEEK_HELPER::get_db_table_name() . ".post_id
     , " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_price
     , " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_sale_type
     , " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_reduction
     , $wpdb->posts.post_content
     , $wpdb->posts.post_title
FROM
  " . TF_SEEK_HELPER::get_db_table_name() . "
  INNER JOIN wp_posts
ON " . TF_SEEK_HELPER::get_db_table_name() . ".post_id = $wpdb->posts.ID
WHERE
  " . TF_SEEK_HELPER::get_db_table_name() . "._terms LIKE '%". $term_id . ",%' AND $wpdb->posts.post_status = 'publish' ORDER BY
  " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_price" . $order . ' LIMIT ' . $start . ', ' . $final;

        $result =  $wpdb->get_results($sql,ARRAY_A);

        return $result;
    }

endif;


if (!function_exists('tfuse_get_holiday_images')) :
    /**
     *
     *
     * To override tfuse_get_holiday_images() in a child theme, add your own tfuse_get_holiday_images()
     * to your child theme's file.
     */
    function tfuse_get_holiday_images($ID)
    {
        if(empty($ID) || (!is_numeric($ID))) return false;


        $attachments1 = tfuse_get_gallery_images($ID,TF_THEME_PREFIX . '_slider_images');
        $attachments = array();
        foreach ($attachments1 as $attachment)   :
            if( isset($attachment->image_options['imgexcludefromslider_check']) ) continue;
            $attachments[] = $attachment;
        endforeach;

        if (!count($attachments))
        {
            return false;
        }
        $output = '';
        $output .= '<a href="' . $attachments[0]->guid . '" data-rel="prettyPhoto['. $ID .']" class="link-viewimages" title="' . __("View Photos", "tfuse") . '">' . __("Photos", "tfuse") . '</a>';

        array_shift($attachments);

        foreach ($attachments as $attachment)   :
            if( TF_SEEK_POST_ATTACHMENTS::img_is_excluded_from_slider($attachment->ID) ) continue;
            $output .= '<a href="' . $attachment->guid . '" data-rel="prettyPhoto['. $ID .']"></a>';
        endforeach;

        echo $output;
    }

endif;


if (!function_exists('tfuse_property_pagination')) :
    /**
     *
     *
     * To override tfuse_property_pagination() in a child theme, add your own tfuse_property_pagination()
     * to your child theme's file.
     */
    function tfuse_property_pagination($location = 'top')
    {
        $output = '';
        echo $output;
    }

endif;

if (!function_exists('tfuse_aasort')) :
    /**
     *
     *
     * To override tfuse_aasort() in a child theme, add your own tfuse_aasort()
     * to your child theme's file.
     */
    function tfuse_aasort ($array, $key) {
        $sorter=array();
        $ret=array();
        if (!$array){$array = array();}
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        return $ret;
    }
endif;


if (!function_exists('tfuse_get_archive_link')) :
    /**
     *
     *
     * To override tfuse_get_archive_link() in a child theme, add your own tfuse_get_archive_link()
     * to your child theme's file.
     */
    function tfuse_get_archive_link ($link_html) {
        $link_html = str_replace('</a>','',$link_html);
        $link_html = str_replace('</li>','</a></li>',$link_html);
        return $link_html;
    }
    add_filter("get_archives_link", "tfuse_get_archive_link");
endif;
add_filter( 'show_recent_comments_widget_style', '__return_false' );



if (!function_exists('tfuse_breadcrumbs')) :
    /**
     *
     *
     * To override tfuse_breadcrumbs() in a child theme, add your own tfuse_breadcrumbs()
     * to your child theme's file.
     */
    function tfuse_breadcrumbs ($args = array()) {

        global $wp_query, $is_tf_front_page, $TFUSE;

        /* Set up the default arguments for the breadcrumb. */
        $defaults = array(
            'separator' => '<span class="separator">&gt;</span>',
            'before' => '<!-- breadcrumbs --><div class="breadcrumbs"><p>',
            'last_before'   => '<span>',
            'last_after'    => '</span>',
            'after' => '</div><!--/ breadcrumbs -->',
            'front_page' => true,
            'show_home' => __( 'Homepage', 'tfuse' ),
            'for_disabled'  => '<!-- breadcrumbs --><div class="breadcrumbs"></div><!--/ breadcrumbs -->',
            'echo' => true
        );
        $args = array_merge($defaults,$args);

        $html = $args['before'];
        if ($args['front_page']) $html .= '<a href="' . site_url() . '">' . $args['show_home'] . '</a>';

        if($is_tf_front_page) if($args['echo']) { echo $args['for_disabled']; return ''; }else return $args['for_disabled'];

        if (is_singular('page'))
        {   if(tfuse_page_options('disable_breadcrumbs', false, $wp_query->queried_object->ID)) if($args['echo']) { echo $args['for_disabled']; return ''; }else return $args['for_disabled'];
            if($wp_query->queried_object->post_parent != 0 )
            {
                $links = array();
                $post = get_post($wp_query->queried_object->post_parent);
                $links[] = $args['separator'] . '<a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';
                while($post->post_parent != 0)
                {
                    $post = get_post($post->post_parent);
                    $links[] = $args['separator'] . '<a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';
                }
                wp_reset_query();
                $links = array_reverse($links);
                foreach($links as $link) $html .= $link;
            }
            $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->post_title . $args['last_after'];
        }
        elseif (is_singular('post'))
        {
            $first_parent = false;
            $terms = wp_get_post_terms( $wp_query->queried_object->ID , 'category');
            if (isset($terms[0])) $first_parent = $terms[0];
            if ($TFUSE->request->isset_COOKIE('latest_category') && in_category(intval($TFUSE->request->COOKIE('latest_category')),$wp_query->queried_object->ID)) $first_parent = $TFUSE->request->COOKIE('latest_category');
            if($first_parent) :
               $links = array();
               $cat = get_term($first_parent,'category');
               $links[] = $args['separator'] . '<a href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a>';
                while($cat->parent)
                {
                    $cat = get_term($cat->parent,'category');
                    $links[] = $args['separator'] . '<a href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a>';
                };
            $links = array_reverse($links);
            foreach($links as $link) $html .= $link;
            endif;

            $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->post_title . $args['last_after'];
        }
        elseif(is_singular(TF_SEEK_HELPER::get_post_type()))
        {
            $html .= $args['separator'] . $args['last_before'] . '<a href="'.get_post_type_archive_link(TF_SEEK_HELPER::get_post_type()) .'">' . TF_SEEK_HELPER::get_option('seek_property_name_plural', 'Holidays') . '</a>' . $args['last_after'];

            $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->post_title . $args['last_after'];
        }
        elseif(is_tag())
        {
            $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->name . $args['last_after'];
        }
        elseif(is_post_type_archive(TF_SEEK_HELPER::get_post_type()))
        {
            $html .= $args['separator'] . $args['last_before'] . '<a href="javascript: return false;">' . TF_SEEK_HELPER::get_option('seek_property_name_plural','Holidays') . '</a>' . $args['last_after'];
        }
        elseif(is_archive())
        {
            if(is_category())
            {
                if(isset($wp_query->queried_object) && $wp_query->queried_object->parent != 0)
                {
                    $links = array();
                    $category = get_category($wp_query->queried_object->parent);
                    $links[] = $args['separator'] . '<a href="' . get_category_link($category->term_id) . '">' . get_cat_name($category->term_id) . '</a>';
                    while($category->category_parent)
                    {
                        $category = get_category($category->parent);
                        $links[] = $args['separator'] . '<a href="' . get_category_link($category->term_id) . '">' . get_cat_name($category->term_id) . '</a>';
                    }
                    $links = array_reverse($links);
                    foreach($links as $link) $html .= $link;
                }
                if(isset($wp_query->queried_object->name)) $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->name . $args['last_after'];
                else $html .= $args['separator'] . $args['last_before'] . __('Blog','tfuse') . $args['last_after'];
            }
            elseif(is_date())
            {
                $html .= $args['separator'] . $args['last_before'] . single_month_title(' ',false) . $args['last_after'];
            }
            elseif(is_author() && (isset($wp_query->query_vars['author_name'])))
            {
                $html .= $args['separator'] . $args['last_before'] . $wp_query->query_vars['author_name'] . $args['last_after'];
            }
            elseif(isset($wp_query->query_vars['taxonomy']) && strpos($wp_query->query_vars['taxonomy'],TF_SEEK_HELPER::get_post_type()) == 0)
            {
                $html .= $args['separator'] . $args['last_before'] . '<a href="'.get_post_type_archive_link(TF_SEEK_HELPER::get_post_type()) .'">' . TF_SEEK_HELPER::get_option('seek_property_name_plural','Holidays') . '</a>' . $args['last_after'];
                $html .= $args['separator'] . $args['last_before'] . tfuse_qtranslate($wp_query->queried_object->name) . $args['last_after'];
            }
        }
        elseif(is_search())
        {
            $html .= $args['separator'] . $args['last_before'] . __('Search Results', 'tfuse') . $args['last_after'];
        }
        elseif(is_404())
        {
            $html .= $args['separator'] . $args['last_before'] . __('404', 'tfuse') . $args['last_after'];
        }

        $html .= $args['after'];
        if($args['echo']) echo $html; else return $html;
    }
endif;



if (!function_exists('tfuse_get_link_for_breadcrumbs')) :
    /**
     *
     *
     * To override tfuse_get_link_for_breadcrumbs() in a child theme, add your own tfuse_get_link_for_breadcrumbs()
     * to your child theme's file.
     */
    function tfuse_get_link_for_breadcrumbs ($id) {
        $link_html = str_replace('</a>','',$link_html);
        $link_html = str_replace('</li>','</a></li>',$link_html);
        return $link_html;
    }
    add_filter("get_archives_link", "tfuse_get_archive_link");
endif;
add_filter( 'show_recent_comments_widget_style', '__return_false' );


if (!function_exists('tfuse_get_holiday_thumbnail')) :
    /**
     *
     *
     * To override tfuse_get_holiday_thumbnail() in a child theme, add your own tfuse_get_holiday_thumbnail()
     * to your child theme's file.
     */
    function tfuse_get_holiday_thumbnail ($ID)
    {
        $src = tfuse_page_options('thumbnail_image', null, $ID);
        //if(!$src) $src = tfuse_get_main_attachement($ID);
        if(!$src) $src = get_template_directory_uri() . '/images/default_holiday_image.jpg';
        return $src;
    }
endif;

if (!function_exists('tfuse_get_latest_offers')) :
    /**
     *
     *
     * To override tfuse_get_latest_offers() in a child theme, add your own tfuse_get_latest_offers()
     * to your child theme's file.
     */
    function tfuse_get_latest_offers ($items = 6)
    {
        $args = array(
                'post_type'     => TF_SEEK_HELPER::get_post_type(),
                'posts_per_page'    => $items
        );

        $offers = array();
        $query = new WP_Query($args);
        $i = 0;
        while($query->have_posts()):
            $query->next_post();
            $id = $query->post->ID;
            $offers[$i]['id'] = $id;
            $offers[$i]['title'] = tfuse_custom_title($id, true);
            $offers[$i]['price'] = TF_SEEK_HELPER::get_post_option('property_price', '---', $id);
            $offers[$i]['url'] = get_permalink($id);
            if(TF_SEEK_HELPER::get_post_option('property_sale_type', 1, $id) == 2) $offers[$i]['during'] = tfuse_page_options('during', 0 ,$id); else $offers[$i]['during'] = 0;
            $offers[$i]['thumb'] = tfuse_page_options('thumbnail_image', TFUSE_THEME_URI . '/images/default_holiday_image.jpg', $id);
            $offers[$i]['reduction'] = TF_SEEK_HELPER::get_post_option('property_reduction', 0, $id);
            $i++;
        endwhile;
        wp_reset_postdata();
        return $offers;
    }
endif;


if (!function_exists('tfuse_get_special_offers')) :
    /**
     *
     *
     * To override tfuse_get_special_offers() in a child theme, add your own tfuse_get_special_offers()
     * to your child theme's file.
     */
    function tfuse_get_special_offers ($items = 6)
    {
        global $wpdb;

        $sql = "SELECT " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_price as price, " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_reduction as reduction , " . TF_SEEK_HELPER::get_db_table_name() . ".post_id as id, " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_sale_type as sale_type
    FROM  " . TF_SEEK_HELPER::get_db_table_name() . "
    INNER JOIN $wpdb->posts ON " . TF_SEEK_HELPER::get_db_table_name() . ".post_id = $wpdb->posts.ID " .
            "WHERE $wpdb->posts.post_status = 'publish' and " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_reduction <> 0 and $wpdb->posts.post_type = '" . TF_SEEK_HELPER::get_post_type() . "'
    ORDER BY $wpdb->posts.post_date DESC LIMIT 0, " . intval($items);

        $offers =  $wpdb->get_results($sql );
        $result = array();
        foreach($offers as $offer) :
            $temp = array();
            $temp['id'] = $offer->id;
            $temp['price'] = $offer->price;
            $temp['reduction'] = $offer->reduction;
            $temp['title'] = tfuse_custom_title($offer->id, true);
            $temp['url'] = get_permalink($offer->id);
            if($offer->sale_type == 2) $temp['during'] = tfuse_page_options('during', 0, $offer->id); else $temp['during'] = 0;
            $temp['thumb'] = tfuse_page_options('thumbnail_image', TFUSE_THEME_URI . '/images/default_holiday_image.jpg', $offer->id);

            array_push($result, $temp);
        endforeach;
        return $result;
    }
endif;


if (!function_exists('tfuse_category_on_blog_page')) :
    /**
     * Dsiplay blogpage category
     *
     * To override tfuse_category_on_blog_page() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_category_on_blog_page()
    {
        global $is_tf_blog_page,$blogpage_categ;
        if ( !$is_tf_blog_page ) return;
        $is_tf_blog_page = false;

        $blogpage_category = tfuse_options('blogpage_category');
        $blogpage_category = explode(",",$blogpage_category);
        foreach($blogpage_category as $blogpage)
        {
            $blogpage_categ = $blogpage;
        }

        $is_tf_blog_page = true;
        $archive = 'archive.php';

        if($blogpage_categ == 'specific')
        {
            $specific = tfuse_options('categories_select_categ_blog', '');
            query_posts('cat='.$specific);
        }
        elseif($blogpage_categ == 'all')
        {
            $args = array(
                'orderby' => 'date',
            );
            query_posts($args);
        }

        include_once(locate_template($archive));
        return;
    }
// End function tfuse_category_on_blog_page()
endif;

if (!function_exists('tfuse_go_to_top')) :
    /**
     * Dsiplay blogpage category
     *
     * To override tfuse_go_to_top() in a child theme, add your own tfuse_go_to_top()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    add_action('init', 'tfuse_go_to_top');
    function tfuse_go_to_top()
    {
        global $TFUSE;
        if(!tfuse_options('disable_to_top', false)) $TFUSE->include->js_enq('scroll_to_top', true); else $TFUSE->include->js_enq('scroll_to_top', false);
    }
// End function tfuse_go_to_top()
endif;


function tfuse_change_submenu_class($menu) {
    $menu = preg_replace('/ class="sub-menu"/','/ class="submenu-1" /',$menu);
    return $menu;
}
add_filter ('wp_nav_menu','tfuse_change_submenu_class');
if(!function_exists('tfuse_feedFilter')) :
    function tfuse_feedFilter($query) {
        if ($query->is_feed) {
            add_filter('the_content', 'tfuse_feedContentFilter');
        }
        return $query;
    }
    add_filter('pre_get_posts','tfuse_feedFilter');
    function tfuse_feedContentFilter($content) {
        global $post;
        if(get_post_type($post) == TF_SEEK_HELPER::get_post_type())
            $thumb = tfuse_page_options('thumbnail_image');
        else
            $thumb = tfuse_page_options('single_image');
        $image = '';
        if($thumb) {
            $image = '<a href="'.get_permalink().'"><img align="left" src="'. $thumb .'" width="200px" height="150px" /></a>';
            echo $image;
        }
        $content = $image . $content;
        return $content;
    }
endif;
if(!function_exists('tfuse_add_custom_post_types_in_feed')) :
    function tfuse_add_custom_post_types_in_feed($qv) {
        if (isset($qv['feed']))
            $qv['post_type'] = array('post', TF_SEEK_HELPER::get_post_type());
        return $qv;
    }
    add_filter('request', 'tfuse_add_custom_post_types_in_feed');
endif;
if(!function_exists('tfuse_seek_add_tags_to_terms')) {
    function tfuse_seek_add_tags_to_terms() {
        if(!get_option('tfuse_seek_add_tags_to_terms', false))
        {
            global $wpdb;
            $sql = 'SELECT post_id, _terms, _tags FROM ' . TF_SEEK_HELPER::get_db_table_name();
            $holidays = $wpdb->get_results($sql);
            $values = '';
            foreach($holidays as $holiday)
            {
                $values .= "(" . $holiday->post_id . ", '" . $holiday->_terms . $holiday->_tags . "'),";
            }
            $values = substr_replace($values ,"",-1);
            $sql = "INSERT INTO " . TF_SEEK_HELPER::get_db_table_name() . " (post_id, _terms) VALUES " . $values ." ON DUPLICATE KEY UPDATE post_id=VALUES(post_id),_terms=VALUES(_terms)";
            $set = $wpdb->query($sql);
            if($set){
                update_option('tfuse_seek_add_tags_to_terms', true);
            }else{
                update_option('tfuse_seek_add_tags_to_terms', 'error');
            }
        }
    }
    add_action('init', 'tfuse_seek_add_tags_to_terms');
}
if(!function_exists('tfuse_change_saved_content_tabs')){
    function tfuse_change_saved_content_tabs()
    {
        if(!get_option('tfuse_change_saved_content_tabs', false))
        {
            $query = new WP_Query(array(
                'post_type'         => TF_SEEK_HELPER::get_post_type(),
                'number_posts'      => -1,
                'posts_per_page'    => -1
            ));
            foreach($query->posts as $holiday)
            {
                $tabs = tfuse_page_options('content_tabs_table', array(), $holiday->ID);
                foreach($tabs as $key => $tab)
                {
                    if(isset($tab[0]))
                    {
                        $tab['tab_title'] = $tab[0];
                        unset($tab[0]);
                    }
                    if(isset($tab[1]))
                    {
                        $tab['tab_content'] = $tab[1];
                        unset($tab[1]);
                    }
                    $tabs[$key] = $tab;
                }
                tfuse_set_page_option('content_tabs_table', $tabs, $holiday->ID);
            }
            wp_reset_query();
            update_option('tfuse_change_saved_content_tabs', true);
        }
    }
    add_action('init', 'tfuse_change_saved_content_tabs');
	
	if ( !function_exists('tfuse_img_content')):

    function tfuse_img_content(){ 
        $content = $link = '';
		$args = array(
			'numberposts'     => -1,
		); 
        $posts_array = get_posts( $args );
        $option_name = 'thumbnail_image';
		foreach($posts_array as $post):
			$featured = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID));  
			if(tfuse_page_options('thumbnail_image',false,$post->ID)) continue;
			
			if(!empty($featured))
			{
				$value = $featured[0];
				tfuse_set_page_option($option_name, $value, $post->ID);
				tfuse_set_page_option('disable_image', true , $post->ID); 
			}
			else
			{
				$args = array(
				 'post_type' => 'attachment',
				 'numberposts' => -1,
				 'post_parent' => $post->ID
				); 
				$attachments = get_posts($args);
				if ($attachments) {
				 foreach ($attachments as $attachment) { 
								$value = $attachment->guid; 
								tfuse_set_page_option($option_name, $value, $post->ID);
								tfuse_set_page_option('disable_image', true , $post->ID); 
							 }
				}
				else
				{
					$content = $post->post_content;
						if(!empty($content))
						{   
							preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content,$matches);
							if(!empty($matches))
							{
								$link = $matches[1]; 
								tfuse_set_page_option($option_name, $link , $post->ID);
								tfuse_set_page_option('disable_image', true , $post->ID);
							}
						}
				}
			}
                        
		endforeach;
			tfuse_set_option('enable_content_img',false, $cat_id = NULL);
    }
endif;

if ( tfuse_options('enable_content_img'))
{ 
    add_action('tfuse_head','tfuse_img_content');
}
}

function tfuse_set_blog_page(){
    global $wp_query,$is_tf_blog_page;
    if(isset($wp_query->queried_object->ID)) $id_post = $wp_query->queried_object->ID;
	else $id_post = 0;
    if(tfuse_options('blog_page') != 0 && $id_post == tfuse_options('blog_page')) $is_tf_blog_page = true;
}
add_action('wp_head','tfuse_set_blog_page');