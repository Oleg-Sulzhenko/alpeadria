<?php

class TF_SEEK_POST_ATTACHMENTS {

    private static $instance        = NULL;
    private $option_name            = NULL;
    private static $options_cache   = NULL;

    public function __construct(){

        if(self::$instance !== NULL) return;

        add_filter('attachment_fields_to_edit', array(&$this, 'filter_media_galery_image_edit'), 10, 2);
        add_filter('attachment_fields_to_save', array(&$this, 'filter_media_galery_image_save'), 10, 2);

        add_filter('tf_export_extra_options', array(&$this, 'filter_add_extra_option_for_export'));

        $this->option_name   = TF_THEME_PREFIX . '_tfuse_seek_post_attachments';

        self::$instance = &$this;
    }

    // Filter: Add this option for export
    public function filter_add_extra_option_for_export($extra_export_options){
        $extra_export_options[ $this->option_name ] = get_option( $this->option_name );

        return $extra_export_options;
    }

    /**
     * Filte media galery image options
     */
    public function filter_media_galery_image_edit($form_fields, $post){
        if( get_post_type($post->post_parent) == TF_SEEK_HELPER::get_post_type() ){

            $options        = (array)get_option( $this->option_name, array() );
            $excluded       = explode(',', @$options['excluded']['posts_select']);
            $main           = explode(',', @$options['main']['posts_select']);

            $form_fields['tfseek_exclude_slider'] = array(
                'label' => __('Exclude from slider', 'tfuse'),
                'input' => 'html',
                'html'  => '<label for="tfseek_imgexcludefromslider_check_'.$post->ID.'"><input id="tfseek_imgexcludefromslider_check_'.$post->ID.'" type="checkbox" '.(in_array($post->ID, $excluded) ? 'checked' : '').' value="yes" name="tf_seek_exclude_attachment_check_'.$post->ID.'"/> <span>'.__('Yes','tfuse').'</span></label>'
            );

            $form_fields['tfseek_main'] = array(
                'label' => __('Set as main', 'tfuse'),
                'input' => 'html',
                'html'  => '<label for="tfseek_imgmain_check_'.$post->ID.'"><input id="tfseek_imgmain_check_'.$post->ID.'" type="checkbox" '.(in_array($post->ID, $main) ? 'checked' : '').' value="yes" name="tf_seek_main_attachment_check_'.$post->ID.'"/> <span>'.__('Yes','tfuse').'</span></label>'
            );
        }

        return $form_fields;
    }

    /**
     * Save fields added by filter
     */
    public function filter_media_galery_image_save($post, $attachment){
        global $TFUSE;
        do {
            if( get_post_type($post['post_parent']) != TF_SEEK_HELPER::get_post_type() ){
                break;
            }

            $options    = (array)get_option( $this->option_name, array() );
            $excluded   = explode(',', @$options['excluded']['posts_select']);
            if(sizeof($excluded)){ // convert values to keys
                $new = array();
                foreach($excluded as $id){
                    if(1 > intval($id)) continue;
                    $new[$id] = 1;
                }
                $excluded = $new;
                unset($new);
            }
            $main       = explode(',', @$options['main']['posts_select']);
            if(sizeof($main)){ // convert values to keys
                $new = array();
                foreach($main as $id){
                    if(1 > intval($id)) continue;
                    $new[$id] = 1;
                }
                $main = $new;
                unset($new);
            }

            if($TFUSE->request->isset_POST('tf_seek_exclude_attachment_check_'.$post['ID'].'')){
                $excluded[$post['ID']] = 1;
            } else {
                if(isset($excluded[$post['ID']])){
                    unset($excluded[$post['ID']]);
                }
            }

            if($TFUSE->request->isset_POST('tf_seek_main_attachment_check_'.$post['ID'].'')){
                $main[$post['ID']] = 1;
            } else {
                if(isset($main[$post['ID']])){
                    unset($main[$post['ID']]);
                }
            }

            $options = array(
                'excluded'  => array(
                    'posts_select' => implode(',', array_keys($excluded))
                ),
                'main'      => array(
                    'posts_select' => implode(',', array_keys($main))
                )
            );

            update_option($this->option_name, $options);
        } while(false);

        return $post;
    }

    public static function load_cache(){
        if(self::$options_cache !== NULL) return self::$options_cache;

        $new     = array(
            'excluded'  => array(),
            'main'      => array(),
        );

        $options = (array)get_option( self::$instance->option_name, array() );

        $tmp = explode(',', @$options['excluded']['posts_select']);
        if(sizeof($tmp)){
            foreach($tmp as $id){
                if(1 > intval($id)) continue;

                $new['excluded'][$id] = 1;
            }
        }

        $tmp = explode(',', @$options['main']['posts_select']);
        if(sizeof($tmp)){
            foreach($tmp as $id){
                if(1 > intval($id)) continue;

                $new['main'][$id] = 1;
            }
        }

        self::$options_cache = $new;

        return( self::$options_cache );
    }

    /**
     * Check if attachment image attached to property is excluded from slider
     */
    public static function img_is_excluded_from_slider($post_id){

        if(self::$options_cache !== NULL){
            $options = self::$options_cache;
        } else {
            $options = self::$options_cache = self::load_cache();
        }

        return isset($options['excluded'][$post_id]);
    }

    /**
     * Check if attachment image attached to property is set as main
     */
    public static function img_is_main($post_id){

        if(self::$options_cache !== NULL){
            $options = self::$options_cache;
        } else {
            $options = self::$options_cache = self::load_cache();
        }

        return isset($options['main'][$post_id]);
    }
}
new TF_SEEK_POST_ATTACHMENTS;