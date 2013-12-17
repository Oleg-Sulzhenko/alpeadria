<?php
/**
 * The template for displaying SlidesJS Slider.
 * To override this template in a child theme, copy this file to your
 * child theme's folder /theme_config/extensions/slider/designs/slidesjs/
 *
 * If you want to change style or javascript of this slider, copy files to your
 * child theme's folder /theme_config/extensions/slider/designs/slidesjs/static/
 * and change get_template_directory() with get_stylesheet_directory()
 */
$TFUSE->include->register_type('slides_js_folder', get_template_directory() . '/theme_config/extensions/slider/designs/' . $slider['design'] . '/static/js');
$TFUSE->include->js('slidesjs_opt', 'slides_js_folder', 'tf_head', 11);

$slider_options = array();
if (isset($slider['general']['slider_sliderEffect']))
    $slider_options['fade'] = true;
else
    $slider_options['fade'] = false;
if (isset($slider['general']['slider_play']))
    $slider_options['play'] = $slider['general']['slider_play'];
else
    $slider_options['play'] = 5000;
if (isset($slider['general']['slider_pause']))
    $slider_options['pause'] = $slider['general']['slider_pause'];
else
    $slider_options['pause'] = 3500;
if (isset($slider['general']['slider_hoverPause']))
    $slider_options['hoverPause'] = true;
else
    $slider_options['hoverPause'] = false;
if (isset($slider['general']['slider_slideSpeed']))
    $slider_options['slideSpeed'] = $slider['general']['slider_slideSpeed'];
else
    $slider_options['slideSpeed'] = 350;
if (isset($slider['general']['slider_slideEasing']))
    $slider_options['slideEasing'] = $slider['general']['slider_slideEasing'];
else
    $slider_options['slideEasing'] = 'easeInOutExpo';

$TFUSE->include->js_enq('slides_options', $slider_options);
$captions = array();
$pattern = (isset($slider['general']['slider_pattern']) && $slider['general']['slider_pattern'] != '') ? $slider['general']['slider_pattern'] : false;
if (!$pattern) {
    $pattern = $slider['general']['slider_default_pattern'];
    $pattern = 'url(' . TFUSE_THEME_URI . '/images/' . $pattern . ')';
}
else
    $pattern = 'url(' . $pattern . ')';
?>
<!-- header slider -->
<div class="header_slider" style="background-image:<?php echo $pattern; ?>; background-color:<?php
if (isset($slider['general']['slider_background']))
    echo $slider['general']['slider_background'];
else
    echo '#222222';
?>">

    <div class="slides_container">
<?php foreach ($slider['slides'] as $slide) : $captions[] = $slide['slide_name']; ?>
            <div class="slide">
                <img src="<?php echo $slide['slide_src']; ?>" alt="">
                <div class="slide_text <?php echo $slide['slide_title_position']; ?>">
                    <div class="slide_title"><strong><a href="<?php echo ($slide['slide_link_url']) ? $slide['slide_link_url'] : "javascript: return false;"; ?>" target="<?php echo $slide['slide_link_target']; ?>"><?php echo $slide['slide_name']; ?></a></strong></div>
                    <br/>
                    <p class="subtitle"><a href="<?php echo ($slide['slide_link_url']) ? $slide['slide_link_url'] : "javascript: return false;"; ?>" target="<?php echo $slide['slide_link_target']; ?>"><?php echo $slide['slide_title']; ?></a></p>
                </div>
                <div class="procent">
                    -<?php echo $slide['slide_subtitle']; ?>%
                </div>
            </div>  
<?php endforeach; ?>                                   

    </div>

    <div class="pagination_wrap">
        <div class="pagination_inner">
            <ul class="pagination">
                <?php foreach ($captions as $caption) : ?>
                    <li><a href="#"><?php echo $caption; ?></a></li>
<?php endforeach; ?>
            </ul>

        </div>
    </div>           
</div>
<!--/ header slider -->
