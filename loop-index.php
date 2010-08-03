<?php

if (is_home()) {
    $args = array(
      'posts_per_page' => 1,
      'paged' => $paged,
      'category_name' => 'Photos'
    );
    query_posts($args);
}

?>

<div id="content">

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?
// Begin post loop.
if (have_posts()) : while (have_posts()) : the_post();
?>

<?php if ( in_category( _x('photos', 'photo category slug', 'reflection') ) ) : ?>

  <?
  // Grab next/previous post IDs if they exist and are in same category. Odd syntax is hack for PHP4.
  $next_post      = is_object($npobj = get_next_post(true))     ? $npobj->ID : 0;
  $prev_post      = is_object($ppobj = get_previous_post(true)) ? $ppobj->ID : 0;
  $next_post_perm = get_permalink($next_post);
  $prev_post_perm = get_permalink($prev_post);
  ?>

  <?php if (is_home_uri() || is_single()):?>
    <script type="text/javascript">
      Site.nextPostID = <?=$next_post?>;
      Site.prevPostID = <?=$prev_post?>;
    </script>
  <?php endif;?>

  <div id="topcontent" style="width:<?=im_dim()?>px;">
    <div id="title">
      <div id="titlebits">
        <ul>
          <?php if (is_home_uri() || is_single()):?>
            <li>
              <a id="prevPostLink" href="<?=$prev_post ? $prev_post_perm.'">&laquo;' : '">';?></a> |
              <a id="nextPostLink" href="<?=$next_post ? $next_post_perm.'">&raquo;' : '">';?></a>
            </li>
          <?php endif;?>
          <li>
            <a id="comment" href="<?php comments_link();?>"><?php comments_number(__('0 comments',TD),__('1 comment',TD),__('% comments',TD));?></a>
          </li>
          <li>
            <a class="panel" id="exif" href=""><?_e('exif',TD);?></a>
          </li>
          <li>
            <a <?=is_home_uri ? 'class="panel" ' : ''?>id="info" href="<?the_permalink();?>#notes"><?_e('info',TD);?></a>
          </li>
        </ul>
      </div>
      <h3 id="texttitle">
        <a href="<?php the_permalink();?>"><?php the_title();?></a>
        <span id="inlinedate"><?php the_date('jS F Y');?></span>
      </h3>
    </div>
    <div id="imageholder">
      <div id="panel_exif" class="overlay" style="right:0;top:0;">
        <?echo get_exif();?>
      </div>
      <?php if (is_home_uri() || is_single()): // Only enable overlays for homepage navigation. ?>
        <div id="panel_info" class="overlay bottomPanel" style="bottom:0;left:0;right:0;z-index:6">
          <?php the_content(__('Read more...')); ?>
        </div>
        <div id="panel_overlay" class="overlay" style="left:0;top:0;z-index:100">
        </div>
      <?php endif; ?>
      <div id="overlaynav">
        <a href="<?=$prev_post ? $prev_post_perm.'"' : '" style="display:none"'?> id="overPrevLink"></a>
        <a href="<?=$next_post ? $next_post_perm.'"' : '" style="display:none"'?> id="overNextLink"></a>
      </div>
      <img id="mainimage" src="<?=get_thumbnail();?>" alt="image" />
    </div>
  </div>
  <div id="reflectionholder"></div>

  <?php if (is_single() && !is_home_uri()): ?>
    <div id="content">
      <a name="info" id="notes"></a>
      <?php the_content(); ?>
    </div>
    <div id="comments">
      <?php comments_template(); ?>
    </div>
  <?php endif;?>

<?php endif; // This was the if statement that broke the loop into three parts based on categories. ?>

<?php break; endwhile; endif;?>

</div>