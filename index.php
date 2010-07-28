<?php

  // Code for grabbing a random post from the database if necessary; we then redirect to the page.
  // if ($_GET['do'] == 'random') {
  //  $random_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'post' AND post_password = '' AND post_status = 'publish' ORDER BY RAND() LIMIT 1");
  //  wp_redirect(get_permalink($random_id));
  // }

  // Grab header.
  get_header();

  get_template_part( 'loop', 'index' );

  get_footer();

?>
