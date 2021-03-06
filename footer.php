
  <footer class="secondary-content clearf">
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
  </footer>

</div>

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script>!window.jQuery && document.write('<script src="<?php bloginfo('template_directory');?>/js/jquery-1.4.2.min.js"></sc'+'ript>')</script>
  <script src="<?php bloginfo('template_directory');?>/3rdparty/orbit/jquery.orbit.js"></script>
  <script type="text/javascript">
    $(window).load(function() {
      $('.slideshow').orbit({
        bullets: true,
        directionalNav: false
      });
    });
  </script>

</body>
</html>
