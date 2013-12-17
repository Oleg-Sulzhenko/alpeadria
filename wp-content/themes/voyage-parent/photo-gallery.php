<?php
    global $post;
    $attachments = tfuse_get_gallery_images($post->ID,TF_THEME_PREFIX . '_slider_images');
    $slider_images = array();
    if ($attachments) {
        foreach ($attachments as $attachment){
            if( isset($attachment->image_options['imgexcludefromslider_check']) ) continue;

            $slider_images[] = array(
                'id'            => $attachment->ID,
                'title'         => apply_filters('the_title', $attachment->post_title),
                'order'         => $attachment->menu_order,
                'img_full'      => $attachment->guid
            );
        }
    }

    if(sizeof($slider_images)) :
    $slider_images = tfuse_aasort($slider_images,'order');
    ?>
    <style>
        #caption .current{
            bottom: 0;
        }
    </style>
    <!-- Photo Gallery -->
    <div class="gal-wrap">

        <div id="gallery" class="gal-content">
            <div class="slideshow-container">
                <div id="loading" class="loader"></div>
                <div id="slideshow" class="gal-slideshow"></div>
                <div id="caption" class="caption-container"></div>
            </div>
        </div>

        <div class="gal-right">
            <div id="thumbs" class="gal-nav">
                <ul class="thumbs noscript">
                    <?php foreach($slider_images as $slide) : ?>
                    <li>
                        <a class="thumb" href="<?php print TF_GET_IMAGE::get_src_link($slide['img_full'], 660, 348); ?>" title="">
                            <img src="<?php print TF_GET_IMAGE::get_src_link($slide['img_full'], 75, 75); ?>" alt="">
                        </a>
                        <div class="caption">
                            <a href="<?php echo $slide['img_full']?>" class="enlarge" rel="prettyPhoto[holiday]" data-id="<?php echo $slide['id']; ?>" title="<?php echo $slide['title']?>"><?php _e('View Large', 'tfuse'); ?></a>
                            <div class="image-desciption"><?php echo $slide['title']?></div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div id="controls" class="controls"></div>

        </div>
        <div class="clear"></div>

    </div>
    <div class="hidden_attachments" style="display: none;">
        <?php $slider_images = array_merge(array(),$slider_images); ?>
        <?php foreach ($slider_images as $attachment): echo '1111=='; ?>
            <a href="<?php echo $attachment['img_full'];?>" rel="prettyPhoto[holiday]" title="<?php echo $attachment['title']; ?>" data-id="<?php echo $attachment['id']; ?>"></a>
        <?php endforeach; ?>
    </div>
    <script>
        jQuery(document).ready(function() {
            var onMouseOutOpacity = 0.67;
            jQuery('#thumbs ul.thumbs li').opacityrollover({
                mouseOutOpacity:   onMouseOutOpacity,
                mouseOverOpacity:  1.0,
                fadeSpeed:         'fast',
                exemptionSelector: '.selected'
            });
            var captionOpacity = 0.70;
            // Initialize Advanced Galleriffic Gallery
            var gallery = jQuery('#thumbs').galleriffic({
                delay:                     4500,
                numThumbs:                 9,
                preloadAhead:              3,
                enableTopPager:            false,
                enableBottomPager:         true,
                maxPagesToShow:            7,
                imageContainerSel:         '#slideshow',
                controlsContainerSel:      '#controls',
                captionContainerSel:       '#caption',
                loadingContainerSel:       '#loading',
                renderSSControls:          true,
                renderNavControls:         true,
                playLinkText:              '<?php _e('Play', 'tfuse'); ?>',
                pauseLinkText:             '<?php _e('Pause', 'tfuse'); ?>',
                prevLinkText:              '<?php _e('&lsaquo; Previous', 'tfuse'); ?>',
                nextLinkText:              '<?php _e('Next &rsaquo;', 'tfuse'); ?>',
                nextPageLinkText:          '<?php _e('Next &rsaquo;', 'tfuse'); ?>',
                prevPageLinkText:          '<?php _e('&lsaquo; Prev', 'tfuse'); ?>',
                enableHistory:             false,
                autoStart:                 true,
                syncTransitions:           true,
                defaultTransitionDuration: 1000,
                /*onSlideChange:             function(prevIndex, nextIndex) {
                    // 'this' refers to the gallery, which is an extension of $('#thumbs')
                    this.find('ul.thumbs').children()
                            .eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
                            .eq(nextIndex).fadeTo('fast', 1.0);
                },*/
                onTransitionOut:           function(slide, caption, isSync, callback) {
                    slide.fadeTo(this.getDefaultTransitionDuration(isSync), 0.0, callback);
                    caption.fadeTo(this.getDefaultTransitionDuration(isSync), 0.0);
                    jQuery('.hidden_attachments a').attr('rel', 'prettyPhoto[holiday]');
                },
                onTransitionIn:            function(slide, caption, isSync) {
                    var duration = this.getDefaultTransitionDuration(isSync);
                    slide.fadeTo(duration, 1.0);

                    // Position the caption at the bottom of the image and set its opacity
                    var slideImage = slide.find('img');
                    caption.width(slideImage.width())
                            .css({
                                'bottom' : Math.floor((slide.height() - slideImage.height()) / 2),
                                'left' : Math.floor((slide.width() - slideImage.width()) / 2) + slideImage.width() - slideImage.width()
                            })
                            .fadeTo(duration, captionOpacity);
                    var have_rel = jQuery(".gal-wrap a[rel^='prettyPhoto']");
                    have_rel.each(function(){
                        var current = jQuery(this).attr('data-id');
                        jQuery('.hidden_attachments a[data-id="' + current + '"]').removeAttr('rel');
                    });
                    jQuery("a[rel^='prettyPhoto']").prettyPhoto({social_tools:false});
                },
                onPageTransitionOut:       function(callback) {
                    this.fadeTo('fast', 0.0, callback);
                },
                onPageTransitionIn:        function() {
                    this.fadeTo('fast', 1.0);
                }
            });
        });
    </script>
    <!--/ Photo Gallery -->
    <?php endif; ?>