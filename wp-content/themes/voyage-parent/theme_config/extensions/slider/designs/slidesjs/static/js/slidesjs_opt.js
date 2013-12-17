jQuery(document).ready(function()
{
    var slider_effect = (tf_script.slides_options.fade) ? 'slide' : 'fade';
    jQuery('.header_slider').slides({
            generatePagination: false,
            generateNextPrev: true,
            fadeSpeed: parseInt(tf_script.slides_options.slideSpeed),
            slideSpeed: parseInt(tf_script.slides_options.slideSpeed),
            play: parseInt(tf_script.slides_options.play),
            pause: parseInt(tf_script.slides_options.pause),
            hoverPause: tf_script.slides_options.hoverPause,
            effect: slider_effect,
            slideEasing: tf_script.slides_options.slideEasing,
            crossfade: true,
            preload: false,
            preloadImage: tf_script.TFUSE_THEME_URL + '/images/loading.gif'
    });
});