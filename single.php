<!--
TODO
- slideshow on photo single page, use slideshow if multiple images in post (is this actually possible?)
- make all image sizes configurable
- make cropping configurable
-->

<?php get_header(); ?>

<div id="main">

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

<?php query_posts($query_string); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <article class="post single">

    <header class="post-header">
      <h2><?php the_title();?></h2>
    </header>

    <div class="featuredImage">
      <?php the_post_thumbnail('slideshow-slide'); ?>
    </div>

    <?php the_content(); ?>

    <footer class="post-footer">

      <?php if ('open' == $post->comment_status) : ?>
        <div id="comments">
          <?php comments_template(); ?>
        </div>
      <?php endif; ?>

      <div id="nav-below" class="navigation">
      	<div class="nav-previous"><?php next_post_link('%link', '&larr; %title', TRUE); ?></div>
      	<div class="nav-next"><?php previous_post_link('%link', '%title &rarr;', TRUE); ?></div>
      </div>

    </footer>

  </article>

<?php break; endwhile; endif;?>

</section>

</div>

<?php get_footer(); ?>