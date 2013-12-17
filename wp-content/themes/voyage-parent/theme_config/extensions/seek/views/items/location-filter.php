<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


    $input_value    = TF_SEEK_HELPER::get_input_value($parameter_name,'');
    $input_values   = explode(',', $input_value);
    $placeholder    = esc_attr( $vars['placeholder'] );
?>
<div class="row inputlist" id="tf-input-container-<?php print($item_id); ?>">
    <label class="label_title"><?php print($vars['label']); ?>:</label>
    <input type="hidden" name="<?php print($parameter_name); ?>" value="<?php print esc_attr( $input_value ); ?>" />

    <div id="tf-seek-item-fields-container-<?php print($item_id); ?>">
    <?php
        if(sizeof($input_values)):
            $uniques = array();
            foreach($input_values as $value):
                if(!($value = trim($value))){
                    continue;
                }
                if(isset($uniques[$value])){
                    continue;
                } else {
                    $uniques[$value] = 'This is unique';
                }
    ?>
                <div class="custom-input addField_remove tf-custom-input-container-<?php print($item_id); ?>"><input type="text" class="<?php print($item_id); ?>-custom-input-field" value="<?php print(esc_attr($value)); ?>"><span></span></div>
    <?php
            endforeach;
        endif;
    ?>
    </div>

    <div class="custom-input addField_add tf-custom-add-input-container-<?php print($item_id); ?>"><input type="text" id="tf-seek-input-text-autocompleter-<?php print($item_id); ?>" class="addField" value="<?php print($placeholder); ?>" onfocus="if (this.value == '<?php print($placeholder); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php print($placeholder); ?>';}" ><span></span></div>
</div>

<?php $safe_item_id = preg_replace('/[^a-z0-9]/iU', '_', $item_id); ?>

<script type="text/javascript" >
    jQuery(document).ready(function($){
        var tf_seek_input_<?php print($safe_item_id); ?> = new (function(item_id){

            this.flush_values = function(){
                $('#tf-input-container-'+This.item_id+' input[name=<?php print($parameter_name); ?>]').val( This.values.join(', ') );
            }

            this.reread_values = function(){
                This.values = [];
                $.each( $('#tf-input-container-'+This.item_id+' input.'+This.item_id+'-custom-input-field'), function(){
                    var value = $.trim( $(this).val() );

                    if(!value.length){
                        return;
                    }

                    if(-1 == This.values.indexOf(value)){
                        This.values.push(value);
                    }
                });
                This.flush_values();
            }

            this.value_exist = function(check_value){
                if(-1 == This.values.indexOf(check_value)){
                    return false;
                } else {
                    return true;
                }
            }

            this.rebind = function(){
                $('#tf-input-container-'+This.item_id+' div.tf-custom-input-container-'+This.item_id+' span')
                    .unbind('click')
                    .bind('click', function(){
                        tf_seek_input_<?php print($safe_item_id); ?>.remove_field( $(this) );
                    });
            }

            this.remove_field = function( item ){
                $(item).closest('div.tf-custom-input-container-'+This.item_id+'').remove();
                This.reread_values();
            }

            this.add_field = function(value, placeholder){
                var parent_add = $('div.tf-custom-add-input-container-'+This.item_id+'');
                var input_text = $('input[type=text]', parent_add);
                var cancel_add = function (){
                    input_text.val( (placeholder ? '<?php print($placeholder); ?>' : '') );
                }

                value = $.trim(value);

                if(! value.length){
                    cancel_add();
                    return;
                }

                if( value == '<?php print($placeholder); ?>' ){
                    cancel_add();
                    return;
                }

                if(-1 != This.values.indexOf(value)){
                    cancel_add();
                    return;
                }

                $('div#tf-seek-item-fields-container-'+This.item_id+'').append(
                    '<div class="custom-input addField_remove tf-custom-input-container-'+This.item_id+'"><input type="text" class="'+This.item_id+'-custom-input-field" value="{value}"><span></span></div>'
                        .replace('{value}', This.htmlEntities(value))
                );
                cancel_add();

                This.rebind();
                This.reread_values();

                return;
            }

            this.htmlEntities = function(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            this.__construct = function(item_id){
                This.item_id = item_id;
                This.values  = String($('#tf-input-container-'+This.item_id+' input[name=<?php print($parameter_name); ?>]').val()).split( /,\s*/ );
                This.rebind();
                This.reread_values();

                var parent_add = $('div.tf-custom-add-input-container-'+This.item_id+'');
                $('span', parent_add).click(function(){
                    This.add_field( $('input[type=text]', parent_add).val(), true );
                });
                $('input[type=text]', parent_add).keydown(function(event){
                    if(event.keyCode == 13){
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        return;
                    }

                    This.add_field( $(this).val(), false );
                });
            }

            // run
            var This = this;
            this.__construct(item_id);
        })('<?php print($item_id); ?>');
    });
</script>

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
        $.each( $('input#tf-seek-input-text-autocompleter-<?php print($item_id); ?>'), function(){
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