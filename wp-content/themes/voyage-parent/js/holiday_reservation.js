/**
 * Holiday Reservation Form
 *
 * To override this function in a child theme, copy this file to your child theme's
 * js folder.
 * /js/holiday_reservation.js
 *
 * @version 1.0
 */

jQuery(document).ready(function(){
    tfuse_send_reservation_email();
});

function tfuse_send_reservation_email()
{
    jQuery('.widget_holiday_reservation form .btn-submit').bind('click', function()
    {

            var field1_label = '';
            var field2_label = '';
            var field3_label = '';
            var textarea_label = '';

            var field1_v = '';
            var field2_v = '';
            var field3_v = '';
            var textarea_v = '';
            var offer_id = jQuery(this).closest('form').attr('data-rel');

            if(jQuery('#reservation_field_1').length) { field1_label = jQuery('label[for="reservation_field_1"]').html(); field1_v = jQuery('#reservation_field_1').val();}
            if(jQuery('#reservation_field_2').length) { field2_label = jQuery('label[for="reservation_field_2"]').html(); field2_v = jQuery('#reservation_field_2').val();}
            if(jQuery('#reservation_field_3').length) { field3_label = jQuery('label[for="reservation_field_3"]').html(); field3_v = jQuery('#reservation_field_3').val();}
            if(!field1_v) { jQuery('#reservation_field_1').css("border","1px solid red"); return false;}
            if(jQuery('#reservation_textarea').length){ textarea_label = jQuery('label[for="reservation_textarea"]').html(); textarea_v = jQuery('#reservation_textarea').val();}

            var dates = jQuery('.calendar:first').multiDatesPicker('getDates');

            var datastring = 'action=tfuse_send_reservation_email';
            if(field1_label) datastring +='&field1_l='+field1_label+'&field1_v='+field1_v;
            if(field2_label) datastring +='&field2_l='+field2_label+'&field2_v='+field2_v;
            if(field3_label) datastring +='&field3_l='+field3_label+'&field3_v='+field3_v;

            if(textarea_label) datastring +='&textarea_l='+textarea_label+'&textarea_v='+textarea_v+'&dates='+dates.toString();
            datastring +='&offer_id='+offer_id;

            jQuery.ajax({
                type: 'POST',
                url: tf_script.ajaxurl,
                data: datastring,
                success: function(response)
                {
                    if (response == 'true')
                    {
                        jQuery('.widget_holiday_reservation .holiday_reservation_success').fadeIn(500);
                    }
                    else
                    {
                        jQuery('.widget_holiday_reservation .holiday_reservation_error').fadeIn(500);
                    }
                    setTimeout(remove_holiday_reservation_messages, 3000);
                }
            });

        return false;
    });
}

function remove_holiday_reservation_messages()
{
    jQuery('.widget_holiday_reservation .holiday_reservation_success').fadeOut(500);
    jQuery('.widget_holiday_reservation .holiday_reservation_error').fadeOut(500);
}