<?php
/**
 * The template for displaying content tabs in the single.php for holiday template.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since Voyage 1.0
 */
$sidebar_position = tfuse_sidebar_position();
$content_tabs = tfuse_page_options('content_tabs_table',array());
if($sidebar_position == 'full') { echo '<style>.tabs_products>.tabs>li>a { width: 146px; } </style>'; }
if(sizeof($content_tabs) && (!empty($content_tabs[0]['tab_title']) || !empty($content_tabs[0]['tab_content']))) :
$tabs = 0;
?>
<!-- offers tabs -->
<div class="tabs_products">

<ul class="tabs linked">
    <?php
        foreach($content_tabs as $key=>$tab) :
            if(empty($tab['tab_title']) && empty($tab['tab_content'])) continue;
            $tabs++;
            echo '<li><a href="#content_tab_' . $key . '">' . $tab['tab_title'] . '</a></li>';
        endforeach;
    ?>
</ul>

<?php
    if($sidebar_position == 'full') {
        $rows = intval($tabs / 6);
        if ($tabs%6) $rows++;
    }
    else{
        $rows = intval($tabs / 4);
        if ($tabs%4) $rows++;
    }
    $height = 40+(($rows-1)*54);
    echo '<style>.tabs_products>.tabs{ height: '. $height . 'px;}</style>';
    foreach($content_tabs as $tab) :
        if(empty($tab['tab_title']) && empty($tab['tab_content'])) continue;
        echo '<div class="tabcontent">';
            echo apply_filters('themefuse_shortcodes', $tab['tab_content']);
            //get_template_part('holiday','map');
        echo '</div>';
    endforeach;
?>

</div>
<!--/ offers tabs -->
<?php endif; ?>

