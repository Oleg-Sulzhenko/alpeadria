<?php

/**
 * OneByOne slider's configurations
 *
 * @since Voyage 1.0
 */

$options = array(
    'tabs' => array(
        array(
            'name' => __('Slider Settings', 'tfuse'),
            'id' => 'slider_settings', #do no t change this ID
            'headings' => array(
                array(
                    'name' => __('Slider Settings', 'tfuse'),
                    'options' => array(
                        array('name' => __('Slider Title', 'tfuse'),
                            'desc' => __('Change the title of your slider. Only for internal use (Ex: Homepage)', 'tfuse'),
                            'id' => 'slider_title',
                            'value' => '',
                            'type' => 'text',
                            'divider' => true),
                        array('name' => __('Background Color', 'tfuse'),
                            'desc' => __('Chose background color for slider', 'tfuse'),
                            'id' => 'slider_background',
                            'value' => '#343637',
                            'type' => 'colorpicker',
                            'divider' => true),                        
                        array('name' => __('Default background patterns', 'tfuse'),
                            'desc' => __('Choose an image pattern for your slider', 'tfuse'),
                            'id' => 'slider_default_pattern',
                            'value' => 'pattern_4.png',
                            'options' => array('pattern_1.jpg' => array( get_template_directory_uri() . '/images/icons/pattern1.png', __('Pattern 1', 'tfuse')), 'pattern_2.png' => array( get_template_directory_uri() . '/images/icons/pattern2.png', __('Pattern 2', 'tfuse')), 'pattern_3.png' => array( get_template_directory_uri() . '/images/icons/pattern3.png', __('Pattern 3', 'tfuse')), 'pattern_4.png' => array( get_template_directory_uri() . '/images/icons/pattern4.png', __('Pattern 4', 'tfuse')), 'pattern_5.png' => array( get_template_directory_uri() . '/images/icons/pattern5.png', __('Pattern 5', 'tfuse'))),
                            'type' => 'images',
                            'divider' => true
                        ),
                        array('name' => __('Upload background pattern', 'tfuse'),
                            'desc' => __('Upload background pattern', 'tfuse'),
                            'id' => 'slider_pattern',
                            'value' => '',
                            'type' => 'upload',
                            'divider' => true),
                        array(
                            'name' => __('Disable fade', 'tfuse'),
                            'desc' => __('Disable fade', 'tfuse'),
                            'id' => 'slider_sliderEffect',
                            'value' => false,
                            'type' => 'checkbox',
                            'divider'   => true,
                            'required' => TRUE
                        ),
                        array(
                            'name' => __('Side/Fade Speed', 'tfuse'),
                            'desc' => __('Set the speed of the sliding/fading animation in milliseconds.', 'tfuse'),
                            'id' => 'slider_slideSpeed',
                            'value' => '350',
                            'type' => 'text',
                            'required' => TRUE
                        ),
                        array(
                            'name' => __('Animation style', 'tfuse'),
                            'desc' => __('Set the easing of the sliding animation.', 'tfuse'),
                            'id' => 'slider_slideEasing',
                            'value' => 'easeInOutExpo',
                            'options' => array('linear' => __('Linear', 'tfuse'), 'swing' => __('Swing', 'tfuse'), 'easeInQuad' => __('EaseInQuad', 'tfuse'), 'easeOutQuad' => __('EaseOutQuad', 'tfuse'),
                                'easeInOutQuad' => __('EaseInOutQuad', 'tfuse'), 'easeInCubic' => __('EaseInCubic', 'tfuse'), 'easeOutCubic' => __('EaseOutCubic', 'tfuse'), 'easeInOutCubic' => __('EaseInOutCubic', 'tfuse'),
                                'easeInQuart' => __('EaseInQuart', 'tfuse'), 'easeOutQuart' => __('EaseOutQuart', 'tfuse'), 'easeInOutQuart' => __('EaseInOutQuart', 'tfuse'), 'easeInQuint' => __('EaseInQuint', 'tfuse'),
                                'easeOutQuint' => __('EaseOutQuint', 'tfuse'), 'easeInOutQuint' => __('EaseInOutQuint', 'tfuse'), 'easeInSine' => __('EaseInSine', 'tfuse'), 'easeOutSine' => __('EaseOutSine', 'tfuse'),
                                'easeInOutSine' => __('EaseInOutSine', 'tfuse'), 'easeInExpo' => __('EaseInExpo', 'tfuse'), 'easeOutExpo' => __('EaseOutExpo', 'tfuse'), 'easeInOutExpo' => __('EaseInOutExpo', 'tfuse'),
                                'easeInCirc' => __('EaseInCirc', 'tfuse'), 'easeOutCirc' => __('EaseOutCirc', 'tfuse'), 'easeInOutCirc' => __('EaseInOutCirc', 'tfuse'), 'easeInElastic' => __('EaseInElastic', 'tfuse'),
                                'easeOutElastic' => __('EaseOutElastic', 'tfuse'), 'easeInOutElastic' => __('EaseInOutElastic', 'tfuse'), 'easeInBack' => __('EaseInBack', 'tfuse'), 'easeOutBack' => __('EaseOutBack', 'tfuse'),
                                'easeInOutBack' => __('EaseInOutBack', 'tfuse'), 'easeInBounce' => __('EaseInBounce', 'tfuse'), 'easeOutBounce' => __('EaseOutBounce', 'tfuse'), 'easeInOutBounce' => __('EaseInOutBounce', 'tfuse') ),
                            'type' => 'select',
                            'required' => TRUE
                        ),
                        array(
                            'name' => __('Play', 'tfuse'),
                            'desc' => __('Autoplay slideshow, a positive number will set to true and be the time between slide animation in milliseconds', 'tfuse'),
                            'id' => 'slider_play',
                            'value' => '5000',
                            'type' => 'text',
                            'required' => TRUE
                        ),
                        array(
                            'name' => __('Hover Pause', 'tfuse'),
                            'desc' => __('Activate pause on hover.', 'tfuse'),
                            'id' => 'slider_hoverPause',
                            'value' => 'true',
                            'type' => 'checkbox',
                            'required' => TRUE
                        ),
                        array(
                            'name' => __('Pause', 'tfuse'),
                            'desc' => __('Pause slideshow on click of next/prev or pagination. A positive number will set to true and be the time of pause in milliseconds.', 'tfuse'),
                            'id' => 'slider_pause',
                            'value' => '3500',
                            'type' => 'text',
                            'required' => TRUE
                        ),
                        array('name' => __('Resize images?', 'tfuse'),
                            'desc' => __('Want to let our script to resize the images for you? Or do you want to have total control and upload images with the exact slider image size?', 'tfuse'),
                            'id' => 'slider_image_resize',
                            'value' => 'false',
                            'type' => 'checkbox')
                    )
                )
            )
        ),
        array(
            'name' => __('Add/Edit Slides', 'tfuse'),
            'id' => 'slider_setup', #do not change ID
            'headings' => array(
                array(
                    'name' => __('Add New Slide', 'tfuse'), #do not change
                    'options' => array(
                        array('name' => __('Заголовок', 'tfuse'),
                            'desc' => __('', 'tfuse'),
                            'id' => 'slide_name',
                            'value' => '',
                            'type' => 'text',
                            'divider' => true),
                        array('name' => __('Додатковий опис', 'tfuse'),
                            'desc' => '',
                            'id' => 'slide_title',
                            'value' => '',
                            'type' => 'text',
                            'divider' => true),
                        array('name' => __('Процент на знижку', 'tfuse'),
                            'desc' => 'Процент знижки який буде відображатись на слайді',
                            'id' => 'slide_subtitle',
                            'value' => '',
                            'type' => 'select',
                            'options'   => array(
                                '5' => __('5', 'tfuse'), 
                                '10' => __('10', 'tfuse'), 
                                '15' => __('15', 'tfuse'), 
                                '20' => __('20', 'tfuse'), 
                                '25' => __('25', 'tfuse'), 
                                '30' => __('30', 'tfuse'), 
                                '35' => __('35', 'tfuse'), 
                                '40' => __('40', 'tfuse'), 
                                '45' => __('45', 'tfuse'), 
                                '50' => __('50', 'tfuse'), 
                                '60' => __('60', 'tfuse'), 
                                '65' => __('65', 'tfuse'), 
                                '70' => __('70', 'tfuse'), 
                                '75' => __('75', 'tfuse'), 
                                '80' => __('80', 'tfuse'), 
                                '85' => __('85', 'tfuse'), 
                                ),
                            'divider' => true),
                        array('name' => __('Розташування тексту на фотографії', 'tfuse'),
                            'desc' => __('Choose Title and Subtitle position.', 'tfuse'),
                            'id' => 'slide_title_position',
                            'value' => 'center_top',
                            'type' => 'select',
                            'options'   => array('center top' => __('Center Top', 'tfuse'), 'left top' => __('Left Top', 'tfuse'), 'right top' => __('Right Top', 'tfuse'), 'center middle' => __('Center Middle', 'tfuse'), 'left middle' => __('Left Middle', 'tfuse'), 'right middle' => __('Right Middle', 'tfuse'), 'center bottom' => __('Center Bottom', 'tfuse'), 'left bottom' => __('Left Bottom', 'tfuse'), 'right bottom' => __('Right Bottom', 'tfuse')),
                            'divider' => true),
                        array('name' => __('Link URL', 'tfuse'),
                            'desc' => __('Set the slide link URL.', 'tfuse'),
                            'id' => 'slide_link_url',
                            'value' => '',
                            'type' => 'text',
                            'divider' => true),
                        array('name' => __('Link Target', 'tfuse'),
                            'desc' => '',
                            'id' => 'slide_link_target',
                            'value' => '',
                            'options' => array('_self' => __('Self', 'tfuse'), '_blank' => __('Blank', 'tfuse')),
                            'type' => 'select',
                            'divider' => true),
                        // Custom Favicon Option
                        array('name' => __('Image <br />(1250px × 467px)', 'tfuse'),
                            'desc' => __('You can upload an image from your hard drive or use one that was already uploaded by pressing  "Insert into Post" button from the image uploader plugin.', 'tfuse'),
                            'id' => 'slide_src',
                            'value' => '',
                            'type' => 'upload',
                            'media' => 'image',
                            'required' => TRUE)
                    )
                )
            )
        ),
        array(
            'name' => __('Category Setup', 'tfuse'),
            'id' => 'slider_type_categories',
            'headings' => array(
                array(
                    'name' => __('Category options', 'tfuse'),
                    'options' => array(
                        array(
                            'name' => __('Select specific categories', 'tfuse'),
                            'desc' => __('Pick one or more
categories by starting to type the category name. If you leave blank the slider will fetch images
from all <a target="_new" href="', 'tfuse') . get_admin_url() . 'edit-tags.php?taxonomy=category">Categories</a>.',
                            'id' => 'categories_select',
                            'type' => 'multi',
                            'subtype' => 'category'
                        ),
                        array(
                            'name' => __('Number of images in the slider', 'tfuse'),
                            'desc' => __('How many images do you want in the slider?', 'tfuse'),
                            'id' => 'sliders_posts_number',
                            'value' => 6,
                            'type' => 'text'
                        )
                    )
                )
            )
        ),
        array(
            'name' => __('Posts Setup', 'tfuse'),
            'id' => 'slider_type_posts',
            'headings' => array(
                array(
                    'name' => __('Posts options', 'tfuse'),
                    'options' => array(
                        array(
                            'name' => __('Select specific Posts', 'tfuse'),
                            'desc' => __('Pick one or more <a target="_new" href="', 'tfuse') . get_admin_url() . 'edit.php">posts</a> by starting to type the Post name. The slider will be populated with images from the posts
you selected.',
                            'id' => 'posts_select',
                            'type' => 'multi',
                            'subtype' => 'post,'.TF_SEEK_HELPER::get_post_type()
                        )
                    )
                )
            )
        ),
        array(
            'name' => __('Tags Setup', 'tfuse'),
            'id' => 'slider_type_tags',
            'headings' => array(
                array(
                    'name' => __('Tags options', 'tfuse'),
                    'options' => array(
                        array(
                            'name' => __('Select specific tags', 'tfuse'),
                            'desc' => __('Pick one or more <a target="_new" href="', 'tfuse') . get_admin_url() . 'edit-tags.php?taxonomy=post_tag">tags</a> by starting to type the tag name. The slider will be populated with images from posts
that have the selected tags.',
                            'id' => 'tags_select',
                            'type' => 'multi',
                            'subtype' => 'post_tag'
                        )
                    )
                )
            )
        )
    )
);
$options['extra_options'] = array();
?>