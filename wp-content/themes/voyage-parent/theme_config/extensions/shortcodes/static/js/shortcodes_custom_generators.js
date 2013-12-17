function custom_generator_faq(type,options) {
    var shortcode='[faq title="'+options.title+'"]';
    for(var i in options.array) {
        shortcode+='[faq_question]'+options.array[i]['question']+'[/faq_question]';
        shortcode+='[faq_answer]'+options.array[i]['answer']+'[/faq_answer]';
    }
    shortcode+='[/faq]';
    return shortcode;
}

function custom_obtainer_faq(data) {
    var cont=jQuery('.tf_shortcode_option:visible');
    var sh_options={};
    sh_options['array']=[];
    sh_options['title']=opt_get('tf_shc_faq_title',cont);
    cont.find('[name="tf_shc_faq_question"]').each(function(i) {
        var question=jQuery(this).val();
        var answer=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_faq_answer"]:first').val();
        var tmp={};
        tmp['question']=question;
        tmp['answer']=answer;
        sh_options['array'].push(tmp);
    });
    return sh_options;
}

function custom_generator_tabs(type,options) {
    var shortcode='[tabs class="'+options['class']+'"]';
    for(var i in options.array) {
        shortcode+='[tab title="'+options.array[i]['title']+'"]'+options.array[i]['content']+'[/tab]';
    }
    shortcode+='[/tabs]';
    return shortcode;
}

function custom_obtainer_tabs(data) {
    var cont=jQuery('.tf_shortcode_option:visible');
    var sh_options={};
    sh_options['array']=[];
    sh_options['class']= opt_get('tf_shc_tabs_class',cont);
    cont.find('[name="tf_shc_tabs_title"]').each(function(i) {
        var div=jQuery(this).parents('.option');
         var title=opt_get(jQuery(this).attr('name'),div);
        div=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_tabs_content"]').first().parents('.option');
        var content=opt_get(jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_tabs_content"]').first().attr('name'),div);
        var tmp={};
        tmp['title']=title;
        tmp['content']=content;
        sh_options['array'].push(tmp);
    });
    return sh_options;
}

function custom_generator_tags(type,options) {
    var shortcode='[tags title="'+options['title']  + '" latest="' + options['latest'] + '" latest_title="' + options['ltitle'] + '"]';
    for(var i in options.array) {
        shortcode+='[tag id="'+options.array[i]['id'] + '" icon="' + options.array[i]['icon'] + '"]';
    }
    shortcode+='[/tags]';
    return shortcode;
}

function custom_obtainer_tags(data) {
    var cont=jQuery('.tf_shortcode_option:visible');
    var sh_options={};
    sh_options['array']=[];
    sh_options['title']= opt_get('tf_shc_tags_title',cont);
    sh_options['latest']= opt_get('tf_shc_tags_latest',cont);
    sh_options['ltitle']= opt_get('tf_shc_tags_ltitle',cont);

    cont.find('[name="tf_shc_tags_id"]').each(function(i)
    {

        var div=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_tags_id"]').first().parents('.option');
        var id=opt_get(jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_tags_id"]').first().attr('name'),div);

        div=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_tags_icon"]').first().parents('.option');
        var icon=opt_get(jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_tags_icon"]').first().attr('name'),div);

        var tmp={};
        tmp['id'] = id;
        tmp['icon']=icon;

        sh_options['array'].push(tmp);
    });
    return sh_options;
}
