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

<script>
UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/VC5NWzqu1at8gBZBUOFIQ.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();

UserVoice.push(['set', {
  accent_color: '#31b4a4',
  trigger_color: 'white',
  trigger_background_color: 'rgba(12, 33, 43, 0.6)'
}]);

UserVoice.push(['identify', {
}]);

UserVoice.push(['addTrigger', { mode: 'contact', trigger_position: 'bottom-right' }]);
UserVoice.push(['autoprompt', {}]);
</script>

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
    <script src="<?php bloginfo('template_directory'); ?>/js/retina.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/js/sticky.js"></script>
    <script src="<?php bloginfo('template_directory'); ?>/assets/js/holder.js"></script>

    <!-- scripts concatenated and minified via ant build script-->
    <script src="<?php bloginfo ('template_directory'); ?>/js/plugins.js"></script>
    <script src="<?php bloginfo ('template_directory'); ?>/js/script.js"></script>

    <script>
    jQuery(window).load(function(){
      jQuery(".navbar-collapse").sticky({ topSpacing: 0 });
    });
    </script>

    <?php wp_footer(); ?>
  </body>
  </html>