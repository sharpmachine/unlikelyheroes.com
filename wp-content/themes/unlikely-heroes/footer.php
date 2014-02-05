<div class="section section-gray newletter">
  <?php get_template_part('newsletter','signup'); ?>
</div>

<footer id="footer" role="contentinfo">
	<div class="container">
		<div class="social-media">
      <?php get_template_part('social','media'); ?>
    </div>
  </div>
  <div class="site-info">
    <div class="container">
      <a href="<?php bloginfo('url' ); ?>/privacy-policy">Privacy Policy</a> | <a href="<?php bloginfo('url' ); ?>/terms-conditions">Terms &amp; Conditions</a> | &copy;<?php echo date('Y') ?> Unlikely Heroes | Branding &amp; site design by <a href="http://sharpmachinemedia.com">Sharp Machine.</a>
    </div>
  </div>
</footer>


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/plugins-min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/script-min.js"></script>

    <?php wp_footer(); ?>
  </body>
  </html>