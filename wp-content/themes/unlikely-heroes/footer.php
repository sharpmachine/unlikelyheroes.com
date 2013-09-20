
<footer id="footer" role="contentinfo">
	<div class="container">
		<div id="site-info">
			&copy;<?php echo date ('Y'); ?><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<?php bloginfo( 'name' ); ?></a>
			| <a href="http://getbootstrap.com/" target="_blank">Bootstrap Docs</a><!-- Remove for production -->
		</div><!-- #site-info -->
	</div>
</footer>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php bloginfo('template_directory'); ?>/js/transition.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/alert.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/modal.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/dropdown.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/scrollspy.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/tab.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/tooltip.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/popover.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/button.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/collapse.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/carousel.js"></script>

    <!-- scripts concatenated and minified via ant build script-->
    <script src="<?php bloginfo ('template_directory'); ?>/js/plugins.js"></script>
    <script src="<?php bloginfo ('template_directory'); ?>/js/script.js"></script>

    <!-- Remove these before deploying to production -->
    <script src="<?php bloginfo ('template_directory'); ?>/js/hashgrid.js" type="text/javascript"></script>

    <script type="text/javascript">
    	var grid = new hashgrid({ numberOfGrids: 1 });
    </script>


    <?php wp_footer(); ?>
  </body>
  </html>