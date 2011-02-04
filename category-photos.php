<?php
/*
 * Template Name: Category Photos
 * Description: Allows users to browse through your archived posts by tag and date.
 */

// Get header.
get_header();

// Determine what kind of archive we have.
$post     = $posts[0];
$thisyear = is_year()     ? get_the_time('Y')           : false;
$thistag  = is_tag()      ? single_tag_title('', false) : false;
$thiscat  = is_category() ? single_cat_title('', false) : false;
$all      = !$thisyear && !$thistag;
$curtag   = get_query_var('tag_id');
$curcat   = get_query_var('cat_id');
$curstr   = ' class="current"';
$postinfo = array();
$disptype = get_opt_or_default('archivedisp');

// Attempt to find permalink to correct page ID
$allid    = $wpdb->get_var("SELECT p.ID FROM $wpdb->posts AS p, $wpdb->postmeta AS m
                            WHERE p.ID = m.post_id AND p.post_status='publish' AND m.meta_key='_wp_page_template' AND m.meta_value='archive.php'");
$alluri   = '';
if ($allid) {
  $alluri = get_permalink($allid);
}

?>

<div id="main">
  <table cellpadding="5" cellspacing="0" id="taxonomyTable">
    <?php if ($disptype): ?>
    <tr>
      <th>Browse by year</th><th style="text-align:right">Browse <?=$disptype == 1 ? 'by tag' : 'by category'?></th>
    </tr>
    <?php else: ?>
    <tr>
      <th>By year</th>
    </tr>
    <?php endif; ?>
    <tr>
      <td id="yearCloud">
          <?php
              $post_years = $wpdb->get_results("SELECT YEAR(post_date) AS posty FROM " . $wpdb->prefix . "posts 
                                          WHERE post_status='publish' AND post_type='post' GROUP BY posty ORDER BY posty DESC;");
              echo '<a href="'.$alluri.'"'.($all ? $curstr : '').'>All</a> ';
        foreach ($post_years as $y)
          echo '<a href="' . get_year_link($y->posty) . '"' . ($thisyear && $thisyear == $y->posty ? $curstr : '') . '>' . $y->posty . '</a> ';
      ?>
        </td>
      <?php if ($disptype == 1): ?>
        <td id="tagCloud">
          <?php 
            if ($thistag !== false) {
              echo implode(' ', str_replace('tag-link-' . $curtag, 'tag-link-' . $curtag .' current', wp_tag_cloud('format=array')));
            } else {
              wp_tag_cloud('');
            }
          ?>
        </td>
      <?php elseif ($disptype == 2): ?>
        <td id="catCloud">
          <?php wp_list_categories(); ?>
        </td>
      <?php endif;?>
      </tr>
    </table>
    
    <div>
      <div id="tag-pics-wrapper">
        <div id="tag-pics" class="clearf">

           <?php 
             $category = 'photos'; // get_option('wpns_category');
             $n_slices = 5; //get_option('wpns_slices');
           ?>

           <?php query_posts( 'cat='.$category.'&posts_per_page=$n_slices' ); if( have_posts() ) : while( have_posts() ) : the_post(); ?>
             <?php if(has_post_thumbnail()) : ?>
               <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> 
                   <?php the_post_thumbnail('mosaic-thumb', array('class' => 'mosaic')); ?>
               </a>
            <?php endif ?>
          <?php endwhile; endif;?>

      </div>
      <img src="<?php bloginfo('template_directory'); ?>/images/browse-load.gif" id="tagProgress" alt="loading" />
    </div>
  </div>
  <?php if (get_opt_or_default('mosaictips')): ?>
    <!-- TODO remove -->
  <?php endif; ?>
</div>

<?
  get_footer();
?>
