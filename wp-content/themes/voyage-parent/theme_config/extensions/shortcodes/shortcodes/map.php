<?php

/**
 * Google map
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 * 
 * Optional arguments:
 * title:
 * width: 590
 * height: 365
 * lat: 0
 * long: 0
 * zoom: 12
 * type: map1, map2, map3
 * address: e.g. Chicago
 * 
 * [map lat="41.887" long="-87.630" zoom="10" type="map1" title=""]
 * 
 * [map lat="41.887" long="-87.630" zoom="10" type="map2" title="Different MapType: <span>HYBRID</span>"]
 * 
 * [map height="500" lat="41.887" long="-87.630" zoom="3" type="map3" address="Chicago" title="Satellite <span>Map with Address</span>"]
 * 
 */
class TFUSE_Map_Shortcode {

    static $add_script;

    static function init() {

        $atts = array(
            'name' => __('Map', 'tfuse'),
            'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
            'category' => 10,
            'options' => array(
                array(
                    'name' => __('Width', 'tfuse'),
                    'desc' => __('Specifies the width of the map', 'tfuse'),
                    'id' => 'tf_shc_map_width',
                    'value' => '590',
                    'type' => 'text'
                ),
                array(
                    'name' => __('Height', 'tfuse'),
                    'desc' => __('Specifies the height of the map', 'tfuse'),
                    'id' => 'tf_shc_map_height',
                    'value' => '365',
                    'type' => 'text'
                ),
                array(
                    'name' => __('Map position', 'tfuse'),
                    'desc' => __('Choose your map location', 'tfuse'),
                    'id' => 'tf_shc_map_position',
                    'value' => '',
                    'type' => 'maps'
                ),
                array(
                    'name' => __('Zoom', 'tfuse'),
                    'desc' => __('Specifies the zooming of the map', 'tfuse'),
                    'id' => 'tf_shc_map_zoom',
                    'value' => '3',
                    'type' => 'text'
                ),
                array(
                    'name' => __('Type', 'tfuse'),
                    'desc' => __('Specifies the type of the map', 'tfuse'),
                    'id' => 'tf_shc_map_type',
                    'value' => '',
                    'options' => array(
                        'map1' => '1',
                        'map2' => '2',
                        'map3' => '3'
                    ),
                    'type' => 'select'
                ),
                array(
                    'name' => __('Address', 'tfuse'),
                    'desc' => __('Specifies the address of the map', 'tfuse'),
                    'id' => 'tf_shc_map_address',
                    'value' => '',
                    'type' => 'text'
                ),
                array(
                    'name' => __('Title', 'tfuse'),
                    'desc' => __('Specifies the title of the map', 'tfuse'),
                    'id' => 'tf_shc_map_title',
                    'value' => '',
                    'type' => 'text'
                )
            )
        );

        tf_add_shortcode('map', array(__CLASS__, 'handle_shortcode'), $atts);

        add_action('init', array(__CLASS__, 'register_script'));
        add_action('wp_footer', array(__CLASS__, 'print_script'));
    }

    static function register_script() {
        $template_directory = get_template_directory_uri();
        wp_register_script('maps.google.com', 'https://maps.googleapis.com/maps/api/js?sensor=false&language=en', array('jquery'), '3.3.0', false);
        wp_register_script('maps.info_box', $template_directory . '/js/infobox.js', array('jquery'), '1.1.5', false);
    }

    static function print_script() {
        if (!self::$add_script)
            return;

        wp_print_scripts('maps.google.com');
        wp_print_scripts('maps.info_box');

    }

    function handle_shortcode($atts) {
        global $TFUSE;

        self::$add_script = true;

        extract(shortcode_atts(array('width' => 590, 'height' => 365, 'position'=>'0:0', 'zoom' => 12, 'type' => '', 'address' => '', 'title' => ''), $atts));
        $coords = explode(':', $position);
        if(sizeof($coords)<2) return '';
        $lat = $coords[0];
        $long = $coords[1];
        $return = '';
        $rand = rand(1, 1000);
        $TFUSE->include->js_enq('tf_map_shortcode_'.$rand , $atts );
        $width = (int) $width;
        $height = (int) $height;

        if (!empty($title))
            $return = '<h2>' . $title . '</h2>';
        $return .='<div id="map' . $rand . '" class="map frame_box" style="width: ' . $width . 'px; height: ' . $height . 'px; border: 1px solid #ccc; overflow: hidden;"></div>';
        $return .= '<style type="text/css">
    #map' . $rand . ' img {
        max-width: none !important;
    }
        /* Bootstrap Css Map Fix*/
    #map' . $rand . ' label {
        width: auto; display:inline !important;
    }
    #map' . $rand . ' { height: 100% }
