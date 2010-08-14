<?php
/**
 * Standard page template.
 *
 * @package Reflection
 */

get_header();

if (have_posts()) : while (have_posts()) : the_post();
?>

<div id="main">
  <h2><?php the_title();?></h2>
  <?php the_content('Read more...');?>
</div>

<?php
endwhile; endif;

get_footer();

?>
