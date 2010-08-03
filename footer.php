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

  <script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/mootools-1.2.1-core.js"></script>
  <script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/mootools-1.2-more.js"></script>
  <script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/blog.js"></script>
  <!-- TODO - Only required in photo archive pages -->
  <script type="text/javascript" src="<?php bloginfo('template_directory');?>/js/browser.js"></script>
  
  <?if (is_home_uri() || is_single()):?>
    <script type="text/javascript">
      Site.templateDir = '<?php bloginfo('template_directory');?>';
      window.addEvent('load', Site.init.bind(Site));
      // TODO - only required in photo archive
      Browse.templateDir = '<?php bloginfo('template_directory');?>';
      window.addEvent('load', Browse.init.bind(Browse));
    </script>
  <?php endif;?>

</body>

</html>
