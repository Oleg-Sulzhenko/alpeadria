<?php

/**
 * prettyPhoto
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 * 
 * Optional arguments:
 * title:
 * link:
 * type: link/button
 * gallery:
 * style:
 * class:
 * 
 * http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/
 */
class TFUSE_PrettyPhoto_Shortcode
{
    private static $add_script_for_code;
    private static $added = false;

    static function init()
    {
        $atts = array(
            'name' => __('PrettyPhoto', 'tfuse'),
            'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
            'category' => 5,
            'options' => array(
                array(
                    'name' => __('Title', 'tfuse'),
                    'desc' => __('Specifies the title', 'tfuse'),
                    'id' => 'tf_shc_prettyPhoto_title',
                    'value' => '',
                    'type' => 'text'
                ),
                array(
                    'name' => __('Link', 'tfuse'),
                    'desc' => __('Specifies the URL of an image', 'tfuse'),
                    'id' => 'tf_shc_prettyPhoto_link',
                    'value' => '',
                    'type' => 'text'
                ),
                array(
                    'name' => __('Type', 'tfuse'),
                    'desc' => __('Specify the type for an shortcode', 'tfuse'),
                    'id' => 'tf_shc_prettyPhoto_type',
                    'value' => 'link',
                    'options' => array(
                        'link' => __('Link', 'tfuse'),
                        'button' => __('Button', 'tfuse')
                    ),
                    'type' => 'select'
                ),
                array(
                    'name' => __('Gallery', 'tfuse'),
                    'desc' => __('Specify the name, if you want display images in gallery prettyPhoto', 'tfuse'),
                    'id' => 'tf_shc_prettyPhoto_gallery',
                    'value' => '',
                    'type' => 'text'
                ),
                array(
                    'name' => __('Style', 'tfuse'),
                    'desc' => __('Specify an inline style for an shortcode', 'tfuse'),
                    'id' => 'tf_shc_prettyPhoto_style',
                    'value' => '',
                    'type' => 'text'
                ),
                array(
                    'name' => __('Class', 'tfuse'),
                    'desc' => __('Specifies one or more class names for an shortcode. To specify multiple classes,<br /> separate the class names with a space, e.g. <b>"left important"</b>.', 'tfuse'),
                    'id' => 'tf_shc_prettyPhoto_class',
                    'value' => '',
                    'type' => 'text'
                ),
                /* add the fllowing option in case shortcode has content */
                array(
                    'name' => __('Content', 'tfuse'),
                    'desc' => __('Enter prettyPhoto Content', 'tfuse'),
                    'id' => 'tf_shc_prettyPhoto_content',
                    'value' => __('Open image with prettyPhoto', 'tfuse'),
                    'type' => 'textarea'
                ),
                array(
                    'name' => __('Add Paragraph', 'tfuse'),
                    'desc' => '',
                    'id' => 'tf_shc_prettyPhoto_paragraph',
                    'value' => true,
                    'type' => 'checkbox'
                ),
            )
        );

        tf_add_shortcode('prettyPhoto', array(__CLASS__,'tfuse_prettyPhoto'), $atts);

        add_action('init', array(__CLASS__, 'register_script'));
        add_action('wp_footer', array(__CLASS__, 'print_script'));
        add_action('wp_print_styles', array(__CLASS__, 'print_styles'));
    }

    static function register_script()
    {
        wp_register_script( 'prettyPhoto', TFUSE_ADMIN_JS . '/jquery.prettyPhoto.js', array('jquery'), '3.1.4', false );
    }

    static function print_styles()
    {
        $template_directory = get_template_directory_uri();
        //wp_register_style( 'prettyPhoto', TFUSE_ADMIN_CSS . '/prettyPhoto.css', false, '3.1.4' );
        //wp_enqueue_style( 'prettyPhoto' );


        wp_register_style( 'prettyPhoto', $template_directory.'/css/prettyPhoto.css', false, '3.1.3' );
        wp_enqueue_style( 'prettyPhoto' );
    }

    static function print_script() {
        if (!self::$add_script_for_code)
           return;

        wp_enqueue_script( 'prettyPhoto' );
    }

    static function tfuse_prettyPhoto($atts, $content = null)
    {
        self::$add_script_for_code = true;
        extract(shortcode_atts(array('title' => '',	'link' => '', 'type' => 'link', 'gallery' => '', 'style' => '', 'class' => '', 'paragraph' => ''), $atts));
        if (!self::$added)
        {
            self::$added = true;
            $beforeScript = '<script type="text/javascript">
                                jQuery(document).ready(function($) {
							        jQuery(\'a[data-rel]\').each(function() {
							        jQuery(this).attr(\'rel\', jQuery(this).data(\'rel\'));
							    });
							    jQuery("a[rel^=\'prettyPhoto\']").prettyPhoto({social_tools:false});
						        });
						        </script>';
        }
        else
        {
            $beforeScript = '';
        }
        if (empty($gallery))
            $gallery = 'p_' . rand(1, 1000);
        if (!empty($style))
            $style = 'style="' . $style . '"';
        if ($paragraph == 'true') { $pstart = '<p>'; $pend='</p>';}  else { $pstart = ''; $pend=''; }
        if ( $type == 'button' )
            return $beforeScript . $pstart . '<a href="' . $link . '" data-rel="prettyPhoto[' . $gallery . ']" class="button_link ' . $class . '" ' . $style . ' title="' . $title . '"><span>' . $content . '</span></a>' . $pend;
        else
            return $beforeScript . $pstart . '<a href="' . $link . '" data-rel="prettyPhoto[' . $gallery . ']" class="' . $class . '" ' . $style . 'title="' . $title . '" >' . $content . '</a>' . $pend;
    }
}

TFUSE_PrettyPhoto_Shortcode::init();