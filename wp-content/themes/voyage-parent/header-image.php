<?php
    /**
    * Daca a fost selectat sa fie imagine in header, optinem imaginea
    * in functia tfuse_get_header_content() ce se afla in 
    * theme_config/theme_includes/THEME_HEADER_CONTENT.php
    * imaginea se transmite prin variabila globala $header_image
    */
    global $header_image;
    if ( !empty($header_image) ) :
?>

    <!-- header image/slider -->
    <div class="header_bot header_image">
        <div class="container">
            <?php
                $image = new TF_GET_IMAGE();
                echo $image->width(960)->height(142)->src($header_image)->get_img();
            ?>
        </div>
    </div>
    <!--/ header image/slider -->

<?php else: ?>

    <div class="header_bot"></div>

<?php endif; ?>
