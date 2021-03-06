<?php

/**
 * Functions which are common to all (or most) files in the theme. We
 * also define things which make it much easier to create administration
 * pages and deal with options.
 *
 * @package pickle
 */

/**
 * Prefix to use in option names. Should _not_ be changed.
 *
 * @global string $pfix
 */
$pfix = "pickle_";

/**
 * Version number for use in the footer.
 *
 * @global string $vnum
 */
$vnum = "1.1";

/**
 * Array of options we use in pickle.
 *
 * This is an array containing all the names and default values that we use for
 * pickle. Each element is also an array with three possible keys
 *
 * - type: Can be text, hidden, int or check
 * - size: For int/text types, size of textfield.
 * - value: For check types, either yes or no.
 * - default: default value.
 *
 * @global array $options
 */
$options = array(
  'photo_category_id'  => array(
  	'type'    => 'text',
  	'size'    => '30',
  	'default' =>  1
  ),
  'slideshow_length' => array(
  	'type'    => 'text',
  	'size'    => '30',
  	'default' => '5'
  ),
  'posts_on_homepage' => array(
  	'type'    => 'check',
    'default' => 1
  ),
	'mosaicsize' => array(
		'type'    => 'text',
		'size'    => '10',
		'default' => 100
	),
	'mosaictips' => array(
		'type'    => 'check',
		'default' => 1
	),
	'mosaicdesc' => array(
		'type'    => 'radio',
		'default' => 1,
		'values'  => array(
			'0' => 'Ascending',
			'1' => 'Descending'
		)
	),
	'archivedisp' => array(
		'type'    => 'radio',
		'default' => 0,
		'values'  => array(
			'0' => 'None',
			'1' => 'Tags'
		)
	),
	'submitted'  => array(
		'type'    => 'hidden',
		'value'   => 'yes'
	)
);

/**
 * Array of option values. Each element of the array is the option
 * value.
 *
 * @global array $values
 */
$values = array();

/**
 * On administration pages, this is set to true after we have updated
 * the options.
 *
 * @global bool $updateflag
 */
$updateflag = false;

/**
 * For some reason, PHP doesn't include the json_encode function
 * before PHP5.2, so this is a replacement for compatibility reasons.
 * Taken from php.net.
 */
if (!function_exists('json_encode')) {
	function json_encode($a=false) {
		if (is_null($a)) return 'null';
		if ($a === false) return 'false';
		if ($a === true) return 'true';
		if (is_scalar($a)) {
			if (is_float($a)) {
				// Always use "." for floats.
				return floatval(str_replace(",", ".", strval($a)));
			}

			if (is_string($a)) {
				static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
			} else return $a;
		}
		$isList = true;
		for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
			if (key($a) !== $i) {
				$isList = false;
				break;
			}
		}
		$result = array();
		if ($isList) {
			foreach ($a as $v) $result[] = json_encode($v);
			return '[' . join(',', $result) . ']';
		} else {
			foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
			return '{' . join(',', $result) . '}';
		}
	}
}

/**
 * Get an option or fall-back on default value.
 *
 * Since WordPress doesn't seem to have a registration hook for
 * themes (akin to plugins), we use this function to avoid epic
 * failure when looking up options.
 *
 * @param string $optname Option name
 * @return mixed Option value from database or default
 */
function get_opt_or_default($optname) {
	global $pfix, $options;
	$opt = get_option($pfix.$optname);
	return $opt === false ? $options[$optname]['default'] : $opt;
}

/**
 * Filter function to create the option page for pickle.
 */
function pickle_add_pages() {
	add_theme_page('Pickle Options', 'Pickle', 'edit_themes', basename(__FILE__), 'pickle_admin');
}

/**
 * Prints a data field defined in $options.
 *
 * @param string $name Field name
 */
function field_print($name) {
	global $options, $values, $pfix;

	if (!is_array($options[$name]))
		return;

	$value = $values[$name];
	$fname = $pfix.$name;

	switch ($options[$name]['type']) {
		case 'text':
			echo '<input type="text" name="'.$fname.'" value="'.$value.'" size="'.$options[$name]['size'].'">';
			break;
		case 'hidden':
			echo '<input type="hidden" name="'.$fname.'" value="'.$options[$name]['value'].'">';
			break;
		case 'check':
			echo '<input type="checkbox" name="'.$fname.'" value="1"'.($value ? ' checked="checked"' : '').'>';
			break;
		case 'radio':
			foreach ($options[$name]['values'] as $k => $v)
				echo '<input type="radio" name="'.$fname.'" value="'.$k.'"'.($value==$k ? ' checked="checked"' : '').'> '.$v.'<br />';
			break;
		default:
			break;
	}
}

