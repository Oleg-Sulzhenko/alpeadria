<?php if (!defined('TFUSE')) exit('Direct access forbidden.');

add_action('wp_ajax_change_gallery_id', 'change_gallery_id');
function change_gallery_id() {
    if (!tf_current_user_can(array('manage_options', 'edit_posts', 'tf_admin_boutique'), false))
        return false;

    global $TFUSE;

    $post_id   = $TFUSE->request->REQUEST('post_id');
    if(!tfuse_parse_boolean($TFUSE->request->REQUEST('change'))) {echo json_encode(array('id'=> $post_id));die;}
    $id        = $TFUSE->request->REQUEST('input_id');
    $media     = $TFUSE->request->REQUEST('media');

    $_token    = (trim($id) != '') ? $id . '_' . $post_id : $post_id;
    $post_fnc  = ($media == 'image') ? 'tfuse_gallery_group_post' : 'tfuse_download_group_post';
    $post_type = str_replace('_post', '', $post_fnc);
    $post      = get_post($post_id);
    if ($post->post_type != $post_type)
        $post_id = $post_fnc($_token);
    echo json_encode(array('id'=> $post_id));
    die;
}

/**
 * 'Exclude from slider' and 'Set as main' checkboxes for attachment
 */
{
    add_filter('attachment_fields_to_edit', 'media_galery_image_edit', 11, 2);
    function media_galery_image_edit($form_fields, $post) {
        $content = get_post_meta($post->ID,'image_options',true);

        $form_fields['tfseek_exclude_slider'] = array(
            'label' => __('Exclude from slider', 'tfuse'),
            'input' => 'html',
            'html'  => '<label for="imgexcludefromslider_check"><input id="imgexcludefromslider_check" type="checkbox" ' . (@$content['imgexcludefromslider_check'] ? 'checked' : '') . ' value="yes" name="imgexcludefromslider_check_'.$post->ID.'"/> <span>' . __('Yes', 'tfuse') . '</span></label>'
        );

        $form_fields['tfseek_main'] = array(
            'label' => __('Set as main', 'tfuse'),
            'input' => 'html',
            'html'  => '<label for="imgmain_check"><input id="imgmain_check" type="checkbox" ' . (@$content['imgmain_check']== 'yes'? 'checked' : '') . ' value="yes" name="imgmain_check_'.$post->ID.'"/> <span>' . __('Yes', 'tfuse') . '</span></label>'
        );

        return $form_fields;
    }

    add_filter('attachment_fields_to_save', 'media_galery_image_save', 11, 2);
    function media_galery_image_save($post, $attachment) {
        global $TFUSE;

        $a = array();
        if($TFUSE->request->isset_POST('imgexcludefromslider_check_'.$post['ID']))
            $a['imgexcludefromslider_check'] = $TFUSE->request->POST('imgexcludefromslider_check_'.$post['ID']);
        if($TFUSE->request->isset_POST('imgmain_check_'.$post['ID']))
            $a['imgmain_check'] = $TFUSE->request->POST('imgmain_check_'.$post['ID']);
        tf_update_post_meta($post['ID'],'image_options',$a);

        return $post;
    }
}

add_filter('media_upload_tabs', 'remove_media_tabs');
function remove_media_tabs($tabs) {
    global $TFUSE;

    if ($TFUSE->request->isset_REQUEST('no_tabs')) {
        unset($tabs['library']);
        unset($tabs['type_url']);
    }

    return $tabs;
}

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

add_filter('the_content', 'tfuse_formatter', 99);
add_filter('themefuse_shortcodes', 'tfuse_formatter', 99);
function tfuse_formatter($content) {
    $new_content      = '';
    $pattern_full     = '{(\[raw\].*?\[/raw\])}is';
    $pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
    $pieces           = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

    foreach ($pieces as $piece) {
        if (preg_match($pattern_contents, $piece, $matches)) {
            $new_content .= $matches[1];
        } else {
            $new_content .= wptexturize(wpautop($piece));
        }
    }
    return $new_content;
}

