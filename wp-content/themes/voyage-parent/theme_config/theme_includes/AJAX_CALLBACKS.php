<?php

if (!function_exists('tfuse_rewrite_worpress_reading_options')):

    /**
     *
     *
     * To override tfuse_rewrite_worpress_reading_options() in a child theme, add your own tfuse_rewrite_worpress_reading_options()
     * to your child theme's file.
     */

    add_action('tfuse_admin_save_options','tfuse_rewrite_worpress_reading_options', 10, 1);

    function tfuse_rewrite_worpress_reading_options ($options)
    {
        if($options[TF_THEME_PREFIX . '_homepage_category'] == 'page')
        {
            update_option('show_on_front', 'page');
            
            if(get_post_type(intval($options[TF_THEME_PREFIX . '_home_page'])) == 'page')
            {
                update_option('page_on_front', intval($options[TF_THEME_PREFIX . '_home_page']));
            }

            if(get_post_type(intval($options[TF_THEME_PREFIX . '_blog_page'])) == 'page')
            {
                update_option('page_for_posts', intval($options[TF_THEME_PREFIX . '_blog_page']));
            }
            else
            {
                update_option('page_for_posts', 0);
            }
        }
        else
        {
            update_option('show_on_front', 'posts');
            update_option('page_on_front', 0);
            update_option('page_for_posts', 0);
        }

    }
endif;

if (!function_exists('tfuse_ajax_get_terms')){
    function tfuse_ajax_get_terms(){
        global $TFUSE;
        $parent = (!$TFUSE->request->empty_POST('parent')) ? $TFUSE->request->POST('parent') : 0;
        $taxonomy = TF_SEEK_HELPER::get_post_type() . '_' . $TFUSE->request->POST('taxonomy');
        $args = array(
            'parent'        =>  $parent,
            'hide_empty'    => false
        );
        $terms = get_terms($taxonomy,$args);
        if(is_wp_error($terms)) $terms = array();
        $response = array(
                    'parent'        =>$parent,
                    'terms'         =>$terms,
                    'terms_size'    =>sizeof($terms)
                    );
        echo json_encode($response);
        die();
    }
}
add_action('wp_ajax_tfuse_ajax_get_terms','tfuse_ajax_get_terms');
add_action('wp_ajax_nopriv_tfuse_ajax_get_terms','tfuse_ajax_get_terms');

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
        $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        $str_end = "";
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        }
        else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        $str = $first_letter . $str_end;
        return $str;
    }
}


if (!function_exists('tfuse_aasort')) :
    /**
     *
     *
     * To override tfuse_aasort() in a child theme, add your own tfuse_aasort()
     * to your child theme's file.
     */
    function tfuse_aasort ($array, $key) {
        $sorter=array();
        $ret=array();
        if (!$array){$array = array();}
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        return $ret;
    }
endif;


if (!function_exists('tfuse_get_term_parent')):

    /*
     * To override tfuse_get_term_parent() in a child theme, add your own tfuse_get_term_parent()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
    */

    function tfuse_get_term_parent( $term_id, $taxonomy ) {
        if ( ! taxonomy_exists($taxonomy) )
            return new WP_Error('invalid_taxonomy', __('Invalid taxonomy'));

        $term_id = intval( $term_id );
        $parents = array();
        $term = get_term( $term_id, $taxonomy );
        $parent_id = $term->parent;
        $parents[] = $parent_id;
        while ( $parent_id != 0 )
        {
            $term = get_term( $parent_id, $taxonomy );
            $parent_id = $term->parent;
            $parents[] = $parent_id;
        }
        array_pop($parents);
        return $parents;
    }

endif;

