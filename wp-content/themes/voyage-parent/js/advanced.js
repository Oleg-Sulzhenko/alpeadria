var selected_reduction = jQuery('#seek_property_reduction').val();
jQuery(document).ready(function() {

    jQuery('#seek_property_reduction').on('change', function(){
       selected_reduction = jQuery(this).val();
    });
    /*

    jQuery('.voyage_content_tabs_table .option-inner').css("padding", "0 0 0 0");
    jQuery('.voyage_content_tabs_table .tfbtq_first_body tr').css("padding-left", "5px");
*/
    /*jQuery('.voyage_content_tabs_table .formcontainer:first').css({width: "100%"});
    jQuery('.voyage_content_tabs_table .formcontainer:first textarea').css("max-width", "none").css("width", "100%");*/
    var $ = jQuery;
    function inArray(needle, haystack) {
        var length = haystack.length;
        for(var i = 0; i < length; i++) {
            if(haystack[i] == needle) return true;
        }
        return false;
    }

    jQuery("#slider_pause, #slider_play, #slider_slideSpeed, #slider_hideSpeed").keydown(function(event) {
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
            // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) ||
            // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault();
            }
        }
    });

    $('#voyage_framework_options_metabox .handlediv, #voyage_framework_options_metabox .hndle').hide();
    $('#voyage_framework_options_metabox .handlediv, #voyage_framework_options_metabox .hndle').hide();
    var options = new Array();

    options['voyage_header_element'] = jQuery('#voyage_header_element').val();
    jQuery('#voyage_header_element').bind('change', function() {
        options['voyage_header_element'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });

    options['voyage_before_content_element'] = jQuery('#voyage_before_content_element').val();
    jQuery('#voyage_before_content_element').bind('change', function() {
        options['voyage_before_content_element'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });

    options['voyage_page_title'] = jQuery('#voyage_page_title').val();
    jQuery('#voyage_page_title').bind('change', function() {
        options['voyage_page_title'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });


    options['slider_hoverPause'] = jQuery('#slider_hoverPause').val();
    jQuery('#slider_hoverPause').bind('change', function() {
        if (jQuery(this).next('.tf_checkbox_switch').hasClass('on'))  options['slider_hoverPause']= true;
        else  options['slider_hoverPause'] = false;
        tfuse_toggle_options(options);
    });

    options['slider_effect'] = (jQuery('#slider_sliderEffect').next('.tf_checkbox_switch').hasClass('on')) ? true : false;
    jQuery('#slider_sliderEffect').bind('change', function() {
        if (jQuery(this).next('.tf_checkbox_switch').hasClass('on'))  options['slider_effect']= true;
        else  options['slider_effect'] = false;
        tfuse_toggle_options(options);
    });

    tfuse_toggle_options(options);

    function tfuse_toggle_options(options)
    {

        jQuery('#voyage_custom_title, #voyage_select_slider, #voyage_select_slider, #voyage_search_type').parents('.option-inner').hide();
        jQuery('#voyage_header_element, #voyage_select_slider').parent().parent().parent().next().removeClass('divider');
        jQuery('#voyage_custom_title, #voyage_select_slider, #voyage_select_slider, #voyage_search_type').parents('.form-field').hide();

        switch (options['voyage_header_element'])
        {
            case 'slider' :
                jQuery('#voyage_select_slider').parents('.option-inner').show();
                jQuery('#voyage_select_slider').parents('.form-field').show();
                jQuery('#voyage_select_slider').parent().parent().parent().next().addClass('divider');
                break;
            default :
                jQuery('#voyage_header_element').parent().parent().parent().next().addClass('divider');
        }

        switch (options['voyage_before_content_element'])
        {
            case 'search' :
                jQuery('#voyage_search_type').parents('.option-inner').show();
                jQuery('#voyage_search_type').parents('.form-field').show();
                break;
        }

        if(options['voyage_page_title'] == 'custom_title')
        {
            jQuery('#voyage_custom_title').parents('.option-inner').show();
            jQuery('#voyage_custom_title').parents('.form-field').show();
        }

        if (options['slider_hoverPause'])
        {
            jQuery('.slider_pause').show();
            jQuery('.slider_pause').next('.tfclear').show();
        }
        else
        {
            jQuery('.slider_pause').hide();
            jQuery('.slider_pause').next('.tfclear').hide();
        }

        if (options['slider_effect'])
        {
            jQuery('.slider_slideEasing').show();
            jQuery('.slider_slideEasing').next('.tfclear').show();
        }
        else
        {
            jQuery('.slider_slideEasing').hide();
            jQuery('.slider_slideEasing').next('.tfclear').hide();
        }
    }
	
	$('.tfuse_selectable_code').live('click', function () {
        var r = document.createRange();
        var w = $(this).get(0);
        r.selectNodeContents(w);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(r);
    });
    $('#tf_rf_form_name_select').change(function(){
        $_get=getUrlVars();
        if($(this).val()==-1 && 'formid' in $_get){
            delete $_get.formid;
        } else if($(this).val()!=-1){
            $_get.formid=$(this).val();
        }
        $_url_str='?';
        $.each($_get,function(key,val){
            $_url_str +=key+'='+val+'&';
        })
        $_url_str = $_url_str.substring(0,$_url_str.length-1);
        window.location.href=$_url_str;
    });


    function getUrlVars() {
        urlParams = {};
        var e,
            a = /\+/g,
            r = /([^&=]+)=?([^&]*)/g,
            d = function (s) {
                return decodeURIComponent(s.replace(a, " "));
            },
            q = window.location.search.substring(1);
        while (e = r.exec(q))
            urlParams[d(e[1])] = d(e[2]);
        return urlParams;
    }

    /*Front Page*/
    if($('#voyage_use_page_options').is(':checked')) jQuery('#homepage-header').hide();
    jQuery('#voyage_use_page_options').bind('change',function () {
        if(jQuery(this).is(':checked'))
            jQuery('#homepage-header').hide();
        else
            jQuery('#homepage-header').show();
    });
    front_page_options = [];
    front_page_options['front_page_category'] = jQuery('#voyage_homepage_category').val();
    jQuery('#voyage_homepage_category').bind('change', function(){
        front_page_options['front_page_category'] = jQuery(this).val();
        tfuse_toggle_options_front_page(front_page_options);
    });

    tfuse_toggle_options_front_page(front_page_options);

    function tfuse_toggle_options_front_page(options)
    {
        jQuery('#voyage_categories_select_categ_entries, #voyage_home_page, #voyage_use_page_options').parents('.option-inner').hide();
        jQuery('#voyage_categories_select_categ_entries, #voyage_home_page, #voyage_use_page_options').parents('.form-field').hide();
        jQuery('#homepage-header').hide();
        switch (options['front_page_category'])
        {
            case 'all' :
                jQuery('#homepage-header').show();
                break;
            case 'specific' :
                jQuery('#voyage_categories_select_categ_entries').parents('.option-inner').show();
                jQuery('#voyage_categories_select_categ_entries').parents('.form-field').show();
                jQuery('#homepage-header').show();
                break;
            case 'page' :
                jQuery('#voyage_home_page, #voyage_use_page_options').parents('.option-inner').show();
                jQuery('#voyage_home_page, #voyage_use_page_options').parents('.form-field').show();
                jQuery('#homepage-header').show();
                break;
        }
    }
    /*End Front Page*/

    /*Blog*/
    options['voyage_blogpage_category'] = jQuery('#voyage_blogpage_category').val();
    jQuery('#voyage_blogpage_category').bind('change', function() {
        options['voyage_blogpage_category'] = jQuery(this).val();
        tfuse_toggle_options_blog(options);
    });

    options['voyage_header_element_blog'] = jQuery('#voyage_header_element_blog').val();
    jQuery('#voyage_header_element_blog').bind('change', function() {
        options['voyage_header_element_blog'] = jQuery(this).val();
        tfuse_toggle_options_blog(options);
    });

    options['voyage_before_content_element_blog'] = jQuery('#voyage_before_content_element_blog').val();
    jQuery('#voyage_before_content_element_blog').bind('change', function() {
        options['voyage_before_content_element_blog'] = jQuery(this).val();
        tfuse_toggle_options_blog(options);
    });

    tfuse_toggle_options_blog (options);

    function tfuse_toggle_options_blog (options)
    {
        jQuery('#voyage_select_slider_blog, #voyage_search_type_blog, #voyage_categories_select_categ_blog_entries').parents('.option-inner').hide();
        jQuery('#voyage_header_element_blog, #voyage_select_slider_blog').parent().parent().parent().next().removeClass('divider');
        jQuery('#voyage_select_slider_blog, #voyage_search_type_blog, #voyage_categories_select_categ_blog_entries').parents('.form-field').hide();

        switch (options['voyage_blogpage_category'])
        {
            case 'specific' :
                jQuery('#voyage_categories_select_categ_blog_entries').parents('.option-inner').show();
                jQuery('#voyage_categories_select_categ_blog_entries').parents('.form-field').show();
                break;
        }

        switch (options['voyage_header_element_blog'])
        {
            case 'slider' :
                jQuery('#voyage_select_slider_blog').parents('.option-inner').show();
                jQuery('#voyage_select_slider_blog').parents('.form-field').show();
                jQuery('#voyage_select_slider_blog').parent().parent().parent().next().addClass('divider');
                break;
            default :
                jQuery('#voyage_header_element_blog').parent().parent().parent().next().addClass('divider');
        }

        switch (options['voyage_before_content_element_blog'])
        {
            case 'search' :
                jQuery('#voyage_search_type_blog').parents('.option-inner').show();
                jQuery('#voyage_search_type_blog').parents('.form-field').show();
                break;
        }
    }
    /*Blog*/
    
    /*Search*/
    options['voyage_header_element_search'] = jQuery('#voyage_header_element_search').val();
    jQuery('#voyage_header_element_search').bind('change', function() {
        options['voyage_header_element_search'] = jQuery(this).val();
        tfuse_toggle_options_search(options);
    });

    options['voyage_before_content_element_search'] = jQuery('#voyage_before_content_element_search').val();
    jQuery('#voyage_before_content_element_search').bind('change', function() {
        options['voyage_before_content_element_search'] = jQuery(this).val();
        tfuse_toggle_options_search(options);
    });

    tfuse_toggle_options_search (options);

    function tfuse_toggle_options_search (options)
    {
        jQuery('#voyage_select_slider_search, #voyage_search_type_search').parents('.option-inner').hide();
        jQuery('#voyage_header_element_search, #voyage_select_slider_search').parent().parent().parent().next().removeClass('divider');
        jQuery('#voyage_select_slider_search, #voyage_search_type_search').parents('.form-field').hide();

        switch (options['voyage_header_element_search'])
        {
            case 'slider' :
                jQuery('#voyage_select_slider_search').parents('.option-inner').show();
                jQuery('#voyage_select_slider_search').parents('.form-field').show();
                jQuery('#voyage_select_slider_search').parent().parent().parent().next().addClass('divider');
                break;
            default :
                jQuery('#voyage_header_element_search').parent().parent().parent().next().addClass('divider');
        }

        switch (options['voyage_before_content_element_search'])
        {
            case 'search' :
                jQuery('#voyage_search_type_search').parents('.option-inner').show();
                jQuery('#voyage_search_type_search').parents('.form-field').show();
                break;
        }
    }
    /*End Search*/

    /*404*/
    options['voyage_header_element_404'] = jQuery('#voyage_header_element_404').val();
    jQuery('#voyage_header_element_404').bind('change', function() {
        options['voyage_header_element_404'] = jQuery(this).val();
        tfuse_toggle_options_404(options);
    });

    options['voyage_before_content_element_404'] = jQuery('#voyage_before_content_element_404').val();
    jQuery('#voyage_before_content_element_404').bind('change', function() {
        options['voyage_before_content_element_404'] = jQuery(this).val();
        tfuse_toggle_options_404(options);
    });

    tfuse_toggle_options_404 (options);

    function tfuse_toggle_options_404 (options)
    {
        jQuery('#voyage_select_slider_404, #voyage_search_type_404').parents('.option-inner').hide();
        jQuery('#voyage_header_element_404, #voyage_select_slider_404').parent().parent().parent().next().removeClass('divider');
        jQuery('#voyage_select_slider_404, #voyage_search_type_404').parents('.form-field').hide();

        switch (options['voyage_header_element_404'])
        {
            case 'slider' :
                jQuery('#voyage_select_slider_404').parents('.option-inner').show();
                jQuery('#voyage_select_slider_404').parents('.form-field').show();
                jQuery('#voyage_select_slider_404').parent().parent().parent().next().addClass('divider');
                break;
            default :
                jQuery('#voyage_header_element_404').parent().parent().parent().next().addClass('divider');
        }

        switch (options['voyage_before_content_element_404'])
        {
            case 'search' :
                jQuery('#voyage_search_type_404').parents('.option-inner').show();
                jQuery('#voyage_search_type_404').parents('.form-field').show();
                break;
        }
    }
    /*End 404*/

    /*Tag*/
    options['voyage_header_element_tag'] = jQuery('#voyage_header_element_tag').val();
    jQuery('#voyage_header_element_tag').bind('change', function() {
        options['voyage_header_element_tag'] = jQuery(this).val();
        tfuse_toggle_options_tag(options);
    });

    options['voyage_before_content_element_tag'] = jQuery('#voyage_before_content_element_tag').val();
    jQuery('#voyage_before_content_element_tag').bind('change', function() {
        options['voyage_before_content_element_tag'] = jQuery(this).val();
        tfuse_toggle_options_tag(options);
    });

    tfuse_toggle_options_tag (options);

    function tfuse_toggle_options_tag (options)
    {
        jQuery('#voyage_select_slider_tag, #voyage_search_type_tag').parents('.option-inner').hide();
        jQuery('#voyage_header_element_tag, #voyage_select_slider_tag').parent().parent().parent().next().removeClass('divider');
        jQuery('#voyage_select_slider_tag, #voyage_search_type_tag').parents('.form-field').hide();

        switch (options['voyage_header_element_tag'])
        {
            case 'slider' :
                jQuery('#voyage_select_slider_tag').parents('.option-inner').show();
                jQuery('#voyage_select_slider_tag').parents('.form-field').show();
                jQuery('#voyage_select_slider_tag').parent().parent().parent().next().addClass('divider');
                break;
            default :
                jQuery('#voyage_header_element_tag').parent().parent().parent().next().addClass('divider');
        }

        switch (options['voyage_before_content_element_tag'])
        {
            case 'search' :
                jQuery('#voyage_search_type_tag').parents('.option-inner').show();
                jQuery('#voyage_search_type_tag').parents('.form-field').show();
                break;
        }
    }
    /*End Tag*/

    /*Author*/
    options['voyage_header_element_author'] = jQuery('#voyage_header_element_author').val();
    jQuery('#voyage_header_element_author').bind('change', function() {
        options['voyage_header_element_author'] = jQuery(this).val();
        tfuse_toggle_options_author(options);
    });

    options['voyage_before_content_element_author'] = jQuery('#voyage_before_content_element_author').val();
    jQuery('#voyage_before_content_element_author').bind('change', function() {
        options['voyage_before_content_element_author'] = jQuery(this).val();
        tfuse_toggle_options_author(options);
    });

    tfuse_toggle_options_author (options);

    function tfuse_toggle_options_author (options)
    {
        jQuery('#voyage_select_slider_author, #voyage_search_type_author').parents('.option-inner').hide();
        jQuery('#voyage_header_element_author, #voyage_select_slider_author').parent().parent().parent().next().removeClass('divider');
        jQuery('#voyage_select_slider_author, #voyage_search_type_author').parents('.form-field').hide();

        switch (options['voyage_header_element_author'])
        {
            case 'slider' :
                jQuery('#voyage_select_slider_author').parents('.option-inner').show();
                jQuery('#voyage_select_slider_author').parents('.form-field').show();
                jQuery('#voyage_select_slider_author').parent().parent().parent().next().addClass('divider');
                break;
            default :
                jQuery('#voyage_header_element_author').parent().parent().parent().next().addClass('divider');
        }

        switch (options['voyage_before_content_element_author'])
        {
            case 'search' :
                jQuery('#voyage_search_type_author').parents('.option-inner').show();
                jQuery('#voyage_search_type_author').parents('.form-field').show();
                break;
        }
    }
    /*End Author*/

    /*Archive*/
    options['voyage_header_element_archive'] = jQuery('#voyage_header_element_archive').val();
    jQuery('#voyage_header_element_archive').bind('change', function() {
        options['voyage_header_element_archive'] = jQuery(this).val();
        tfuse_toggle_options_archive(options);
    });

    options['voyage_before_content_element_archive'] = jQuery('#voyage_before_content_element_archive').val();
    jQuery('#voyage_before_content_element_archive').bind('change', function() {
        options['voyage_before_content_element_archive'] = jQuery(this).val();
        tfuse_toggle_options_archive(options);
    });

    tfuse_toggle_options_archive (options);

    function tfuse_toggle_options_archive (options)
    {
        jQuery('#voyage_select_slider_archive, #voyage_search_type_archive').parents('.option-inner').hide();
        jQuery('#voyage_header_element_archive, #voyage_select_slider_archive').parent().parent().parent().next().removeClass('divider');
        jQuery('#voyage_select_slider_archive, #voyage_search_type_archive').parents('.form-field').hide();

        switch (options['voyage_header_element_archive'])
        {
            case 'slider' :
                jQuery('#voyage_select_slider_archive').parents('.option-inner').show();
                jQuery('#voyage_select_slider_archive').parents('.form-field').show();
                jQuery('#voyage_select_slider_archive').parent().parent().parent().next().addClass('divider');
                break;
            default :
                jQuery('#voyage_header_element_archive').parent().parent().parent().next().addClass('divider');
        }

        switch (options['voyage_before_content_element_archive'])
        {
            case 'search' :
                jQuery('#voyage_search_type_archive').parents('.option-inner').show();
                jQuery('#voyage_search_type_archive').parents('.form-field').show();
                break;
        }
    }
    /*End Archive*/

    jQuery('#seek_property_sale_type').live('click', function(){
        change_offer_type(jQuery(this).val());
    });
    change_offer_type(jQuery('#seek_property_sale_type').val());

    function change_offer_type(option)
    {
        jQuery('#voyage_during').parents('.option-inner').hide();
        jQuery('#seek_property_sale_type').parent().parent().parent().next().removeClass('divider');
        jQuery('#seek_property_price').parents().prev('label').html('Price (' + tf_script.regular_price_suffix +')');
        jQuery('#voyage_during').parents('.form-field').hide();
        if(option != 1)
        {
            jQuery('#voyage_during').parents('.option-inner').show();
            jQuery('#seek_property_price').parents().prev('label').html('Price (' + tf_script.currency_symbol +')');
        }
    }

    jQuery('#voyage_is_promo').live('click', function(){
        set_promo(jQuery("label[for='voyage_is_promo']").hasClass('on'));
    });
    set_promo(jQuery("label[for='voyage_is_promo']").hasClass('on'));

    function set_promo(option)
    {
        if(option)
        {
            jQuery('#seek_property_reduction').val(selected_reduction);
        }
        else
        {
          jQuery('#seek_property_reduction').val(0);
        }
        jQuery('#seek_property_reduction').parents('.option-inner').hide();
        jQuery('#voyage_is_promo').parent().parent().parent().next().removeClass('divider');
        jQuery('#seek_property_reduction').parents('.form-field').hide();
        if(option == true)
        {
            jQuery('#seek_property_reduction').parents('.option-inner').show();
        }
    }
    //Date From/To
   jQuery('.seek_property_availability_from .popbox .excludedate_ok').click(function(event){
       if (jQuery(".box_content .tf_exclude_from_datepicker[name='tfuse_seek_availability_from']").val() && jQuery(".box_content .tf_exclude_to_datepicker[name='tfuse_seek_availability_to']").val())
       {
           var interval = jQuery(".box_content .tf_exclude_from_datepicker[name='tfuse_seek_availability_from']").val() + '/' + jQuery(".box_content .tf_exclude_to_datepicker[name='tfuse_seek_availability_to']").val();
           jQuery("#seek_property_availability_from, .tfuse_datepicker[name='seek_property_availability_to']").val(interval);
       }
       $(this).parent().removeClass('current');
       $(this).parent().fadeOut("fast");
       return false;
   });

    if(typenow == tf_script.seek_post_type){
        jQuery('#publish').hover( function(){
            jQuery('#seek_property_availability_to').val(jQuery('#seek_property_availability_from').val());
        });
    }
    jQuery(".tfuse_seek_hidden_date_out").parents('.option-inner').hide();

    // Show/Hide #voyage_id_map_tab
    function show_hide_id_map_tab()
    {
        if(jQuery("label[for='voyage_show_map_tab']").hasClass('on'))
        {
            jQuery('#voyage_id_map_tab').closest('.option-inner').show();
            jQuery("label[for='voyage_show_map_tab']").closest('.option-inner').parent().next('.tfclear').removeClass('divider');
            jQuery('#voyage_id_map_tab').parent().parent().parent().next().addClass('divider');
        }
        else
        {
            jQuery('#voyage_id_map_tab').closest('.option-inner').hide();
            jQuery("label[for='voyage_show_map_tab']").closest('.option-inner').parent().next('.tfclear').addClass('divider');
            jQuery('#voyage_id_map_tab').parent().parent().parent().next().removeClass('divider');
        }
    }
    show_hide_id_map_tab();
    jQuery(document).on('click', "label[for='voyage_show_map_tab']", function()
    {
        show_hide_id_map_tab();
    });

    jQuery('#voyage_id_map_tab').keydown(function(event) {
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 190 || event.keyCode == 110|| event.keyCode == 189 || event.keyCode == 109 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
            // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) ||
            // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault();
            }
        }
    });

    jQuery('#taxonomy-holiday_locations input:checkbox').bind('change', function(e){
        var term_id = jQuery(this).attr("value");
        if (jQuery(this).is(':checked'))
        {
            jQuery.ajax({
                type: "POST",
                url: tf_script.ajaxurl,
                data: "action=tfuse_ajax_get_parents&id=" + term_id,
                success: function(msg){
                    var obj = jQuery.parseJSON(msg);
                    var msg_array = jQuery.makeArray( obj );
                    jQuery('#taxonomy-holiday_locations input:checkbox').each(function()
                    {
                        if(inArray(jQuery(this).attr("value"),msg_array)) jQuery(this).attr("checked","true");

                    });
                }
            });
        }
        else
        {
            jQuery.ajax({
                type: "POST",
                url: tf_script.ajaxurl,
                data: "action=tfuse_ajax_get_childs&id=" + term_id,
                success: function(msg){
                    var obj = jQuery.parseJSON(msg);
                    var msg_array = jQuery.makeArray( obj );
                    jQuery('#taxonomy-holiday_locations input:checkbox').each(function()
                    {
                        if(inArray(jQuery(this).attr("value"),msg_array)) jQuery(this).removeAttr("checked");

                    });
                }
            });
        }

    });

    /*var tabs_options = [];
    jQuery('#slider_hoverPause').bind('change', function() {
        if (jQuery(this).next('.tf_checkbox_switch').hasClass('on'))  tabs_options['slider_hoverPause']= true;
        else  tabs_options['slider_hoverPause'] = false;
        tfuse_content_tabs(tabs_options);
    });*/
    //tfuse_content_tabs();
});