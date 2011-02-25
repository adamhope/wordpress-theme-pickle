<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-above" class="navigation">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'pickle' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'pickle' ) ); ?></div>
	</div><!-- #nav-above -->
<?php endif; ?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<article id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'pickle' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'pickle' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</article>
<?php endif; ?>

<section class="posts">
<?php while ( have_posts() ) : the_post(); ?>

<?php /* How to display posts in the Gallery category. */ ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

      <header class="post-header">
        <h2>
          <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title();?></a>
        </h2>
      </header>

			<div class="entry-meta">
				<?php pickle_posted_on(); ?>
			</div>

	<?php if ( is_archive() || is_search() ) : // Only display excerpts for archives and search. ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div>
	<?php else : ?>
			<div class="entry-content">
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'pickle' ) ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'pickle' ), 'after' => '</div>' ) ); ?>
			</div>
	<?php endif; ?>

      <footer>

        <?php /* Display navigation to next/previous pages when applicable */ ?>
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
          	<div id="nav-below" class="navigation">
          		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'pickle' ) ); ?></div>
          		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'pickle' ) ); ?></div>
          	</div><!-- #nav-below -->
        <?php endif; ?>

  			<div class="entry-utility">
  				<?php if ( count( get_the_category() ) ) : ?>
  					<span class="cat-links">
  						<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'pickle' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
  					</span>
  					<span class="meta-sep">|</span>
  				<?php endif; ?>
  				<?php
  					$tags_list = get_the_tag_list( '', ', ' );
  					if ( $tags_list ):
  				?>
  					<span class="tag-links">
  						<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'pickle' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
  					</span>
  					<span class="meta-sep">|</span>
  				<?php endif; ?>
  				<?php if ('open' == $post->comment_status) : ?>
  				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'pickle' ), __( '1 Comment', 'pickle' ), __( '% Comments', 'pickle' ) ); ?></span>
  				<span class="meta-sep">|</span>
          <?php endif; ?>
  				<?php edit_post_link( __( 'Edit', 'pickle' ), '<span class="edit-link">', '</span>' ); ?>
  			</div><!-- .entry-utility -->
			</footer>
		</article>

		<?php comments_template( '', true ); ?>

<?php endwhile; // End the loop. Whew. ?>
</section>
