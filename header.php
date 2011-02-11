<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Pickle
 */

?>
<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
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
  		echo ' | ' . sprintf( __( 'Page %s', 'pickle' ), max( $paged, $page ) );
	?></title>

  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="stylesheet" href="<?php bloginfo('template_directory');?>/reset.css" />
  <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/3rdparty/orbit/orbit.css">
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

  <?php wp_head(); ?>

  <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
  <script src="<?php bloginfo('template_directory');?>/js/modernizr-1.5.min.js"></script>

</head>
<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->

<div id="frame">
  <header>
    <div id="header-wrapper" class="clearf">
      <hgroup>
        <h1 id="site-title">
      		<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
      		  <?php bloginfo('name'); ?>
      		</a>
    		</h1>
        <!-- <h2 id="site-description"><?php bloginfo('description'); ?></h2> -->
    	</hgroup>
      <nav role="navigation">
        <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
      </nav>
    </div>
  </header>
