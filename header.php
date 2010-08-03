<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package PickleTheme
 */

if (is_home()) {
    $args = array(
      'posts_per_page' => 1,
      'paged' => $paged,
      'category_name' => 'Photos'
    );
    query_posts($args);
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <title><?php
  	/*
  	 * Print the <title> tag based on what is being viewed.
  	 */
  	global $page, $paged;

  	wp_title( '|', true, 'right' );

  	// Add the blog name.
  	bloginfo( 'name' );

  	// Add the blog description for the home/front page.
  	$site_description = get_bloginfo( 'description', 'display' );
  	if ( $site_description && ( is_home() || is_front_page() ) )
  		echo " | $site_description";

  	// Add a page number if necessary:
  	if ( $paged >= 2 || $page >= 2 )
  		echo ' | ' . sprintf( __( 'Page %s', 'reflection' ), max( $paged, $page ) );

  	?></title>
  <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

  <?php if (is_home_uri() || is_single()):?>
    <script type="text/javascript">
      var Site = {}, Browse = {};
    </script>
  <?php endif;?>

  <?php wp_head(); ?>

</head>
<body>

<div id="frame">
  <div id="header">
    <h1 id="site-title">
  		<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
  		  <?php bloginfo( 'name' ); ?>
  		</a>
		</h1>
		
		<div id="site-description"><?php bloginfo( 'description' ); ?></div>
    
    <div id="navbar">
      <?php wp_nav_menu(); ?>
    </div>

  </div>