/**
 * Sets up the administration page itself.
 */
function pickle_admin() {
	global $updateflag;

	echo '<div class="wrap">';
	echo '<h2>'.__('Pickle Options').'</h2>';
	if ($updateflag) { ?><div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div><? }

	?>
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<?php field_print('submitted');?>

	<h3>Homepage options</h3>
	<table class="form-table">
		<tr>
			<th scope="row" valign="top">Photo Category</th>
			<td>
				<?php field_print('photo_category_id');?><br />
				<span class="setting-description">Name of category containing photos.</span>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Slideshow length</th>
			<td>
				<?php field_print('slideshow_length');?><br />
				<span class="setting-description">Maximum number of photos to display in slideshow.</span>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Show posts on homepage</th>
			<td>
				<?php field_print('posts_on_homepage');?>
				<span class="setting-description">Enable or disable posts under slideshow on the homepage.</span>
			</td>
		</tr>
	</table>

	<h3>Mosaic configuration</h3>
	<table class="form-table">
		<tr>
			<th scope="row" valign="top">Taxonomy display</th>
			<td>
				<?php field_print('archivedisp');?>
				<span class="setting-description">If you want to display photos by tag name as well as by date, then select the 'tags' option.</span>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Mosaic image size</th>
			<td>
				<?php field_print('mosaicsize');?> px<br />
				<span class="setting-description">Size of the square images shown in the mosaic.</span>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Show mosaic tooltips</th>
			<td>
				<?php field_print('mosaictips');?>
				<span class="setting-description">If enabled, tooltips will be shown as you hover over each image with the name, post date and number of comments.</span>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">Post order</th>
			<td>
				<?php field_print('mosaicdesc');?>
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ); ?>" />
	</p>
</form>
	<?
}

// --------------------------------------------------------------------
// End of functions
// --------------------------------------------------------------------

// If we're in the administration panel, then set up all of our options
// and put the values in $values.

if (is_admin()) {
	if ($_POST[$pfix.'submitted'] == 'yes') {
		foreach (array_keys($options) as $opt) {
			$val = $_POST[$pfix.$opt];
			if ($options[$opt]['type'] == 'hidden')
				continue;
			elseif ($options[$opt]['type'] == 'check')
				$val = $val ? 1 : 0;
			update_option($pfix.$opt, $val);
			$values[$opt] = $val;
		}

		$updateflag = true;
	} else {
		foreach (array_keys($options) as $opt) {
			if ($options[$opt]['type'] == 'hidden')
				continue;
			$values[$opt] = get_option($pfix.$opt);

			// Set up default options
			if ($values[$opt] === false) {
				$values[$opt] = $options[$opt]['default'];
				add_option($pfix.$opt, $values[$opt]);
			}
		}
	}
	add_action('admin_menu', 'pickle_add_pages');
}

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'pickle' ),
) );

function pickle_widgets_init() {

	// Area 1, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'pickle' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'pickle' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'pickle' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'pickle' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'pickle' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'pickle' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}

/** Register sidebars by running pickle_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'pickle_widgets_init' );

/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function pickle_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'pickle' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date">%3$s</time></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'pickle' ), get_the_author() ),
			get_the_author()
		)
	);
}

if ( function_exists( 'add_theme_support' ) ) {

  add_theme_support( 'post-thumbnails' );

  set_post_thumbnail_size( 150, 150, true ); // default Post Thumbnail dimensions (cropped)

  // Custom image sizes
  // NOTE: seem need to re-upload post image for change to take effect?
  // NOTE: last param is crop
  add_image_size( 'mosaic-thumb', 280, 280, true );
  add_image_size( 'single', 480, 999 );
  add_image_size( 'slideshow-slide', 999, 480 );
  add_image_size( 'featuredImage', 999, 480 );

}

?>