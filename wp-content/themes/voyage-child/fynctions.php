<?php

$templates = wp_get_theme()->get_page_templates();
foreach ($templates as $template_name => $template_filename) {
    echo "$template_name ($template_filename)<br />";
}
 
?>

