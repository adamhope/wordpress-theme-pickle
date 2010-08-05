  </div>

  <div id="footer" role="contentinfo">
    <div id="colophon">

      <?php get_sidebar('footer'); ?>

      <div id="site-info">
        <a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
          <?php bloginfo('name'); ?>
        </a>
      </div><!-- #site-info -->

      <div id="site-generator">
        <?php do_action( 'reflection_credits' ); ?>
        <a href="<?php echo esc_url( __('http://wordpress.org/', 'reflection') ); ?>"
        title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'reflection'); ?>" rel="generator">
          <?php printf( __('Proudly powered by %s.', 'reflection'), 'WordPress' ); ?>
        </a>
      </div><!-- #site-generator -->

    </div><!-- #colophon -->
  </div><!-- #footer -->
  
  <script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/jquery-1.4.2.min.js"></script>
  <script src="<?php bloginfo('template_directory');?>/js/pickle.js" type="text/javascript" charset="utf-8"></script>

  <script type="text/javascript">
    opts.templateDir = '<?php bloginfo('template_directory');?>';
    $(document).ready(Pickle.init);
  </script>

</body>

</html>
