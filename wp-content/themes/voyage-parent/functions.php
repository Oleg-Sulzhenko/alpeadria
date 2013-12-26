<?php

/**
 * WARNING: This file is part of the core ThemeFuse Framework. It is not recommended to edit this section
 *
 * @package ThemeFuse
 * @since 2.0
 */
function wptutsplus_change_post_menu_label() {
    global $menu;
    $menu[5][0] = 'Новини';
}
add_action( 'admin_menu', 'wptutsplus_change_post_menu_label' );

require_once(TEMPLATEPATH . '/framework/BootsTrap.php');
require_once(TEMPLATEPATH . '/theme_config/theme_includes/AJAX_CALLBACKS.php');

 
 