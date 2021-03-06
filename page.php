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
    <footer>
      <?php if (  $wp_query->max_num_pages > 1 ) : ?>
      <div id="nav-below" class="navigation">
      	<div class="nav-previous"><?php next_post_link('%link', '&larr; %title', TRUE); ?></div>
      	<div class="nav-next"><?php previous_post_link('%link', '%title &rarr;', TRUE); ?></div>
      </div>
      <?php endif; ?>
    </footer>
  </article>
</div>

<?php
endwhile; endif;

get_footer();

?>
