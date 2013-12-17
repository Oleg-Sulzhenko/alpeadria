<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


if( !function_exists('tf_seek_post_type_options__html_script_google_maps_input') ):
    function tf_seek_post_type_options__html_script_google_maps_input($map_id, $input_id_lat, $input_id_lng){
        ob_start();

        ?>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false"></script>

    <div style="height:500px;width:500px;" id="<?php print $map_id; ?>"></div>

    <script type="text/javascript">
        jQuery(document).ready(function($){

            new (function(){
                this.map            = null;
                this.lat_element    = $('input#<?php print $input_id_lat; ?>');
                this.lng_element    = $('input#<?php print $input_id_lng; ?>');
                this.mapDiv         = $('#<?php print $map_id; ?>');
                this.marker         = null;

                this.__construct = function(){

                    if(This.map !== null){
                        return;
                    }

                    var getFloatVal = function(value){
                        value = parseFloat(value);

                        if(String(value) == 'NaN'){
                            value = 0;
                        }

                        return value;
                    }

                    // ------------

                    var coods   = {
                        lat:    getFloatVal( This.lat_element.val() ),
                        lng:    getFloatVal( This.lng_element.val() )
                    }

                    var myLatlng    = new google.maps.LatLng( coods.lat, coods.lng);
                    var myOptions   = {
                        zoom: 4,
                        center: myLatlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        streetViewControl: false
                    }
                    This.map = new google.maps.Map(document.getElementById("<?php print $map_id; ?>"), myOptions);

                    This.marker = new google.maps.Marker({
                        position: myLatlng,
                        map: This.map,
                        icon: new google.maps.MarkerImage('<?php bloginfo('template_directory'); ?>/images/gmap_marker.png',
                            new google.maps.Size(34, 40),
                            new google.maps.Point(0,0),
                            new google.maps.Point(16, 40)
                        ),
                        draggable: true,
                        animation: google.maps.Animation.DROP
                    });

                    // ------------

                    var placeMarker = function(position, noUpdateInputs) {
                        if(noUpdateInputs === undefined){
                            noUpdateInputs = false;
                        }

                        coods.lat = position.lat();
                        coods.lng = position.lng();

                        if(!noUpdateInputs){
                            This.lat_element.val( String( coods.lat ) );
                            This.lng_element.val( String( coods.lng ) );
                        }

                        This.marker.setPosition(position);
                        // This.map.setCenter(position);
                    }
                    google.maps.event.addListener(This.map, 'click', function(event) {
                        placeMarker(event.latLng);
                    });
                    google.maps.event.addListener(This.marker, 'dragend', function(event) {
                        placeMarker(event.latLng);
                    });


                    // ------------
                    var change_input = function(){
                        coods   = {
                            lat:    getFloatVal( This.lat_element.val() ),
                            lng:    getFloatVal( This.lng_element.val() )
                        }

                        var newLatlng    = new google.maps.LatLng( coods.lat, coods.lng);

                        placeMarker(newLatlng, true);
                        This.map.setCenter(newLatlng);
                    }
                    This.lat_element.bind('blur change keyup', change_input);
                    This.lng_element.bind('blur change keyup', change_input);
                };

                // -----------------
                var This = this;

                $('#seek_property_maps_has_position').bind('change', function(){

                    if( $(this).is(':checked') ){
                        This.lat_element.removeAttr('disabled').closest('.option').fadeIn();
                        This.lng_element.removeAttr('disabled').closest('.option').fadeIn();
                        This.mapDiv.closest('.option').fadeIn();
                    } else {
                        This.lat_element.attr('disabled', 'disabled').closest('.option').fadeOut();
                        This.lng_element.attr('disabled', 'disabled').closest('.option').fadeOut();
                        This.mapDiv.closest('.option').fadeOut();
                    }
                }).trigger('change');

                if(This.mapDiv.is(":visible")){
                    This.__construct();
                }

                (function(){ // Fix map shift in hidden elements
                    var resizeFunction  = function(){
                        google.maps.event.trigger(This.map, 'resize');

                        if(This.marker !== null){
                            This.map.setCenter( This.marker.getPosition() );
                        }
                    };
                    var mapDivState     = This.mapDiv.is(":visible");
                    var click_function  = function(){
                        var newState = This.mapDiv.is(":visible");

                        if(This.map === null && newState){
                            This.__construct();
                        }

                        if(mapDivState != newState){
                            mapDivState = newState;

                            if(newState){
                                resizeFunction();
                            }
                        }
                    };

                    $(document.body).bind('click', click_function);

                    var interval = setInterval(function(){ // wait until tabs are loaded
                        var tabs = $('.ui-tabs-nav', This.mapDiv.closest('.tf_meta_tabs'));
                        mapDivState = false;
                        if( tabs.length ){
                            $('a', tabs).click(click_function);
                            click_function();
                            clearInterval(interval);
                        }
                    }, 1000);
                })();

            })();
        });
    </script>
    <?php

        return ob_get_clean();
    }
