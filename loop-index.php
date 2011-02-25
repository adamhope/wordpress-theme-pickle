<!--
TODO
- slideshow on photo single page, use slideshow if multiple images in post (is this actually possible?)
- make all image sizes configurable
- make cropping configurable
-->

<?php
  $photo_category_id = get_opt_or_default('photo_category_id');
  $n_slices          = get_opt_or_default('slideshow_length');
  $posts_on_homepage = get_opt_or_default('posts_on_homepage');
?>

<div id="main">

<?php if (is_home()) : ?>
  <section id="featured-content">
    <div class="slideshow">
      <?php query_posts('cat=' . $photo_category_id . '&posts_per_page=' . $n_slices); if( have_posts() ) : while( have_posts() ) : the_post(); ?>
      <?php if(has_post_thumbnail()) : ?>
        <div class="slide">
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php the_post_thumbnail('slideshow-slide'); ?>
          </a>
        </div>
      <?php endif ?>
      <?php endwhile; endif;?>
      </div>
  </section>
  <?php wp_reset_query();?>
<?php endif; ?>

<section id="content">

  <?php /* If there are no posts to display, such as an empty archive page */ ?>
  <?php if ( ! have_posts() ) : ?>
  	<div id="post-0" class="post error404 not-found">
  		<h1 class="entry-title"><?php _e( 'Not Found', 'pickle' ); ?></h1>
  		<div class="entry-content">
  			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'pickle' ); ?></p>
  			<?php get_search_form(); ?>
  		</div>
  	</div>
  <?php endif; ?>

<?php query_posts($query_string . '&cat=-' . photo_category_id); ?>
<?php if ( have_posts() && $posts_on_homepage == 1) : while ( have_posts() ) : the_post(); ?>

  <article class="post">

    <header class="post-header">
      <h2>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title();?></a>
      </h2>
    </header>

    <?php the_content(); ?>

    <footer class="post-footer">

      <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div id="nav-below" class="navigation">
        	<div class="nav-previous"><?php next_post_link('%link', '&larr; %title', TRUE); ?></div>
        	<div class="nav-next"><?php previous_post_link('%link', '%title &rarr;', TRUE); ?></div>
        </div>
      <?php endif; ?>

      <?php if ('open' == $post->comment_status) : ?>
        <div id="comments">
          <?php comments_template(); ?>
        </div>
      <?php endif; ?>

    </footer>

  </article>

<?php break; endwhile; endif;?>

</section>

</div>