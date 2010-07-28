  </div>

  <div id="footer" role="contentinfo">
    <div id="colophon">

      <?php get_sidebar( 'footer' ); ?>

      <div id="site-info">
        <a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
          <?php bloginfo( 'name' ); ?>
        </a>
      </div><!-- #site-info -->

      <div id="site-generator">
        <?php do_action( 'twentyten_credits' ); ?>
        <a href="<?php echo esc_url( __('http://wordpress.org/', 'twentyten') ); ?>"
        title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'twentyten'); ?>" rel="generator">
          <?php printf( __('Proudly powered by %s.', 'twentyten'), 'WordPress' ); ?>
        </a>
      </div><!-- #site-generator -->

    </div><!-- #colophon -->
  </div><!-- #footer -->

</body>

</html>
