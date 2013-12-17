<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

// Some Seek class options

$options = array(
    'post_type' => 'holiday'
        // Name of post type used for holidays.
        // If you change this:
        // - rename the file '<post_type>_options.php'
        // - rename  theme_config/options/'<post_type>_options.php'
        // - custom taxonomies defined in ../includes/custom_taxonomies.php contain in their names <post_type>_<taxonomy-name> !
        // - and maybe somewhere else where is used '<post_type>' ...
);