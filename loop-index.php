<!--

TODO

- slideshow on photo single page, use slideshow if multiple images in post (is this actually possible?)
- show posts not in photos under homepage photo
- make all image sizes configurable
- make cropping configurable

 -->

<div id="main">

  <?php
    if (is_home()) :
    $category = 'photos'; // get_option('wpns_category');
    $n_slices = 5; //get_option('wpns_slices');
  ?>
     <div class="slideshow">
     <?php query_posts( 'cat='.$category.'&posts_per_page=$n_slices' ); if( have_posts() ) : while( have_posts() ) : the_post(); ?>
       <?php if(has_post_thumbnail()) : ?>
         <div class="slide">
           <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> 
             <?php the_post_thumbnail('slideshow-slide'); ?>
           </a>
          </div>
      <?php endif ?>
    <?php endwhile; endif;?>
    <?php wp_reset_query();?>
    </div>
  <?php endif; ?>

  <?php /* If there are no posts to display, such as an empty archive page */ ?>
  <?php if ( ! have_posts() ) : ?>
  	<div id="post-0" class="post error404 not-found">
  		<h1 class="entry-title"><?php _e( 'Not Found', 'pickle' ); ?></h1>
  		<div class="entry-content">
  			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'pickle' ); ?></p>
  			<?php get_search_form(); ?>
  		</div><!-- .entry-content -->
  	</div><!-- #post-0 -->
  <?php endif; ?>

<?
// Begin post loop.
if (have_posts()) : while (have_posts()) : the_post();
?>

  <article>

    <header>
      <h2><?php the_title();?></h2>
    </header>

    <?php the_content(); ?>

    <?php if ('open' == $post->comment_status) : ?>
      <footer>
        <div id="comments">
          <?php comments_template(); ?>
        </div>
      </footer>
    <?php endif; ?>

    <div id="nav-below" class="navigation">
    	<div class="nav-previous"><?php next_post_link('%link', '&larr; %title', TRUE); ?></div>
    	<div class="nav-next"><?php previous_post_link('%link', '%title &rarr;', TRUE); ?></div>
    </div><!-- #nav-below -->

  </article>

<?php break; endwhile; endif;?>

</div>