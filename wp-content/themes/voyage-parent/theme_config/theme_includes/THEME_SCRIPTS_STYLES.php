<?php

add_action('wp_print_styles', 'tfuse_add_css');
add_action('wp_print_scripts', 'tfuse_add_js');

if (!function_exists('tfuse_add_css')) :

    /**
     * This function include files of css.
     */
    function tfuse_add_css() {

        wp_register_style('custom', tfuse_get_file_uri('/custom.css'), array(), false);
        wp_enqueue_style('custom');

        wp_register_style('jslider', tfuse_get_file_uri('/css/jslider.css'), array(), false);
        wp_enqueue_style('jslider');

        wp_register_style('customInput', tfuse_get_file_uri('/css/customInput.css'), array(), false);
        wp_enqueue_style('customInput');

        wp_register_style('jquery-ui-1.8.20', tfuse_get_file_uri('/css/custom-theme/jquery-ui-1.8.20.custom.css'), array(), false);
        wp_enqueue_style('jquery-ui-1.8.20');

        wp_register_style('cusel.css', tfuse_get_file_uri('/css/cusel.css'), array(), false);
        wp_enqueue_style('cusel.css');

        $tfuse_browser_detect = tfuse_browser_body_class();

        if ($tfuse_browser_detect[0] == 'ie7')
            wp_enqueue_style('ie7-style', tfuse_get_file_uri('/css/ie.css'));
    }

endif;


if (!function_exists('tfuse_add_js')) :

    /**
     * This function include files of javascript.
     */
    function tfuse_add_js() {
    
        wp_deregister_script('jquery');
        // Register the library again from Google's CDN
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), null, false);

        wp_register_script('modernizr', tfuse_get_file_uri('/js/libs/modernizr-2.5.3.min.js'));
        wp_enqueue_script('modernizr');

        wp_register_script('respond', tfuse_get_file_uri('/js/libs/respond.min.js'));
        wp_enqueue_script('respond');

//        wp_enqueue_script('jquery');

        wp_register_script('maps.google.com', 'https://maps.googleapis.com/maps/api/js?sensor=false&language=en', array('jquery'), '3.3.0', false);
        wp_register_script('maps.info_box', tfuse_get_file_uri('/js/infobox.js'), array('jquery'), '1.1.5', false);

        wp_register_script('jquery.easing', tfuse_get_file_uri('/js/jquery.easing.1.3.min.js'), array('jquery'), '1.3', false);
        wp_enqueue_script('jquery.easing');

        wp_register_script('hoverIntent', tfuse_get_file_uri('/js/hoverIntent.js'), array('jquery'), '0.1', false);
        wp_enqueue_script('hoverIntent');

        // general.js can be overridden in a child theme by copying it in child theme's js folder
        wp_register_script('general', tfuse_get_file_uri('/js/general.js'), array('jquery'), '2.0', false);
        wp_enqueue_script('general');

        // sliders 
        wp_register_script('slides.min.jquery', tfuse_get_file_uri('/js/slides.min.jquery.js'), array('jquery'), '2.0', false);
        wp_enqueue_script('slides.min.jquery');

        // range sliders
        wp_register_script('jquery.slider.bundle', tfuse_get_file_uri('/js/jquery.slider.bundle.js'), array('jquery'), '2.0', false);
        wp_enqueue_script('jquery.slider.bundle');

        wp_register_script('jquery.slider', tfuse_get_file_uri('/js/jquery.slider.js'), array('jquery'), '2.0', false);
        wp_enqueue_script('jquery.slider');

        wp_register_script('jquery.customInput', tfuse_get_file_uri('/js/jquery.customInput.js'), array('jquery'), '2.0', false);
        wp_enqueue_script('jquery.customInput');

        // datepicker
        wp_register_script('jquery-ui-1.9.2', tfuse_get_file_uri('/js/jquery-ui-1.9.2.custom.min.js'), array('jquery'), '1.9.2', false);
        wp_enqueue_script('jquery-ui-1.9.2');

        wp_register_script('jquery-ui.multidatespicker', tfuse_get_file_uri('/js/jquery-ui.multidatespicker.js'), array('jquery', 'jquery-ui-1.9.2'), '1.6.1', false);

        wp_register_script('jquery.tools', tfuse_get_file_uri('/js/jquery.tools.min.js'), array('jquery'), '1.2.5', false);
        wp_enqueue_script('jquery.tools');

        //styled select
        wp_register_script('cusel-min', tfuse_get_file_uri('/js/cusel-min.js'), array('jquery'), '2.0', false);
        wp_enqueue_script('cusel-min');

        wp_register_script('jScrollPane.min', tfuse_get_file_uri('/js/jScrollPane.min.js'), array('jquery'), '2.0', false);
        wp_enqueue_script('jScrollPane.min');

        wp_register_script('jquery.mousewheel', tfuse_get_file_uri('/js/jquery.mousewheel.js'), array('jquery'), '2.0', false);
        wp_enqueue_script('jquery.mousewheel');

        //Start Photo Gallery
        wp_register_script('jquery.galleriffic', tfuse_get_file_uri('/js/jquery.galleriffic.min.js'), array('jquery'), '2.0.1', false);
        wp_enqueue_script('jquery.galleriffic');

        wp_register_script('jquery.opacityrollover', tfuse_get_file_uri('/js/jquery.opacityrollover.js'), array('jquery'), '1.0', false);
        wp_enqueue_script('jquery.opacityrollover');
        //End Photo Gallery

        wp_register_script('jquery.prettyPhoto', tfuse_get_file_uri('/js/jquery.prettyPhoto.js'), array('jquery'), '3.1.4', true);
        wp_enqueue_script('jquery.prettyPhoto');

        wp_register_script('jquery.jcarousel', tfuse_get_file_uri('/js/jquery.jcarousel.min.js'), array('jquery'), '3.1.3', true);

        wp_register_script('contactform', tfuse_get_file_uri('/js/contactform.js'), array('jquery'), '2.0', true);

        wp_register_script('shCore', tfuse_get_file_uri('/js/shCore.js'), array('jquery'), '2.1.382', true);
        wp_enqueue_script('shCore');

        wp_register_script('shBrushPlain', tfuse_get_file_uri('/js/shBrushPlain.js'), array('jquery'), '2.1.382', true);
        wp_enqueue_script('shBrushPlain');

        wp_register_script('stellar', tfuse_get_file_uri('/js/jquery.stellar.min.js'), array('jquery'), '2.1.1', true);
        wp_enqueue_script('stellar');

        wp_register_script('holiday_reservation', tfuse_get_file_uri('/js/holiday_reservation.js'), array('jquery'), '1.0', true);

        if (!tfuse_options('disable_preload_css')) {
            wp_register_script('preloadCssImages', tfuse_get_file_uri('/js/preloadCssImages.js'), array('jquery'), '5.0', true);
            wp_enqueue_script('preloadCssImages');
        }
    }


endif;
