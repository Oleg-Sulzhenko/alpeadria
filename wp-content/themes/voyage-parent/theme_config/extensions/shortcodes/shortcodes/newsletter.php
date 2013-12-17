<?php

/**
 * Newsletter
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 * 
 * Optional arguments:
 * title: e.g. Newsletter signup
 * text: e.g. Thank you for your subscribtion.
 * action: URL where to send the form data.
 * rss_feed:
 */

function tfuse_newsletter($atts, $content = null)
{
    extract(shortcode_atts(array('title' => '', 'text' => '', 'rss_feed' => '','sendtitle' => 'Send'), $atts));
    $custom_id = rand(100,1000);

    if (empty($title))
        $title = __('Newsletter', 'tfuse');
   /* if (empty($text))
        $text = __('Sign up for our weekly newsletter to receive updates, news, and promos:', 'tfuse');*/

    $out = '
    <div class="widget-container newsletter_subscription_box newsletterBox">
    <div class="inner">
        <h3>' . $title . '</h3>

        <div class="newsletter_subscription_messages before-text">

            <div class="newsletter_subscription_message_success">' . __('Thank you for your subscribtion.','tfuse') . '</div>
            <div class="newsletter_subscription_message_wrong_email" style="padding-left: 25px;">' . __('Your email format is wrong!','tfuse') . '</div>
            <div class="newsletter_subscription_message_failed">' . __('Sad, but we couldn\'t add you to our mailing list ATM.','tfuse') . '</div>
        </div>

        <form action="#" method="post" class="newsletter_subscription_form">
            <input type="text" value="' . __('Enter your email address' , 'tfuse') . '" onfocus="if (this.value == \'' . __('Enter your email address' , 'tfuse') . '\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'' . __('Enter your email address' , 'tfuse') . '\';}"  name="newsletter" class="newsletter_subscription_email inputField" />';
            if ( $rss_feed == 'true' )
                $out .= '<div class="rowCheckbox input_styled checklist"><input type="checkbox" name="subscribe" id="subscribe' . $custom_id . '" value="1"> <label for="subscribe' . $custom_id . '"> ' . __('Subscribe to RSS', 'tfuse') . '</label></div>';
            $out .= '<input type="submit" value="' . $sendtitle . '" class="btn-submit newsletter_subscription_submit" />';
        $out .= '</form><div class="newsletter_subscription_ajax">'. __('Loading','tfuse').'...</div>';
    $out .= '</div></div>';

    return $out;
}

$atts = array(
    'name' => __('Newsletter', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.', 'tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Title', 'tfuse'),
            'desc' => __('Enter the title of the Newsletter form', 'tfuse'),
            'id' => 'tf_shc_newsletter_title',
            'value' => 'SUBSCRIBE TO NEWSLETTER:',
            'type' => 'text'
        ),
       /* array(
            'name' => __('Text', 'tfuse'),
            'desc' => __('Specify the newsletter message', 'tfuse'),
            'id' => 'tf_shc_newsletter_text',
            'value' => 'Sign up for our weekly newsletter to receive updates, news, and promos:',
            'type' => 'textarea'
        ),*/
        array(
            'name' => __('RSS Feed', 'tfuse'),
            'desc' => __('Show RSS Feed link?', 'tfuse'),
            'id' => 'tf_shc_newsletter_rss_feed',
            'value' => 'false',
            'type' => 'checkbox'
        ),
        array(
            'name' => __('Send Title', 'tfuse'),
            'desc' => __('Title for send button', 'tfuse'),
            'id' => 'tf_shc_newsletter_sendtitle',
            'value' => 'Send',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('newsletter', 'tfuse_newsletter', $atts);
