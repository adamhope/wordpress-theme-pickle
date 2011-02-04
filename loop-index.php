<div id="main">

  <!-- TODO: homepage only -->
   <div id="slider">
   <?php 
     $category = 'photos'; // get_option('wpns_category');
     $n_slices = 5; //get_option('wpns_slices');
   ?>
   <?php query_posts( 'cat='.$category.'&posts_per_page=$n_slices' ); if( have_posts() ) : while( have_posts() ) : the_post(); ?>
     <?php if(has_post_thumbnail()) : ?>
       <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> 
         <?php the_post_thumbnail('medium'); ?>
       </a>
    <?php endif ?>
  <?php endwhile; endif;?>
  <?php wp_reset_query();?>
  </div>

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

<?php if ( in_category( _x('photos', 'photo category slug', 'pickle') ) ) : ?>

  <?php if (is_single() && !is_home_uri()): ?>

     <?php if(has_post_thumbnail()) : ?>
       <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> 
           <?php the_post_thumbnail('single'); ?>
       </a>
    <?php endif ?>

      <a name="info" id="notes"></a>
      <?php the_content(); ?>
      <?php if ('open' == $post->comment_status) : ?>
        <div id="comments">
          <?php comments_template(); ?>
        </div>
      <?php endif; ?>
  <?php endif;?>

<?php else: ?>
  <!-- Single post for other categories -->

  <article>
    <h2><?php the_title();?></h2>
    <?php the_content(); ?>
    <?php if ('open' == $post->comment_status) : ?>
      <div id="comments">
        <?php comments_template(); ?>
      </div>
    <?php endif; ?>
    <div id="nav-below" class="navigation">
    	<div class="nav-previous"><?php next_post_link('%link', '&larr; %title', TRUE); ?></div>
    	<div class="nav-next"><?php previous_post_link('%link', '%title &rarr;', TRUE); ?></div>
    </div><!-- #nav-below -->
  </article>

<?php endif; // This was the if statement that broke the loop into three parts based on categories. ?>

<?php break; endwhile; endif;?>

</div>