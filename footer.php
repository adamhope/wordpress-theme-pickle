
  <footer role="contentinfo">
    <div id="colophon">

      <div id="footer-widget-area">
        <?php get_sidebar('footer'); ?>
      </div>

      <small id="site-info">
        <a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
          <?php bloginfo('name'); ?>
        </a>
        <?php do_action( 'reflection_credits' ); ?>
        <a href="<?php echo esc_url( __('http://wordpress.org/', 'pickle') ); ?>"
        title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'pickle'); ?>" rel="generator">
          <?php printf( __('Proudly powered by %s.', 'pickle'), 'WordPress' ); ?>
        </a>
      </small>

    </div><!-- #colophon -->
  </footer>

</div>

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script>!window.jQuery && document.write('<script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jquery-1.4.2.min.js"></sc'+'ript>')</script>
  <script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/tooltip.js"></script>
  <script src="<?php bloginfo('template_directory');?>/js/pickle.min.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
    browseOpts.templateDir = '<?php bloginfo('template_directory');?>';
    opts.templateDir = '<?php bloginfo('template_directory');?>';
    $(document).ready(Pickle.init);
    $(document).ready(Browse.init);
  </script>

</body>

</html>