endif;

/* ----------------------------------------------------------------------------------- */

/* HELP: Option structure */
array(
    'name'          => __('Price', 'tfuse'),
        // This is used as label
    'pluralization' => array(
        // if item value is int, you can show name in plural or singular (abbreviated or not) depends on value
        // user helper function to show proper name: TF_SEEK_HELPER::get_property_pluralization_name(...)
        'single'        => __('Asking Price', 'tfuse'),
        'plural'        => __('Asking Price', 'tfuse'),
        'single_abbr'   => __('Price', 'tfuse'),
        'plural_abbr'   => __('Price', 'tfuse')
    ),
    'desc'          => sprintf(__('Enter the %s price without the currency symbol. You can <a href="admin.php?page=themefuse">change the global currency options</a> in the Fuse Framework.', 'tfuse'), mb_strtolower(TF_SEEK_HELPER::get_option('seek_property_name_singular'), 'UTF-8')),
        // description shown near option when editing post
    'id'            => 'seek_property_price',
        // unique option id
    'value'         => '1',
        // default value
    'type'          => 'text',
        // input type
    'searchable'    => TRUE,
        // set this to true if you want to make this option searchable
        // if set tot true, for this option will be created a column in seek index table in database
        // / then you can use its id and make sql in some sql generator for some form items
        // Attention!!! once column is created in database table for this option id, it can't be deleted automacaly
        // / you have to delete it manually from database table
    'valtype'       => 'int',
        // set valtype for mysql comun in seek index table if you set this option as searchable
        // available values: 'int', 'varchar','date'
);
/* ^ */

