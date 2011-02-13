<?php
/**
 * Standard page template.
 *
 * @package Pickle
 */

get_header();

if (have_posts()) : while (have_posts()) : the_post();
?>

<div id="main">
  <article class="page">
    <header>
      <h2><?php the_title();?></h2>
    </header>
    <?php the_content('Read more...');?>
  </article>
</div>

<?php
endwhile; endif;

get_footer();

?>
