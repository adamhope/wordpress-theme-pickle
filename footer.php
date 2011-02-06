
  <footer>
    <div id="footer-wrapper" class="clearf">
      <?php get_sidebar('footer'); ?>
      <div id="copyright" class="clearf">
        <small id="site-info">
          <a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
            <?php bloginfo('name'); ?>
          </a>
          <?php do_action('pickle_credits'); ?>
          <a href="<?php echo esc_url( __('http://wordpress.org/', 'pickle') ); ?>" title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'pickle'); ?>" rel="generator">
            <?php printf( __('Proudly powered by %s.', 'pickle'), 'WordPress' ); ?>
          </a>
        </small>
      </div>
    </div>
  </footer>

</div>

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script>!window.jQuery && document.write('<script src="<?php bloginfo('template_directory');?>/js/jquery-1.4.2.min.js"></sc'+'ript>')</script>
  <script src="<?php bloginfo('template_directory');?>/3rdparty/ProLoser-AnythingSlider-6958793/js/jquery.anythingslider.min.js"></script>

  <!-- TODO: find out why anythingSlider theme loading doesn't work -->
  <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/theme-reflection.css">
  <script>
  jQuery().ready(function() {
    $('#slider').anythingSlider({
      theme               : "reflection",
      // themeDirectory      : "<?php bloginfo('template_directory');?>/theme-reflection.css",
      pauseOnHover: true
    });
  });
  </script>

</body>
</html>
