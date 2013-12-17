<?php

if ( ! function_exists( 'tfuse_header_content' ) ):
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which runs
     * before the init hook. The init hook is too late for some features, such as indicating
     * support post thumbnails.
     *
     * To override tfuse_slider_type() in a child theme, add your own tfuse_slider_type to your child theme's
     * functions.php file.
     */

    function tfuse_header_content($location = false)
    {   
        global $wp_query,$is_tf_front_page,$is_tf_blog_page,$TFUSE,$post, $search, $header_element,$before_content_element, $latest_added_nr;
        $posts = $header_element = $header_image = $before_content_element = $slider = $after_content_element = null;

        if (!$location) return;
        switch ($location)
        {
            case 'header' :
                if ($is_tf_front_page)
                {
                    $home_page = $post->ID;
                    if(tfuse_options('use_page_options', false) && ($home_page > 0))
                        $header_element = tfuse_page_options('header_element', null, $home_page);
                    else
                         $header_element = tfuse_options('header_element','none');

                    if ( ('slider' == $header_element) && tfuse_options('use_page_options', false) )
                        $slider = tfuse_page_options('select_slider', null, $home_page);
                    else if (('slider' == $header_element) && (!tfuse_options('use_page_options', false)))
                        $slider = tfuse_options('select_slider', null);
                    else    return;
                }
                elseif($is_tf_blog_page)
                {
                    $header_element = tfuse_options('header_element_blog', null);

                    if ( 'slider' == $header_element ) $slider = tfuse_options('select_slider_blog', null); else return;

                }
                elseif ( is_singular() )
                {
                    $ID = $wp_query->post->ID;
                    $header_element = tfuse_page_options('header_element', null, $ID);
                    if( 'none' == $header_element)
                    {
                        return;
                    }
                    elseif ( 'slider' == $header_element)
                    {
                        $slider = tfuse_page_options('select_slider', null, $ID);
                    }
                    else
                        return;

                }
                elseif ( is_category() )
                {
                    $ID = $wp_query->query_vars['cat'];
                    $header_element = tfuse_options('header_element', null, $ID);
                    if( 'none' == $header_element)
                    {
                        return;
                    }
                    elseif ( 'slider' == $header_element)
                    {
                        $slider = tfuse_options('select_slider', null, $ID);
                    }
                    else
                        return;
                }
                elseif (is_tax())
                {
                    $ID = $wp_query->queried_object->term_id;
                    $header_element = tfuse_options('header_element', null, $ID);
                    if( 'none' == $header_element)
                    {
                        return;
                    }
                    elseif ( 'slider' == $header_element)
                    {
                        $slider = tfuse_options('select_slider', null, $ID);
                    }
                    else
                        return;
                }
                elseif(is_search())
                {
                    $header_element = tfuse_options('header_element_search', null);

                    if ( 'slider' == $header_element ) $slider = tfuse_options('select_slider_search', null); else return;

                }
                elseif(is_404())
                {
                    $header_element = tfuse_options('header_element_404', null);

                    if ( 'slider' == $header_element ) $slider = tfuse_options('select_slider_404', null); else return;
                }
                elseif(is_author())
                {
                    $header_element = tfuse_options('header_element_author', null);

                    if ( 'slider' == $header_element ) $slider = tfuse_options('select_slider_author', null); else return;
                }
                elseif(is_tag())
                {
                    $header_element = tfuse_options('header_element_tag', null);

                    if ( 'slider' == $header_element ) $slider = tfuse_options('select_slider_tag', null); else return;
                }
                elseif(is_archive())
                {
                    $header_element = tfuse_options('header_element_archive', null);

                    if ( 'slider' == $header_element ) $slider = tfuse_options('select_slider_archive', null); else return;
                }


                break;

            case 'before_content' :
                if ($is_tf_front_page)
                {
                    $home_page = $post->ID;
                    if(tfuse_options('use_page_options', false) && ($home_page > 0))
                    {
                        $before_content_element = tfuse_page_options('before_content_element', 'search', $home_page);

                        if('search' == $before_content_element)
                        {
                            $search['type'] = tfuse_page_options('search_type', 'closed', $home_page);
                        }
                        else return;
                    }
                    else
                    {
                        $before_content_element = tfuse_options('before_content_element', 'search');

                        if('search' == $before_content_element)
                        {
                            $search['type'] = tfuse_options('search_type', 'closed');
                        }
                        else return;
                    }

                }
                elseif($is_tf_blog_page)
                {
                    $before_content_element = tfuse_options('before_content_element_blog', 'search');

                    if('search' == $before_content_element)
                    {
                        $search['type'] = tfuse_options('search_type_blog', 'closed');
                    }
                    else return;
                }
                elseif ( is_singular() )
                {
                    $ID = $wp_query->post->ID;
                    $before_content_element = tfuse_page_options('before_content_element', 'search', $ID);

                    if('search' == $before_content_element)
                    {
                        $search['type'] = tfuse_page_options('search_type', 'closed', $ID);
                    }
                    else return;
                }
                elseif( is_category())
                {
                    $ID = $wp_query->query_vars['cat'];
                    $before_content_element = tfuse_options('before_content_element', 'search', $ID);

                    if('search' == $before_content_element)
                    {
                        $search['type'] = tfuse_options('search_type', 'closed', $ID);
                    }
                    else return;
                }
                elseif (is_tax())
                {
                    $ID = $wp_query->queried_object->term_id;
                    $before_content_element = tfuse_options('before_content_element', 'search', $ID);

                    if('search' == $before_content_element)
                    {
                        $search['type'] = tfuse_options('search_type', 'closed', $ID);
                    }
                    else return;
                }
                elseif(is_search())
                {
                    $before_content_element = tfuse_options('before_content_element_search', 'search');

                    if('search' == $before_content_element)
                    {
                        $search['type'] = tfuse_options('search_type_search', 'closed');
                    }
                    else return;
                }
                elseif(is_404())
                {
                    $before_content_element = tfuse_options('before_content_element_404', 'search');

                    if('search' == $before_content_element)
                    {
                        $search['type'] = tfuse_options('search_type_404', 'closed');
                    }
                    else return;
                }
                elseif(is_tag())
                {
                    $before_content_element = tfuse_options('before_content_element_tag', 'search');

                    if('search' == $before_content_element)
                    {
                        $search['type'] = tfuse_options('search_type_tag', 'closed');
                    }
                    else return;
                }
                elseif(is_author())
                {
                    $before_content_element = tfuse_options('before_content_element_author', 'search');

                    if('search' == $before_content_element)
                    {
                        $search['type'] = tfuse_options('search_type_author', 'closed');
                    }
                    else return;
                }
                elseif(is_archive())
                {
                    $before_content_element = tfuse_options('before_content_element_archive', 'search');

                    if('search' == $before_content_element)
                    {
                        $search['type'] = tfuse_options('search_type_archive', 'closed');
                    }
                    else return;
                }
                break;
            case 'after_content' :
                if ($is_tf_front_page)
                {
                    $home_page = $post->ID;
                    if(tfuse_options('use_page_options', false) && ($home_page > 0))
                    {
                        $after_content_element = tfuse_page_options('after_content_element', 'after_content_widgets', $home_page);
                    }
                    else
                    {
                        $after_content_element = tfuse_options('after_content_element', 'after_content_widgets');
                    }

                }
                elseif($is_tf_blog_page)
                {
                    $after_content_element = tfuse_options('after_content_element_blog', 'after_content_widgets');
                }
                elseif ( is_singular() )
                {
                    $ID = $wp_query->post->ID;
                    if(get_post_type($ID) == TF_SEEK_HELPER::get_post_type())
                        $after_content_element = tfuse_page_options('after_content_element', 'similar_holidays', $ID);
                    else
                        $after_content_element = tfuse_page_options('after_content_element', 'after_content_widgets', $ID);
                }
                elseif( is_category())
                {
                    $ID = $wp_query->query_vars['cat'];
                    $after_content_element = tfuse_options('after_content_element', 'after_content_widgets', $ID);
                }
                elseif (is_tax())
                {
                    $ID = $wp_query->queried_object->term_id;
                    $after_content_element = tfuse_options('after_content_element', 'after_content_widgets', $ID);
                }
                elseif(is_search())
                {
                    $after_content_element = tfuse_options('after_content_element_search', 'after_content_widgets');
                }
                elseif(is_404())
                {
                    $after_content_element = tfuse_options('after_content_element_404', 'after_content_widgets');
                }
                elseif(is_tag())
                {
                    $after_content_element = tfuse_options('after_content_element_tag', 'after_content_widgets');
                }
                elseif(is_author())
                {
                    $after_content_element = tfuse_options('after_content_element_author', 'after_content_widgets');
                }
                elseif(is_archive())
                {
                    $after_content_element = tfuse_options('before_content_element_archive', 'after_content_widgets');

                }
                break;
        }

        if ($location == 'after_content')
        {
            if($after_content_element === 'after_content_widgets')
                get_template_part('after', 'content');
            elseif($after_content_element === 'similar_holidays')
                get_template_part('similar','holidays');

            return;
        }

        if( $before_content_element == 'search' )
        {
            get_template_part( 'before', 'content' );
            return;
        }        
        elseif ( !$slider ) return;
          
        $slider = $TFUSE->ext->slider->model->get_slider($slider);
        if(!isset($slider['general'])) return;

        if(!isset($ID) || empty($ID)) $ID = rand(100,1000);
        switch ($slider['type']):

            case 'custom':
                
                if ( is_array($slider['slides']) ) :
                    $slider_image_resize = ( isset($slider['general']['slider_image_resize']) && $slider['general']['slider_image_resize'] == 'true' ) ? true : false;
                    foreach ($slider['slides'] as $k => $slide) :
                        $image = new TF_GET_IMAGE();
                        $slider['slides'][$k]['slide_src'] = $image->width(1250)->height(467)->src($slide['slide_src'])->resize($slider_image_resize)->get_src();
                    endforeach;
                endif;

                break;

        endswitch;

        if ( !is_array($slider['slides']) ) return;

        include(locate_template( '/theme_config/extensions/slider/designs/'.$slider['design'].'/template.php' ));
      
    }

endif;



