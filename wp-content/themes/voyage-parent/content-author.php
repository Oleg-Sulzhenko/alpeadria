<?php
/**
 * The template for displaying content in the single.php template.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since Voyage 1.0
 */
?>

<?php
    /**
     * tfuse_user_profile() function is located in theme_config/theme_includes/THEME_FUNCTIONS.php
     * Aceasta functie returneaza lista cu meta despre user in dependenta de fieldurile cerute
     * si daca ele sunt completate in user profile. ca fielduri pot fi cele standarte sau
     * adaugate cu ajutorul pluginului ThemeFuse Extend User Profile. Pentru fiecare field nou adaugat,
     * trebuie sa fie si o iconita specifica in folderul images/icons/social_{key}.png
     * Create your own tfuse_user_profile() to override in a child theme or use filter tfuse_user_profile.
     * 
     * Specific wich fileds form user profile to retrive: first_name,last_name,email,url,aim,yim,jabber,facebook,twitter etc.
     * 
     * @since Voyage 1.0
     */
    $author_meta = tfuse_user_profile(array('facebook','twitter','in','mojo'));
?>

<?php
    $author_description = get_the_author_meta('description');
    $disable_author_info = tfuse_page_options('disable_author_info',tfuse_options('disable_author_info'));
    if ( !$disable_author_info && (!empty($author_meta) || !empty($author_description)) ) :
?>
    <!-- author description -->
    <div class="author-box">
        <div class="author-description">

            <div class="author-image"><?php echo get_avatar( get_the_author_meta( 'ID' ), '100' ); ?></div>

            <div class="author-text">

                <h4><?php echo get_the_author(); ?></h4>

                <?php if ( !empty($author_description) ) echo '<p>'.$author_description.'</p>'; ?>

                <?php if ( !empty($author_meta) ) : ?>
                <div class="author-contact">
                    <label><?php _e('CONTACT THE AUTHOR:', 'tfuse'); ?></label>
                    <?php foreach($author_meta as $key => $item) : ?>
                        <a href="<?php echo $item;?>"><img src="<?php echo tfuse_get_file_uri('/images/icons/social_'.$key.'_mid.png'); ?>" alt="<?php echo $key; ?>" title="<?php echo $key; ?>" width="25" height="25" border="0" /></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>

            <div class="clear"></div>
        </div>
    </div>
    <!--/ author description -->
<?php endif; ?>