if (!function_exists('tfuse_ajax_get_parents')) :
    /**
     *
     *
     * To override tfuse_ajax_get_parents() in a child theme, add your own tfuse_ajax_get_parents()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_ajax_get_parents ()
    {
        global $TFUSE;
        $id = intval($TFUSE->request->POST('id'));
        $parents = tfuse_get_term_parent( $id,'holiday_locations');
        echo json_encode($parents);
        die();
    }

    add_action('wp_ajax_tfuse_ajax_get_parents','tfuse_ajax_get_parents');
    add_action('wp_ajax_nopriv_tfuse_ajax_get_parents','tfuse_ajax_get_parents');

endif;

if (!function_exists('tfuse_ajax_get_childs')) :
    /**
     *
     *
     * To override tfuse_ajax_get_childs() in a child theme, add your own tfuse_ajax_get_childs()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_ajax_get_childs ()
    {
        global $TFUSE;
        $id = intval($TFUSE->request->POST('id'));
        $childs = get_term_children( $id,'property_locations');
        echo json_encode($childs);
        die();
    }

    add_action('wp_ajax_tfuse_ajax_get_childs','tfuse_ajax_get_childs');
    add_action('wp_ajax_nopriv_tfuse_ajax_get_childs','tfuse_ajax_get_childs');

endif;


if (!function_exists('tfuse_send_reservation_email')) :
    /**
     *
        *
        * To override tfuse_send_reservation_email() in a child theme, add your own tfuse_send_reservation_email()
        * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function tfuse_send_reservation_email()
    {
        global $TFUSE;
        $field1_l = $TFUSE->request->POST('field1_l');
        $field2_l = $TFUSE->request->POST('field2_l');
        $field3_l = $TFUSE->request->POST('field3_l');
        $textarea_l = $TFUSE->request->POST('textarea_l');

        $field1_v = $TFUSE->request->POST('field1_v');
        $field2_v = $TFUSE->request->POST('field2_v');
        $field3_v = $TFUSE->request->POST('field3_v');
        $textarea_v = $TFUSE->request->POST('textarea_v');

        $dates = $TFUSE->request->POST('dates');
        $offer_id = intval($TFUSE->request->POST('offer_id'));

        $the_blogname       = esc_attr(get_bloginfo('name'));

        $the_myemail 	= esc_attr(get_bloginfo('admin_email'));


        $send_options = get_option(TF_THEME_PREFIX . '_tfuse_contact_form_general');
        $message = '';

        if ($field1_l) $message .= "<strong>". $field1_l ."</strong> " . $field1_v . "<br />";
        if ($field2_l) $message .= "<strong>". $field2_l ."</strong> " . $field2_v . "<br />";
        if ($field3_l) $message .= "<strong>". $field3_l ."</strong> " . $field3_v . "<br />";
        if ($textarea_l) $message .= "<strong>". $textarea_l ."</strong> " . $textarea_v . "<br />";
        $message .= "<strong>Dates</strong> " . $dates . "<br />";
        $message .= "<strong>Offer</strong> <a href='".get_permalink($offer_id)."' target='_blank'>" . get_the_title($offer_id) . "</a><br />";


        $headers = __('From:','tfuse') . $the_blogname;
        add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

        if ($send_options['mail_type'] == 'wpmail')
        {
            if(wp_mail($the_myemail, 'From : ' . $the_blogname, $message, $headers))
                echo 'true';
            else
                echo 'false';

            die();
        }
        elseif($send_options['mail_type'] == 'smtp')
        {
            require_once ABSPATH . WPINC . '/class-phpmailer.php';
            require_once ABSPATH . WPINC . '/class-smtp.php';
            $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->IsHTML(true);
            $phpmailer->Port = $send_options['smtp_port'];
            $phpmailer->Host = $send_options['smtp_host'];
            $phpmailer->SMTPAuth = true;
            $phpmailer->SMTPDebug = false;
            $phpmailer->SMTPSecure = ($send_options['secure_conn'] != 'no') ? $send_options['secure_conn'] : null;
            $phpmailer->Username = $send_options['smtp_user'];
            $phpmailer->Password = $send_options['smtp_pwd'];
            $phpmailer->From   = $the_myemail;
            $phpmailer->FromName   = $the_myemail;
            $phpmailer->Subject    = __('From :','tfuse') . ' ' . $the_blogname;
            $phpmailer->Body       = $message;
            $phpmailer->AltBody    = __('To view the message, please use an HTML compatible email viewer!','tfuse');
            $phpmailer->WordWrap   = 50;
            $phpmailer->MsgHTML($message);
            $phpmailer->AddAddress($the_myemail);

            if(!$phpmailer->Send()) {
                echo "false" . $phpmailer->ErrorInfo;
            } else {
                echo "true";
            }
            die();
        }
        else
        {
            if(wp_mail($the_myemail, __('From :','tfuse') . ' ' . $the_blogname, $message, $headers))
            {
                echo 'true';
                die();
            }
            else
            {
                echo 'false';
                die();
            }
        }
    }

endif;
add_action('wp_ajax_tfuse_send_reservation_email','tfuse_send_reservation_email');
add_action('wp_ajax_nopriv_tfuse_send_reservation_email','tfuse_send_reservation_email');

