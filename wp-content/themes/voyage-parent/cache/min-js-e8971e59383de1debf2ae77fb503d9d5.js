var TFE=new(function()
{var $=jQuery;var eventsBox=$('<div></div>');this.logsEnabled=false;this.log=function(message,data)
{if(!this.logsEnabled){return;}
if(data!==undefined){console.log('[TFE] '+getIndentation()+message,'◼',data);}else{console.log('[TFE] '+getIndentation()+message);}};this.on=function(event,callback)
{eventsBox.on(event,callback);this.log('✚ '+event);};this.one=function(event,callback)
{eventsBox.one(event,callback);this.log('✚ '+event);};this.off=function(event)
{eventsBox.off(event);this.log('✖ '+event);};this.trigger=function(event,data)
{data=data||{};this.log('╭╼▓ '+event,data);changeIndentation(+1);try{eventsBox.trigger(event,data);}catch(e){console.log('[TFE] Exception ',{exception:e});if(console.trace){console.trace();}}
changeIndentation(-1);this.log('╰╼░ '+event,data);};this.getAttachedEvents=function()
{return $._data(eventsBox[0],'events');};var getIndentation=function()
{return new Array(currentIndentation).join('│   ');};var currentIndentation=1;var changeIndentation=function(increment)
{if(increment!==undefined){currentIndentation+=(increment>0?+1:-1);}
if(currentIndentation<0){currentIndentation=0;}};})();
/* end of /wp-content/themes/voyage-parent/framework/static/javascript/TFE.js */
(function(){var methods={changeCheckboxId:function(self){var uniqueID=Math.floor(Math.random()*99999);self.find('.div-table-td[data-type=checkbox]').each(function(){var td=jQuery(this);var id=td.attr('data-id');td.find('[id^="'+id+'"],label[for^="'+id+'"]').each(function(){var self=jQuery(this);if(self.attr('for'))
self.attr('for',id+'_'+(uniqueID));else
self.attr('id',id+'_'+uniqueID);});uniqueID=Math.floor(Math.random()*99999);});},setInputValue:function(event){var self=event.data.self;var tableId=self.attr('id');self.find('input[name="'+tableId+'"]').val(JSON.stringify(self.find('.div-table-first-body input, .div-table-first-body textarea, .div-table-first-body select').serializeObject()));},checkAllRows:function(event){var self=event.data.self;if(jQuery(this).is(':checked'))
self.find('.tfbtq_checkbox_column').attr('checked','checked');else
self.find('.tfbtq_checkbox_column').removeAttr('checked');},deleteRows:function(event){var self=event.data.self;var checkboxes=self.find('.div-table-delete-checkbox-row input[type="checkbox"]');jQuery.each(checkboxes,function(){if(jQuery(this).is(':checked'))
jQuery(this).closest('.div-table-tr').remove();});self.find('.tfbtq_checkbox_column_all').removeAttr('checked');self.find('input').trigger('change');return false;},addInput:function(callback,id){var id='#'+id+'_chzn';jQuery(id).find('.chzn-drop').prepend('<div class="tf_selectsearch_create_options_wrapper">'+'<div class="tf_selectsearch_create_options_content">'+'<input   type="text"/><a class="add button tfuse_selectsearch_create_option_action">Add</a>'+'<div style="clear: both"></div> '+'</div>'+'</div>');jQuery('.tfuse_selectsearch_create_option_action').data('record',{'callback':callback,'id':'#'+id});},addRow:function(event){var self=event.data.self,firstTr=event.data.firstTr,row=firstTr.clone(),selectsearch=row.find('.tfuse-select');self.find('.div-table-first-body').append(row.show());if(selectsearch.length>0){selectsearch.removeClass('chzn-done').removeAttr('id').next('div').remove();selectsearch.tfuse_chosen();}
methods.changeCheckboxId(self);self.find('input').trigger('change');return false;}};jQuery.fn.extend({tfuseMakeDivTable:function(){jQuery(this).each(function(){var self=jQuery(this);methods.changeCheckboxId(self);var firstTr=self.find('.tfbtq-default-value-row');jQuery(document).on('click','#'+self.attr('id')+' .tfbtq_shopping_delete_rows',{self:self},methods.deleteRows);jQuery(document).on('change','#'+self.attr('id')+' input, #'+self.attr('id')+' textarea, #'+self.attr('id')+' select',{self:self},methods.setInputValue);jQuery(document).on('click','#'+self.attr('id')+' .tfbtq_shopping_add_row',{self:self,firstTr:firstTr},methods.addRow);self.find('input').trigger('change');});}});})();
/* end of /wp-content/themes/voyage-parent/framework/static/javascript/tfuse_div_table.js */
