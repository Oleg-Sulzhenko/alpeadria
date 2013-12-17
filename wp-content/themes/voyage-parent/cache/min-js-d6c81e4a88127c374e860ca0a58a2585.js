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
(function(){var $=jQuery;$.fn.popbox=function(options){var settings=$.extend({selector:this.selector,open:'.open',box:'.box',arrow:'.arrow',arrow_border:'.arrow-border',close:'.close'},options);var methods={open:function(event){event.preventDefault();$(settings['box']).removeClass("current");var pop=$(this);var box=$(this).parent().find(settings['box']);box.addClass('current');box.find(settings['arrow']).css({'left':box.width()/2-10});box.find(settings['arrow_border']).css({'left':box.width()/2-10});methods.close();box.css({'display':'block','top':8,'left':(box.width()/2)});},close:function(){$(settings['box']).each(function(){if(!$(this).hasClass('current'))
$(this).fadeOut("fast");});}};$(document).bind('keyup',function(event){if(event.keyCode==27){$(settings['box']).removeClass('current');methods.close();}});$(document).bind('click',function(event){if(!$(event.target).closest(settings['selector']).length&&!$(event.target).closest('.ui-datepicker').length&&!$(event.target).closest('.ui-datepicker-header').length){$(settings['box']).removeClass('current');methods.close();}});return this.each(function(){$(this).css({'width':$(settings['box']).width()});$(settings['open'],this).bind('click',methods.open);$(settings['open'],this).parent().find(settings['close']).bind('click',function(event){event.preventDefault();$(settings['box']).removeClass('current');methods.close();});});}}).call(this);
/* end of /wp-content/themes/voyage-parent/framework/static/javascript/popbox.min.js */
