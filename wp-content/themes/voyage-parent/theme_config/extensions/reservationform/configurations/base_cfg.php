<?php
$cfg['input_types']=array(
                        array(
                            'name'=>__('Text line', 'tfuse'),
                            'type'=>'text',
                            'value'=>'',
                            'id'=>TF_THEME_PREFIX."_%%name%%",
                            'options'=>false,
                            'properties'=>array(
                                'style'=>'width:85%',
                                'class'=>'inputtext'
                            )
                            ),
                        array(
                            'name'=>__('Text area', 'tfuse'),
                            'type'=>'textarea',
                            'value'=>'',
                            'id'=>TF_THEME_PREFIX."_%%name%%",
                            'options'=>false,
                            'properties'=>array(
                                'style'=>'width:91%',
                                'class'=>'textarea'
                            )
                             ),

                        array(
                             'name'=>__('Radio buttons', 'tfuse'),
                             'type'=>'radio',
                             'value'=>'',
                             'id'=>TF_THEME_PREFIX."_%%name%%",
                             'options'=>true
                             ),
                        array(
                             'name'=>__('Checkbox', 'tfuse'),
                             'type'=>'checkbox',
                             'value'=>'',
                             'id'=>TF_THEME_PREFIX."_%%name%%",
                             'options'=>false
                             ),
                         array(
                             'name'=>__('Select Box', 'tfuse'),
                             'type'=>'select',
                             'value'=>'',
                             'id'=>TF_THEME_PREFIX."_%%name%%",
                             'properties'=>array(
                                 'class'=>'select_styled'
                             ),
                             'options'=>true
                              ),
                         array(
                             'name'=>__('Email', 'tfuse'),
                             'type'=>'text',
                             'value'=>'',
                             'id'=>TF_THEME_PREFIX."_%%name%%",
                             'options'=>false,
                             'properties'=>array(
                                 'style'=>'width:85%',
                                 'class'=>'inputtext'
                             )
                             ),
                         array(
                              'name'=>__('Captcha', 'tfuse'),
                              'type'=>'captcha',
                              'value'=>'',
                              'id'=>"captcha",
                              'options'=>false,
                              'file_name'=>'captcha_gen.php',
                             'properties'=>array(
                                 'style'=>'width:85%'
                             )
                              )
                        );
$cfg['labels']=array(
            'type'=>'tfuse_cf_label',
    array(
        'id'=>'rf_type',
        'html'=>'<label >' . __('Type', 'tfuse') . '</label>',
        'type'=>'raw'
    ),
            array(
                'id'=>'rf_label',
                'html'=>'<label >' . __('Label', 'tfuse') . '</label>',
                'type'=>'raw'
            ),

            array(
                'id'=>'rf_width',
                'html'=>'<label >' . __('Width (%)', 'tfuse') . '</label>',
                'type'=>'raw'
            ),
            array(
                'id'=>'rf_required',
                'html'=>'<label >' . __('Required', 'tfuse') . '</label>',
                'type'=>'raw'
            ),
            array(
                'id'=>'rf_newline',
                'html'=>'<label >' . __('New Line', 'tfuse') . '</label>',
                'type'=>'raw'
            ),
            array(
                'id'=>'rf_shortcode',
                'html'=>'<label >' . __('Shortcode', 'tfuse') . '</label>',
                'type'=>'raw'
            ),
        );