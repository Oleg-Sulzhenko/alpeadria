<?php
/**
 * The template for displaying content in the template-contact.php template.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since Voyage 1.0
 */
?>

<?php

    wp_enqueue_script( 'contactform', tfuse_get_file_uri('js/contactform.js'), array('jquery'), '2.0', true );

    $params = array( 'contactform_uri' => tfuse_get_file_uri('theme_config/theme_includes/CONTACTFORM.php') );

    wp_localize_script( 'contactform', 'ContactFormParams', $params );

    add_action( 'wp_footer', create_function( '', 'wp_print_scripts( "contactform" );' ) );
?>

<!-- add comment -->
<div class="add-comment contact-form" id="addcomments">

    <div class="add-comment-title">
        <h3><?php _e('Leave a Reply','tfuse'); ?></h3>
    </div>

    <div class="comment-form">
        <form action="#" method="post" class="ajax_form" name="contactForm" id="contactForm">

            <div class="row alignleft">
                <label><strong><?php _e('Name','tfuse');?></strong></label>
                <input type="text" name="yourname" value="" class="inputtext input_middle required">
            </div>

            <div class="space"></div>

            <div class="row  alignleft">
                <label><strong><?php _e('Email','tfuse'); ?></strong><?php echo' ('; _e('never published','tfuse'); echo ')';?></label>
                <input type="text" name="email" value="" class="inputtext input_middle required">
            </div>

            <div class="clear"></div>

            <div class="row">
                <label><strong><?php _e('Website','tfuse');?></strong></label>
                <input type="text" name="url" value="" class="inputtext input_full">
            </div>

            <div class="row">
                <label><strong><?php _e('Comment','tfuse');?></strong></label>
                <textarea cols="30" rows="10" name="message" class="textarea textarea_middle required"></textarea>
            </div>

            <input id="send" type="submit" value="SEND MESSAGE" class="btn-submit">
        </form>

<!--/add comment -->

    </div>
</div>

<div id="reservation_send_ok">
    <h2><?php _e('Your message has been sent!', 'tfuse') ?></h2>
    <?php _e('Thank you for contacting us,', 'tfuse') ?><br /><?php _e('We will get back to you within 2 business days.', 'tfuse') ?>
</div>

<div id="reservation_send_failure">
    <h2><?php _e('Oops!', 'tfuse') ?></h2>
    <?php _e('Due to an unknown error, your form was not submitted, please resubmit it or try later.', 'tfuse') ?>
</div>