$options = array(
    /* Post Media */
    array(
        'name'          => sprintf(__('%s details','tfuse'), ucfirst (TF_SEEK_HELPER::get_option('seek_property_name_singular'))),
        'id'            => 'seek_media',
        'type'          => 'metabox',
        'context'       => 'normal'
    ),
    // Slider Images
    array('name' => __('Images', 'tfuse'),
        'desc' => __('Manage the holiday images by pressing the "Upload" button. These images will automatically form the slider from the property detail page', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_slider_images',
        'value' => '',
        'type' => 'multi_upload',
        'divider'   =>true,
        'searchable'    => FALSE,
    ),
    // Thumbnail Image
    array('name' => __('Thumbnail <br>(300px x 210px)', 'tfuse'),
        'desc' => __('This is the thumbnail for your ', 'tfuse'). mb_strtolower(TF_SEEK_HELPER::get_option('seek_property_name_singular'), 'UTF-8') . ' and it will be displayed in various sliders on the website and in the property listings. Upload one from your computer, or specify an online address for your image (Ex: http://yoursite.com/image.png).',
        'id' => TF_THEME_PREFIX . '_thumbnail_image',
        'value' => '',
        'type' => 'upload',
        'divider'   =>true,
        'searchable'    => FALSE,
    ),
    array(
        'name'          => sprintf(__('%s type', 'tfuse'), TF_SEEK_HELPER::get_option('seek_property_name_singular')),
        'pluralization' => array(
            'single'        => __('Sale type', 'tfuse'),
            'plural'        => __('Sale type', 'tfuse'),
            'single_abbr'   => __('', 'tfuse'),
            'plural_abbr'   => __('', 'tfuse')
        ),
        'desc'          => sprintf(__('Choose the type for this %s', 'tfuse'), mb_strtolower(TF_SEEK_HELPER::get_option('seek_property_name_singular'), 'UTF-8')),
        'id'            => 'seek_property_sale_type',
        'value'         => TF_SEEK_HELPER::get_option('seek_property_default_offer_type'),
        'type'          => 'select',
        'options'       => array(
            1 => __('Regular', 'tfuse'),
            2 => __('Package', 'tfuse')
        ),
        'searchable'    => TRUE,
        'valtype'       => 'int',
        'template_zone' => '',
        'template_zone_priority' => 0,
        'divider'       =>true
    ),
    // How many nights?
    array('name' => __('How many nights?', 'tfuse'),
        'desc' => __('Input the number of nights for the ', 'tfuse') . mb_strtolower(TF_SEEK_HELPER::get_option('seek_property_name_singular'), 'UTF-8'),
        'id' => TF_THEME_PREFIX . '_during',
        'value' => '7',
        'type' => 'text',
        'searchable'    => FALSE,
        'divider'   =>true,
    ),
    array(
        'name'          => __('It includes', 'tfuse'),
        'desc'          => __('Choose what the ', 'tfuse') . mb_strtolower(TF_SEEK_HELPER::get_option('seek_property_name_singular'), 'UTF-8') . ' includes',
        'id'            => 'seek_property_includes',
        'value'         => '1',
        'type'          => 'select',
        'options'       => array(
            1 => __('Without Dining', 'tfuse'),
            2 => __('Breakfast', 'tfuse'),
            3 => __('Half Board', 'tfuse'),
            4 => __('All Inclusive', 'tfuse')
        ),
        'searchable'    => TRUE,
        'valtype'       => 'int',
        'divider'       =>true
    ),
    // Period
    array(
        'id' =>  'seek_property_availability_from',
        'type'=> 'datepicker',
        'name'=>__('Availability', 'tfuse'),
        'desc' => __('The holiday will not be displayed on the website if the availability date passed. Leave blank and the holiday will newer expire.', 'tfuse'),
        'searchable'    => TRUE,
        'valtype'       => 'date',
        'divider'       => TRUE,
        'value'=>'',
        'properties'=>array(
            'class' => 'tf_rf_exclude_interval',
        ),
        'popbox' =>array(
            'with_datepickers' => array('tfuse_seek_availability_from','tfuse_seek_availability_to'),
            'dependancy' => array(
                'tfuse_datepicker_from' => 'tfuse_seek_availability_from',
                'tfuse_datepicker_to' => 'tfuse_seek_availability_to'
            ),
            array(
                'type' => 'text',
                'name' => __('Date From', 'tfuse'),
                'id' => 'tfuse_seek_availability_from',

                'value' => '',
                'desc' => '',
                'properties' => array(
                    'class' => 'tf_exclude_from_datepicker'
                )
            ),
            array(
                'type' => 'text',
                'name' => __('Date To', 'tfuse'),
                'id' => 'tfuse_seek_availability_to',

                'value' => '',
                'desc' => '',
                'properties' => array(
                    'class' => 'tf_exclude_to_datepicker'
                )
            ),
        ),
    ),
    // Period
    array(
        'id' =>  'seek_property_availability_to',
        'type'=> 'datepicker',
        'name'=>__('Availability', 'tfuse'),
        'desc' => __('The holiday will not be displayed on the website if the availability date passed. Leave blank and the holiday will newer expire.', 'tfuse'),
        'searchable'    => TRUE,
        'valtype'   =>'date',
        'value'=>'',
        'properties'=>array(
            'class' => 'tfuse_seek_hidden_date_out',
        )
    ),
    // Promo
    array('name' => __('Promo deal?', 'tfuse'),
        'desc' => __('Select Yes if you want to offer a discount % for this holiday', 'tfuse'),
        'id' => TF_THEME_PREFIX . '_is_promo',
        'value' => 'false',
        'type' => 'checkbox',
        'searchable'    => FALSE,
        'divider'       =>true
    ),
    // Promo
    array('name' => __('Discount %', 'tfuse'),
        'desc' => __("Select the discount % from the initial price. Note that the discount is not applied automatically, you'll have to input the price already discounted in the Price field", 'tfuse'),
        'id' => 'seek_property_reduction',
        'value' => 0,
        'type' => 'select',
        'options'   => array( 0 =>'0', 5 => '5', 10 => '10', 15 =>'15', 20 => '20', 25 => '25', 30 =>'30', 35 => '35', 40 => '40', 45 =>'45', 50 => '50', 55 => '55', 60 =>'60', 65 => '65', 70 => '70', 75 =>'75', 80 => '80', 85 => '85', 90 => '90', 95 => '95'),
        'divider'   =>true,
        'searchable'    => TRUE,
        'valtype'       =>'int',
        'template_zone' => '',
        'template_zone_priority' => 0
    ),
    array(
        'name'          => __('Price', 'tfuse'),
        'pluralization' => array(
            'single'        => __('Asking Price', 'tfuse'),
            'plural'        => __('Asking Price', 'tfuse'),
            'single_abbr'   => __('Price', 'tfuse'),
            'plural_abbr'   => __('Price', 'tfuse')
        ),
        'desc'          => sprintf(__('Enter the %s price without the currency symbol. You can <a href="admin.php?page=themefuse">change the global currency options</a> in the Fuse Framework.', 'tfuse'), mb_strtolower(TF_SEEK_HELPER::get_option('seek_property_name_singular'), 'UTF-8')),
        'id'            => 'seek_property_price',
        'value'         => '',
        'type'          => 'text',
        'searchable'    => TRUE,
        'valtype'       => 'int',
        'template_zone' => '',
        'template_zone_priority' => 0
    )

);