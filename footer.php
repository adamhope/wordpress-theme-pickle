
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
  <script src="<?php bloginfo('template_directory');?>/js/tooltip.min.js"></script>
  <script src="<?php bloginfo('template_directory');?>/js/pickle.js"></script>
  <script>
    var opts  = opts  || {},
        posts = posts || {};
    opts.templateDir = '<?php bloginfo('template_directory');?>';
    $(document).ready(function() {
      Slideshow.init(opts);
      Browser.init(opts, posts);
    });
  </script>

</body>
</html>
