<!-- after content -->
<div class="after_content">
    <div class="after_inner">
        <div class="container_12">

            <!--# widgets area, col 1 -->
            <div class="widgetarea widget_col_1">

                <?php // dynamic_sidebar('after-content-1'); ?>
                
                <?php echo do_shortcode('[testimonials title="Відгуки" order="RAND"]') ?>

            </div>
            <!--/ widgets area, col 1 -->

            <!--# widgets area, col 2 -->
            <div class="widgetarea widget_col_2">

                <?php dynamic_sidebar('after-content-2'); ?>

            </div>
            <!--/ widgets area, col 2 -->

            <!--# widgets area, col 3 -->
            <div class="widgetarea widget_col_3">

                <?php dynamic_sidebar('after-content-3'); ?>

            </div>
            <!--/ widgets area, col 3 -->

            <div class="clear"></div>
        </div>
    </div>
</div>
<!--/ after content -->