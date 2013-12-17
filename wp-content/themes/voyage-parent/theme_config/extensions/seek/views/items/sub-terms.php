<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');
$all_items      = TF_SEEK_HELPER::get_items_options();
?>
<div class="row rowSubmit" style="z-index: 9;" id="tf-seek-input-select-<?php print esc_attr($item_id); ?>">
    <select class="select_styled parameter_name" name="<?php print esc_attr($parameter_name); ?>" id="<?php print esc_attr($parameter_name); ?>" style="width: 207px;">
        <option value="<?php print $settings['default_parent']; ?>" ><?php print $vars['default_option']; ?></option>
    </select>
</div>
    <script type="text/javascript">

        function getURLParameter(name) {
            return decodeURI(
                    (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
            );
        }

        function tfuse_get_sub_terms_<?php echo esc_attr($parameter_name); ?>(parent,selected)
        {
            jQuery.ajax({
                type: 'POST',
                url:  tf_script.ajaxurl,
                data: 'action=tfuse_ajax_get_terms&taxonomy=<?php print $settings['taxonomy']; ?>&parent=' + parent,
                success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    terms = obj.terms;
                    if(obj.parent == <?php print ($all_items[$settings['dependency']]['settings']['args']['parent']); ?>)
                    {
                        jQuery('#cusel-scroll-<?php print esc_attr($parameter_name); ?> span').remove();
                        jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> .cuselText').html('<?php print $vars['default_option']; ?>');
                        jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> #<?php print esc_attr($parameter_name); ?>').val(obj.parent);
                        jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> .cusel-scroll-wrap').css("visibility", "hidden");
                    }
                    else if(terms.length)
                    {
                        jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> .cusel-scroll-wrap').css("visibility", "visible");
                        jQuery('#cusel-scroll-<?php print esc_attr($parameter_name); ?> span').remove();
                        jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> .cuselText').html('<?php print $vars['all_sub_terms']; ?>');
                        jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> #<?php print esc_attr($parameter_name); ?>').val(obj.parent);
                        var cussel_default_active = ((typeof(selected) == 'undefined') || (parent == selected) ) ? ' class="cuselActive"' : '';
                        var cussel_active = ' class="cuselActive"';
                        jQuery('#cusel-scroll-<?php print esc_attr($parameter_name); ?>').append('<span val="' + obj.parent + '"' + cussel_default_active + '><?php print $vars['all_sub_terms']; ?></span>');
                        for(var i in terms)
                        {
                            var html = '<span val="' + terms[i].term_id + '"';
                                if(terms[i].term_id == selected)
                                {
                                    html += cussel_active;
                                    jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> .cuselText').html(terms[i].name);
                                }
                                html +='>' + terms[i].name + '</span>';
                            jQuery('#cusel-scroll-<?php print esc_attr($parameter_name); ?>').append(html);
                        }
                    }
                    else
                    {
                        jQuery('#cusel-scroll-<?php print esc_attr($parameter_name); ?> span').remove();
                        jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> .cuselText').html('<?php print $vars['no_terms']; ?>');
                        jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> #<?php print esc_attr($parameter_name); ?>').val(obj.parent);
                        jQuery('#cuselFrame-<?php print esc_attr($parameter_name); ?> .cusel-scroll-wrap').css("visibility", "hidden");
                    }
                }
            });

            var params = {
                refreshEl: "#<?php print esc_attr($parameter_name); ?>",
                visRows: 15
            }
            cuSelRefresh(params);
        }

        jQuery(document).ready(function(){
            tfuse_get_sub_terms_<?php echo esc_attr($parameter_name); ?>(jQuery('input:radio[name="<?php print esc_attr($settings['dependency']); ?>"]:checked').val(), getURLParameter('<?php echo esc_attr($parameter_name) ?>'));
        });

        jQuery(document).on('change', 'input:radio[name="<?php print esc_attr($settings['dependency']); ?>"]:checked', function(){tfuse_get_sub_terms_<?php echo esc_attr($parameter_name); ?>(jQuery(this).val());});
    </script>