add_action('wp_head', 'tfuse_favicon_and_css');
function tfuse_favicon_and_css() {
    // Favicon
    $favicon = tfuse_options('favicon');
    if (!empty($favicon))
        echo '<link rel="shortcut icon" href="' . $favicon . '"/>' . PHP_EOL;

    // Custom CSS block in header
    $custom_css = tfuse_options('custom_css');
    if (!empty($custom_css)) {
        $output = '<style type="text/css">' . PHP_EOL;
        $output .= html_entity_decode($custom_css);
        $output .= '</style>' . PHP_EOL;
        echo $output;
    }
}

add_action('wp_footer', 'tfuse_analytics', 100);
function tfuse_analytics() {
    echo tfuse_options('google_analytics');
}

/** multi_upload2 */
{
    class _TF_OPTIGEN_MULTI_UPLOAD2
    {
        private $post_type = 'tf_multi_upload2'; // max length is 20

        public function __construct()
        {
            add_action('wp_ajax_multi_upload2_get_temp_gallery_post_id',        array($this, 'ajax_get_temp_gallery_post_id'));
            add_action('wp_ajax_multi_upload2_get_temp_gallery_attachments',    array($this, 'ajax_get_temp_gallery_attachments'));
            add_action('init',                                                  array($this, 'action_init'));

            add_action('media_upload_image',                                    array($this, 'action_media_upload_image'));
            add_action('media_upload_gallery',                                  array($this, 'action_media_upload_gallery'));
            add_action('media_upload_library',                                  array($this, 'action_media_upload_library'));
        }

        public function action_init()
        {
            register_post_type($this->post_type, array(
                'labels' => array(
                    'name' => __('ThemeFuse Multi Upload2', 'tfuse'),
                ),
                'public'            => true,
                'show_ui'           => false,
                'capability_type'   => 'post',
                'hierarchical'      => false,
                'rewrite'           => false,
                'supports'          => array(),
                'query_var'         => false,
                'can_export'        => false,
                'show_in_nav_menus' => false
            ));
        }

        /**
         * Get gallery images uploaded in popup
         * and delete everything about temp post_id
         */
        public function ajax_get_temp_gallery_attachments()
        {
            if (!tf_current_user_can(array('manage_options', 'edit_posts', 'tf_admin_boutique'), false))
                return false;

            /** @var TF_TFUSE */
            global $TFUSE;

            $response = array(
                'status' => 0
            );

            do {
                $post_id = $TFUSE->request->POST('post_id');
                if (!is_numeric($post_id) || $post_id < 1)
                    break;

                $post_id = intval($post_id);
                $attachments = array();
                foreach(get_children('post_type=attachment&orderby=menu_order&order=ASC&post_parent='. $post_id) as $key => $attachment) {
                    $attachments[] = array(
                        'url'       => $attachment->guid,
                        'title'     => $attachment->post_title,
                        'alt'       => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
                        'caption'   => $attachment->post_excerpt,
                        'desc'      => $attachment->post_content
                    );
                }
                $response['attachments'] = $attachments;

                $this->delete_all_posts();

                $response['status'] = 1;
            } while(false);

            echo json_encode($response);
            die;
        }

        /**
         * Create post_id for opening gallery popup
         * and attach to it given images
         */
        function ajax_get_temp_gallery_post_id()
        {
            if (!tf_current_user_can(array('manage_options', 'edit_posts', 'tf_admin_boutique'), false))
                return false;

            /** @var TF_TFUSE $TFUSE */

            global $TFUSE;

            $value = json_decode($TFUSE->request->POST('value'));
            if ($value === null)
                $value = array();

            $response = array(
                'status' => 0
            );

            $this->delete_all_posts();

            do {
                $attachments = array();
                foreach ($value as $file) {
                    if (!is_object($file))
                        continue;

                    $attachments[] = array(
                        'url'       => $file->url,
                        'title'     => @$file->title,
                        'alt'       => @$file->alt,
                        'caption'   => @$file->caption,
                        'desc'      => @$file->desc,
                    );
                }

                $post_id = $this->create_post();

                if (!$post_id)
                    break;

                $this->create_attachments($post_id, $attachments);

                $response['post_id'] = $post_id;
                $response['status'] = 1;
            } while(false);

            echo json_encode($response);
            die;
        }

        /**
         * Delete all trash from db
         */
        private function delete_all_posts()
        {
            /** @var WPDB $wpdb */

            global $wpdb;

            foreach (
                $wpdb->get_results(
                     $wpdb->prepare("SELECT ID FROM ". $wpdb->posts ." WHERE post_type = %s", $this->post_type)
                 ) as $post
            ) {
                wp_delete_post($post->ID, true);
            }
        }

        /**
         * Create a post to contain attachments (added in upload popup)
         */
        private function create_post($attachments = array())
        {
            $post_data = array(
                'post_type'     => $this->post_type,
                'post_status'   => 'draft',
                'comment_status'=> 'closed',
                'ping_status'   => 'closed',
                'post_title'    => '~',
                'post_name'     => '~',
            );

            $post_id = wp_insert_post($post_data);

            if (is_wp_error($post_id) || !$post_id)
                return 0;

            return $post_id;
        }

        private function create_attachments($post_id, $attachments)
        {
            /** @var WPDB $wpdb */
            global $wpdb;

            do {
                // you must first include the image.php file
                // for the function wp_generate_attachment_metadata() to work
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                $wp_upload_dir = wp_upload_dir();

                if ($wp_upload_dir['error'])
                    break;

                $menu_order = 1;
                foreach ($attachments as $attachment) {
                    {
                        $url = $attachment['url'];

                        $rel_path = explode('/wp-content/uploads/', $url);
                        array_shift($rel_path);
                        $rel_path = array_pop($rel_path);

                        if (empty($rel_path))
                            continue;

                        $path = $wp_upload_dir['basedir'] .'/'. $rel_path;
                    }

                    if (!file_exists($path))
                        continue;

                    $wp_filetype = wp_check_filetype(basename($path), null );

                    $alt = $attachment['alt'];

                    $attachment = array(
                        'guid'              => $wp_upload_dir['url'] . '/' . basename($path),
                        'post_mime_type'    => $wp_filetype['type'],
                        'post_title'        => $attachment['title'] ? $attachment['title'] : preg_replace('/\.[^.]+$/', '', basename($path)),
                        'post_excerpt'      => $attachment['caption'],
                        'post_content'      => $attachment['desc'],
                        'post_status'       => 'inherit',
                        'menu_order'        => $menu_order++
                    );

                    $attach_id = $wpdb->get_var(
                        $wpdb->prepare("SELECT
                            ID
                                FROM $wpdb->posts
                                WHERE guid = '%s' AND
                                    post_type = 'attachment'
                            LIMIT 1",
                            $attachment['guid']
                        )
                    );

                    if ($attach_id) {
                        wp_update_post(array(
                            'ID' => $attach_id,
                            'post_parent' => $post_id
                        ));

                        unset($attachPost);
                    } else {
                        $attach_id = wp_insert_attachment($attachment, $path, $post_id);
                    }

                    tf_update_post_meta($attach_id, '_wp_attachment_image_alt', $alt);

                    $attach_data = wp_generate_attachment_metadata($attach_id, $path);
                    wp_update_attachment_metadata($attach_id, $attach_data);
                }

                return true;
            } while(false);

            return false;
        }

        public function action_media_upload_image()
        {
            $this->media_upload_popup_output('image');
        }

        public function action_media_upload_gallery()
        {
            $this->media_upload_popup_output('gallery');
        }

        public function action_media_upload_library()
        {
            $this->media_upload_popup_output('library');
        }

        /**
         * When multi_upload popup content is generated
         * Hide elements that does not work
         */
        public function media_upload_popup_output($type)
        {
            if (get_post_type((int)@$_GET['post_id']) !== $this->post_type)
                return;

            ?>
            <style type="text/css">
                #sidemenu #tab-type_url,
                #gallery-form #media-items .media-item td.savesend input.button,

                #media-upload .media-item .slidetoggle tr.tfseek_exclude_slider,
                #media-upload .media-item .slidetoggle tr.tfseek_main,
                #media-upload .media-item .slidetoggle tr.align,
                #media-upload .media-item .slidetoggle tr.image-size,
                #media-upload .media-item .slidetoggle tr.url,

                #gallery-settings {
                    display: none !important;
                }
            </style>
            <?php
        }
    }
    new _TF_OPTIGEN_MULTI_UPLOAD2();
}
