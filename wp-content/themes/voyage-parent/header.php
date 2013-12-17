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
        if(tfuse_options('disable_tfuse_seo_tab')) {
            wp_title( '|', true, 'right' );
            bloginfo( 'name' );
            $site_description = get_bloginfo( 'description', 'display' );
            if ( $site_description && ( is_home() || is_front_page() ) )
                echo " | $site_description";
        } else
            wp_title('');
        ?></title>
    <?php tfuse_meta(); ?>
    <link rel="profile" href="http://gmpg.org/xfn/11" />	
    <link href="http://fonts.googleapis.com/css?family=Lato:400,400italic,700|Sorts+Mill+Goudy:400,400italic" rel="stylesheet">

    <!-- Mobile viewport optimized: h5bp.com/viewport -->
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link type="text/css" media="screen" href="<?php echo get_stylesheet_uri(); ?>" rel="stylesheet">
    <link type="text/css" media="screen" href="<?php echo get_template_directory_uri() . '/screen.css'; ?>" rel="stylesheet">
    <script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo tfuse_options('feedburner_url', get_bloginfo_rss('rss2_url')); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php
        tfuse_head();
        wp_head();
        TF_SEEK_HELPER::register_search_parameters(array(
                'form_id'   => 'tfseekfid',
                'page'      => 'tfseekpage',
                'orderby'   => 'tfseekorderby'
            ));
    ?>
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
            	<form id="searchForm" action="<?php echo home_url( '/' ) ?>" method="get">
                	<input type="submit" onclick="if(!document.getElementById('stext').value) return false;" id="searchSubmit" value="" class="btn-search" >
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
        <?php tfuse_menu('default');  ?>
	<div class="clear"></div>
    </div>
</div>
<!--/ header -->
<?php tfuse_header_content('header'); ?>

    <?php
        tfuse_header_content('before_content');
        global $is_tf_blog_page;
        if($is_tf_blog_page) tfuse_category_on_blog_page();
    ?>

