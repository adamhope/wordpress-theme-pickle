<?php
/**
 * The template for displaying generic archives.
 *
 */

get_header(); ?>

  <?php
  	/* Queue the first post, that way we know
  	 * what date we're dealing with (if that is the case).
  	 *
  	 * We reset this later so we can run the loop
  	 * properly with a call to rewind_posts().
  	 */
  	if ( have_posts() )
  		the_post();
  ?>

<div id="main">

  <h1 class="page-title">
    <?php single_cat_title(); ?>
  </h1>

<?php
/* Since we called the_post() above, we need to
* rewind the loop back to the beginning that way
* we can run the loop properly, in full.
*/
rewind_posts();
get_template_part( 'loop', 'archive' );
?>

</div>

<?
// Get footer.
get_footer();
?>
