<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

?><div class="row rowInput" id="tf-seek-input-text-autocompleter-<?php print($item_id); ?>">
    <label class="label_title"><?php print($vars['label']); ?>:</label>
    <input type="text" name="<?php print($parameter_name); ?>" value="<?php print esc_attr( TF_SEEK_HELPER::get_input_value($parameter_name) ); ?>">
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
        //taxonomy single
        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }
        //
        $.each( $('#tf-seek-input-text-autocompleter-<?php print($item_id); ?> input'), function(){
            var This = $(this);

            This
                .bind( "keydown", function( event ) {
                    if ( event.keyCode === $.ui.keyCode.TAB &&
                        $( this ).data( "autocomplete" ).menu.active ) {
                        event.preventDefault();
                    }
                })
                .autocomplete({
                    minLength: 1,
                    source: function( request, response ) {
                        // delegate back to autocomplete, but extract the last term
                        //response( $.ui.autocomplete.filter( availableTags, extractLast( request.term ) ) );

                        $.post(tf_script.ajaxurl, {
                            action:     'tf_action',
                            tf_action:  'tf_action_ajax_seek_location_autocomplete',
                            item_id:    '<?php print($item_id); ?>',
                            value:      request.term,
                            term:       extractLast( request.term )
                        },function(data){
                            response(data);
                        },'json');
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function( event, ui ) {
                        var terms = split( this.value );
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push( ui.item.value );
                        // add placeholder to get the comma-and-space at the end
                        terms.push( "" );
                        this.value = terms.join( ", " );
                        return false;
                    },
                    open: function(event, ui) {
                        var target  = $(event.target);
                        var width   = target.width();
                        var padSide = parseInt(target.css('paddingLeft')) + parseInt(target.css('paddingRight'));
                        var auWidth = width + padSide;

                        $('.ui-autocomplete').css('width', auWidth+'px');
                    }
                });
        });
    });
</script>