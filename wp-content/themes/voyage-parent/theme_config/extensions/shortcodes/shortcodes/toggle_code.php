<?php

/**
 * Toggle Content
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 */

function tfuse_toggle_content($atts, $content = null)
{
    extract(shortcode_atts(array('title' => '', 'class' => 'box'), $atts));

    if (empty($title))
        $title = __('Toggle Content (click to open)', 'tfuse');

    return '<h3 class="toggle ' . $class . '">' . $title . '<span class="ico"></span></h3>
        <div class="toggle_content boxed"><p>' . do_shortcode($content) . '</p></div>';
}

$atts = array(
    'name' => __('Toggle Content', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 8,
    'options' => array(
        array(
            'name' => __('Title', 'tfuse'),
            'desc' => __('Specifies the title of an shortcode', 'tfuse'),
            'id' => 'tf_shc_toggle_content_title',
            'value' => '',
            'type' => 'text'
        ),
        array(
            'name' => __('Class', 'tfuse'),
            'desc' => __('Specifies one or more class names for an shortcode. To specify multiple classes,<br /> separate the class names with a space, e.g. <b>"left important"</b>', 'tfuse'),
            'id' => 'tf_shc_toggle_content_class',
            'value' => '',
            'type' => 'text'
        ),
        /* add the fllowing option in case shortcode has content */
        array(
            'name' => __('Content', 'tfuse'),
            'desc' => __('Enter shortcode content', 'tfuse'),
            'id' => 'tf_shc_toggle_content_content',
            'value' => '',
            'type' => 'textarea'
        )
    )
);

tf_add_shortcode('toggle_content', 'tfuse_toggle_content', $atts);

class TFUSE_Code_Shortcode {

    static $add_script_for_code;

    static function init() {
        $atts = array(
            'name' => __('Toggle Code', 'tfuse'),
            'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
            'category' => 8,
            'options' => array(
                array(
                    'name' => __('Title', 'tfuse'),
                    'desc' => __('Specifies the title of an shortcode', 'tfuse'),
                    'id' => 'tf_shc_toggle_code_title',
                    'value' => '',
                    'type' => 'text'
                ),
                /* add the fllowing option in case shortcode has content */
                array(
                    'name' => __('Content', 'tfuse'),
                    'desc' => __('Enter shortcode content', 'tfuse'),
                    'id' => 'tf_shc_toggle_code_content',
                    'value' => '',
                    'type' => 'textarea'
                )
            )
        );

        tf_add_shortcode('toggle_code', array(__CLASS__, 'tfuse_toggle_code'), $atts);

        $atts = array(
            'name' => __('Code', 'tfuse'),
            'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
            'category' => 8,
            'options' => array(
                /* add the fllowing option in case shortcode has content */
                array(
                    'name' => __('Content', 'tfuse'),
                    'desc' => __('Enter shortcode content', 'tfuse'),
                    'id' => 'tf_shc_code_content',
                    'value' => '',
                    'type' => 'textarea'
                )
            )
        );

        tf_add_shortcode('code', array(__CLASS__, 'tfuse_code'), $atts);

        add_action('init', array(__CLASS__, 'register_script'));
        add_action('wp_footer', array(__CLASS__, 'print_script'));
        add_action('wp_print_styles', array(__CLASS__, 'print_styles'));
    }

    static function register_script() {
        $template_directory = get_template_directory_uri();

        wp_register_script('shCore', $template_directory . '/js/shCore.js', array('jquery'), '2.1.382', true);
        wp_register_script('shBrushPlain', $template_directory . '/js/shBrushPlain.js', array('jquery'), '2.1.382', true);
    }

    static function print_styles() {
        $template_directory = get_template_directory_uri();

        wp_register_style('shCore', $template_directory . '/css/shCore.css', false, '2.1.382');
        wp_enqueue_style('shCore');

        wp_register_style('shThemeDefault', $template_directory . '/css/shThemeDefault.css', false, '2.1.382');
        wp_enqueue_style('shThemeDefault');
    }

    static function print_script() {
        if (!self::$add_script_for_code)
            return;

        wp_print_scripts('shCore');
        wp_print_scripts('shBrushPlain');
        echo '<script type="text/javascript">SyntaxHighlighter.defaults[\'gutter\'] = false;SyntaxHighlighter.defaults[\'toolbar\'] = true;SyntaxHighlighter.all();</script>';
    }

    function tfuse_toggle_code($atts, $content = null) {
        self::$add_script_for_code = true;

        extract(shortcode_atts(array('title' => __('Toggle Content (click to open)', 'tfuse'), 'brush' => 'plain'), $atts));

        $out = '<h3 class="toggle box">' . $title . '<span class="ico"></span></h3>';
        $out .= '<div class="toggle_content">';
        $out .= '<pre class="brush: ' . $brush . '">';
        $out .= $content;
        $out .= '</pre>';
        $out .= '</div>';

        return $out;
    }

    static function tfuse_code($atts, $content = null) {
        self::$add_script_for_code = true;

        extract(shortcode_atts(array('brush' => 'plain'), $atts));

        return '<pre class="brush: ' . $brush . '">' . $content . '</pre>';
    }

}

TFUSE_Code_Shortcode::init();