</style>';
        if ($type == 'map2') {
            $return .= '<script type="text/javascript">
                                function initialize_' . $rand . '()
                                {
                                    if(maps['. $rand .'] === undefined) {
                                         var gmap_options = {
                                            center: new google.maps.LatLng(' . $lat . ',' . $long . '),
                                            zoom: '. $zoom .',
                                            scrollwheel: true,
                                            mapTypeId: google.maps.MapTypeId.HYBRID
                                         }
                                         maps['. $rand .'] = new google.maps.Map(document.getElementById("map' . $rand . '"), gmap_options);
                                    } else {
                                        setTimeout(function(){ google.maps.event.trigger(maps['. $rand .'], "resize");  maps[' . $rand . '].setCenter(new google.maps.LatLng(' . $lat . ',' . $long . '));}, 30);
                                    }
                                }
                            jQuery(document).ready(function(){
                                google.maps.event.addDomListener(window, "load", initialize_' . $rand . ');
                            });
                        </script>';
        } elseif ($type == 'map3') {
            $return .= '<script type="text/javascript">
                            function initialize_' . $rand . '()
                                {
                                    if(maps['. $rand .'] === undefined) {
                                            var gmap_options = {
                                                center: new google.maps.LatLng(' . $lat . ',' . $long . '),
                                                title: "' . $title . '",
                                                zoom: ' . $zoom . ',
                                                mapTypeId: google.maps.MapTypeId.ROADMAP
                                                }
                                            maps[' . $rand . '] = new google.maps.Map(document.getElementById("map' . $rand . '"), gmap_options);

		                                    var marker = new google.maps.Marker({
                                                            map: maps['. $rand .'],
                                                            draggable: true,
                                                            position: new google.maps.LatLng(' . $lat . ',' . $long . '),
                                                            visible: true
                                                        });

                                                        var boxText = document.createElement("div");
                                                        boxText.style.cssText = "border: 1px solid black; margin-top: 8px; background: white; width: 150px; padding: 5px;";
                                                        boxText.innerHTML = "'. $address .'";

                                                        var myOptions = {
                                                             content: boxText
                                                        };

                                                        google.maps.event.addListener(marker, "click", function (e) {
                                                            ib.open(maps['. $rand .'] , this);
                                                        });

                                                        var ib = new InfoBox(myOptions);
                                                        ib.open(maps['. $rand .'] , marker);

                                        }else{
                                            setTimeout(function(){ google.maps.event.trigger(maps['. $rand .'], "resize");  maps[' . $rand . '].setCenter(new google.maps.LatLng(' . $lat . ',' . $long . ')); }, 30);
                                    }
                                }
                            jQuery(document).ready(function(){
                                google.maps.event.addDomListener(window, "load", initialize_' . $rand . ');
                            });
                        </script>';
        } else {
            $return .= '<script type="text/javascript">

                            function initialize_' . $rand . '()
                                {
                                    if(maps['. $rand .'] === undefined) {
                                         var gmap_options = {
                                            center: new google.maps.LatLng(' . $lat . ',' . $long . '),
                                            zoom: '. $zoom .',
                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                         }
                                         maps['. $rand .'] = new google.maps.Map(document.getElementById("map' . $rand . '"), gmap_options);
                                    } else {
                                        setTimeout(function(){ google.maps.event.trigger(maps['. $rand .'], "resize");  maps[' . $rand . '].setCenter(new google.maps.LatLng(' . $lat . ',' . $long . ')); }, 30);
                                    }
                                }
                          jQuery(document).ready(function(){
                            google.maps.event.addDomListener(window, "load", initialize_' . $rand . ');
                         });
                        </script>';
        }

        return $return;
    }

}

TFUSE_Map_Shortcode::init();
