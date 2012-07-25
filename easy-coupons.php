<?php
/**
 * Package WordPRess
 * @subpackage Skint_Coupons
 * @version 0.6
 */
/*
Plugin Name: Skint Coupons
Plugin URI: http://creativeworkers.mx/
Description: This is sparta!
Author: Mario Alva
Version: 0.6
License: WTFPL :p
*/


/**
 * Protection 
 * 
 * This string of code will prevent hacks from accessing the file directly.
 */
defined('ABSPATH') or die("Cannot access pages directly.");


/**
 * Required files
 */
// require 'widgets/categories.widget.php';


/**
 * Create brand new post-type and taxonomy on WP to the coupons
 *
 * @uses Taxonomy
 */
add_action('init', 'create_coupons_type');
function create_coupons_type ()
{

	register_post_type('ecc_coupon',
		array(
			'labels'                   => array(
				'name'                 => 'Coupons',
				'singular_name'        => 'Coupon',
				'add_new_item'         => 'Add New Coupon',
				'edit_item'            => 'Edit Coupon',
			),
			'public'                   => true,
			'show_ui'                  => true,
			'capability_type'          => 'post',
			'has_archive'              => true,
			'rewrite'                  => array('slug' => 'coupon', 'with_front' => false),
			'show_in_admin_bar'        => true,
			'taxonomies'               => array('coupons'),
			'supports'                 => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'sticky', 'custom-fields'),
			'menu_icon'                => plugins_url('assets/img/plugin_menu_ico.png', __FILE__)
		)
	);

	$labels = array(
		'name'                         => 'Categories',
		'singular_name'                => 'Category',
		'search_items'                 => 'Search Categories',
		'popular_items'                => 'Popular Categories',
		'all_items'                    => 'All Categories',
		'parent_item'                  => 'Parent Category',
		'edit_item'                    => 'Edit Category',
		'update_item'                  => 'Update Category',
		'add_new_item'                 => 'Add New Category',
		'new_item_name'                => 'New Category',
		'separate_items_with_commas'   => 'Separate Categories with commas',
		'add_or_remove_items'          => 'Add or remove Categories',
		'choose_from_most_used'        => 'Choose from most used Categories'
		);

	$args = array(
		'label'                        => 'Categories',
		'labels'                       => $labels,
		'public'                       => true,
		'hierarchical'                 => true,
		'show_ui'                      => true,
		'show_in_nav_menus'            => true,
		'args'                         => array('orderby' => 'term_order'),
		'rewrite'                      => array('slug' => 'coupons', 'with_front' => false),
		'query_var'                    => true
	);

	register_taxonomy('coupons', 'ecc_coupon', $args);
}


/**
 * Styles and scripts
 */
add_action('wp', 'ecc_plugin');
function ecc_plugin ()
{

	/**
	 * Admin styles and scripts
	 */
	if (is_admin())
	{
		global $parent_file;

		if (isset($_GET['post_type']) && $_GET['post_type'] == 'ecc_coupon' && preg_match('/ecc_coupon/', $parent_file))
		{
			wp_enqueue_script('peity', plugins_url('assets/js/jquery.peity.min.js', __FILE__), array('jquery'));
			wp_enqueue_script('adminjs', plugins_url('assets/js/admin.js', __FILE__), array('jquery'));
			wp_enqueue_style('admincss', plugins_url('assets/css/admin.css', __FILE__));
		}
	}


	/**
	 * Public scripts and styles
	 */
	else
	{

		if (is_page('supercalifragilisticexpialidocious'))
		{

			/**
			 * AJAX
			 */
			if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
			{

				$key = "ecc_rating-$_POST[action]_$_POST[ID]";

				$current_value = get_post_meta($_POST['ID'], $key, true);

				if ( ! $current_value)
					$current_value = 0;

				update_post_meta($_POST['ID'], $key, ++$current_value);
				die;
			}


			wp_enqueue_style('ecc_styles', plugins_url('assets/css/styles.css', __FILE__));

			wp_enqueue_script('ecc_rating', plugins_url('assets/js/rate.js', __FILE__));
			wp_enqueue_script('ecc_pajinate', plugins_url('assets/js/jquery.pajinate.min.js', __FILE__));
		}
	}
}


/**
 * Add the sidebar areas
 */
// register_sidebar(array(
// 	'name'                             => 'Coupons top',
// 	'id'                               => 'coupons_top',
// 	'before_widget'                    => '<div id="%1$s" class="widget %2$s">',
// 	'after_widget'                     => '</div>',
// 	'before_title'                     => '<h3 class="widget-title">',
// 	'after_title'                      => '</h3>',
// ));
// register_sidebar(array(
// 	'name'                             => 'Coupons bottom',
// 	'id'                               => 'coupons_bottom',
// 	'before_widget'                    => '<div id="%1$s" class="widget %2$s">',
// 	'after_widget'                     => '</div>',
// 	'before_title'                     => '<h3 class="widget-title">',
// 	'after_title'                      => '</h3>',
// ));


// /**
//  * Register the widgets
//  */
// add_action('widgets_init', 'ecc_register_widgets');
// function ecc_register_widgets ()
// {
// 	register_widget('Categories_Widget');
// }


/**
 * The coupons
 *
 * @var mixed $category
 */
function the_coupons ($category=false)
{

	global $wpdb;

	$ignored = array();

	/**
	 * Pull options!
	 */
	$featured_limit = (int) get_option('ecc_featured-coupons_') ? get_option('ecc_featured-coupons_') : 1;
	$coupons_per_page = (int) get_option('ecc_coupons-per-page_') ? get_option('ecc_coupons-per-page_') : 10;

	/**
	 * Try to pull featured coupons
	 * It can belong to any category, so, the query it's the same for any case
	 */
	$featured = $wpdb->get_results("
		
		SELECT SQL_CALC_FOUND_ROWS
			$wpdb->posts.*

		FROM $wpdb->posts

			INNER JOIN $wpdb->postmeta ON
				($wpdb->posts.ID = $wpdb->postmeta.post_id)
		
			INNER JOIN $wpdb->postmeta AS mt1 ON
				($wpdb->posts.ID = mt1.post_id)

		WHERE
			1=1
			AND $wpdb->posts.post_type = 'ecc_coupon'
			AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private')
			AND (($wpdb->postmeta.meta_key = '_featured_coupon_d' AND CAST($wpdb->postmeta.meta_value AS CHAR) = '1')
			AND (mt1.meta_key = '_expiration_d' AND '" . time() . "' <= CAST(mt1.meta_value AS SIGNED)))

		GROUP BY $wpdb->posts.ID
		ORDER BY $wpdb->posts.post_date DESC
		LIMIT 0, $featured_limit

	", OBJECT);

	// echo '<pre>';
	// print_r($featured);
	// die;

	/**
	 * If we have featured posts :O
	 */
	if ( ! empty($featured))
	{
		/**
		 * Push to ignored array to avoid duplicate entries
		 */
		foreach ($featured as $_post)
			$ignored[] = $_post->ID;


		/**
		 * Render it
		 */
		?>
		<div id="featured-coupons-wrapper">
			<?php include 'views/featured-coupon.php' ?>

			<?php

				/**
				 * Pull the banner data
				 */
				$banner = get_option('ecc_banner');
				$banner_link = get_option('ecc_banner_link');

			if ($banner): ?>

			<div id="coupons-banner-wrapper">
				<p>
					
					<?php if ($banner_link): ?>
					<a href="<?php echo $banner_link ?>">
						<?php echo $banner ?>
					</a>
					<?php else: ?>
						<?php echo $banner ?>
					<?php endif ?>
				</p>
			</div>

			<?php endif ?>

		</div>
		<?php
	}


	/**
	 * Now we pull the rest of the coupons
	 */
	wp_reset_postdata();
	$coupons = new WP_Query(array(
		'post_type' => 'ecc_coupon',
		'post__not_in' => $ignored
	));

	ob_start();
		include 'views/coupon.php';
		$content = ob_get_contents();
	ob_end_clean();
	
	?>
	<div id="coupons-wrapper">
		<?php print_r($content) ?>
	</div>
	<?php
}


/**
 * Custom columns to the list
 *
 * @uses Feedback
 * @uses Featured
 * @uses Expiration date
 */
add_filter('manage_edit-ecc_coupon_columns', 'ecc_edit_columns');
add_action('manage_ecc_coupon_posts_custom_column', 'ecc_manage_columns', 10, 2);
function ecc_edit_columns ($columns)
{

	$new = array();

	foreach ($columns as $k => $title)
	{
		/**
		 * Add the feedback column just before the date one
		 */
		if ($k == 'date')
		{
			$new['featured'] = 'Featured';
			$new['feedback'] = 'Feedback';
			$new['expiration'] = 'Expiration Date';
		}

		$new[$k] = $title;
	}

	return $new;
}
function ecc_manage_columns ($column, $post_id)
{
	global $post;

	switch ($column)
	{

		case 'featured':
			if ($featured = get_post_meta($post_id, '_featured_coupon_d', true)):
			?>
				<p><img src="<?php echo plugins_url('assets/img/ico_featured_coupon-admin.png', __FILE__) ?>"></p>
			<?php
			endif;
			break;

		case 'feedback':

			$yes = get_post_meta($post_id, "ecc_rating-yes_$post_id", true);
			$no = get_post_meta($post_id, "ecc_rating-no_$post_id", true);
			?>
			<div class="rating">
				<?php if ($yes or $no): ?>
					<p>Yes <span class="chart"><?php echo $yes ? $yes : '0' ?>,<?php echo $no ? $no : '0' ?></span> No</p>
			<?php endif ?>
			</div>
			<?php
			break;

		case 'expiration':

			$expiration = get_post_meta($post_id, '_expiration_d', true);

			if ($expiration):
				?><p><?php echo date('d/m/Y', $expiration); ?></p><?php
			endif;
			break;
	}
}


/**
 * Add expiration and featuring to coupons
 */
add_action('post_submitbox_misc_actions', 'add_expiration_date');
function add_expiration_date ()
{
	global $post;
	if (get_post_type($post) == 'ecc_coupon')
	{


		if ($expiration = get_post_meta($post->ID, '_expiration_d', true))
			$expiration = date('d/m/Y', $expiration);

		$featured = get_post_meta($post->ID, '_featured_coupon_d', true);

		?>
		<div class="misc-pub-section adsfas">
			<label class="selectit">
				<input type="checkbox" name="_featured_coupon_d" <?php echo $featured ? 'checked="checked"' : ''?>>
				Featured <small>(sticky)</small>
			</label>
		</div>

		<div class="misc-pub-section curtime misc-pub-section-last">
			<span id="timestamp">
				Expires on: <strong><?php echo $expiration ? $expiration : 'unset' ?></strong>
				<a href="#edit_expiration" class="edit-expiration hide-if-no-js">Edit</a>
			</span>
			<div id="expirationdiv" class="hide-if-js">
				<input type="text" maxlength="10" value="<?php echo $expiration ? $expiration : '' ?>" name="_expiration_d">
				<small>dd/mm/yyyy</small>
				<p>
					<a href="#edit_expiration" class="save-expiration hide-if-no-js button">OK</a>
					<a href="#edit_expiration" class="cancel-expiration hide-if-no-js">Cancel</a>
				</p>
			</div>
		</div>
		<script type="text/javascript">
		jQuery(function($) {
			$('a.edit-expiration').click(function(e) {
				e.preventDefault();
				$(this).hide();
				$('div#expirationdiv').slideDown(200);
			});

			$('div#expirationdiv a.cancel-expiration').click(function() {
				$('a.edit-expiration').show();
				$(this).parents('div#expirationdiv').slideUp(200);
			});
		});
		</script>
		<?php
	}
}

/**
 * Save expiration date
 */
add_action('save_post', 'save_expiration_date');
function save_expiration_date ()
{
	global $post;
	
	if (get_post_type($post) == 'ecc_coupon')
	{

		// echo '</pre>';
		// print_r($_POST);
		// die;
		
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		 
		if ( ! isset($_POST['_expiration_d']))
			return $post_id;
		
		$time = strtotime($_POST['_expiration_d']);

		list($d, $m, $y) = explode('/', $_POST['_expiration_d']);
		update_post_meta($post->ID, '_expiration_d', mktime(0, 0, 0, $m, $d, $y));

		update_post_meta($post->ID, '_featured_coupon_d', $_POST['_featured_coupon_d'] == 'on' ? '1' : '0');
	}
}


/**
 * Admin menu
 */
function ecc_admin_menu ()
{
	add_submenu_page('edit.php?post_type=ecc_coupon', 'Options', 'Options', 'activate_plugins', 'ecc_options', 'ecc_options');
} add_action('admin_menu', 'ecc_admin_menu');

function ecc_options ()
{

	if (isset($_POST["ecc_options_is_saving"]))
	{
		update_option('ecc_featured-coupons_', esc_attr($_POST['ecc_featured-coupons_']));
		update_option('ecc_coupons-per-page_', esc_attr($_POST['ecc_coupons-per-page_']));
		update_option('ecc_banner', $_POST['ecc_banner']);
		update_option('ecc_banner_link', $_POST['ecc_banner_link']);
	}

	?>
	<div class="wrap">

		<div id="icon-options-general" class="icon32"><br></div>
		<h2>Coupons Options</h2>

		<form name="form1" method="post" action="">

			<input type="hidden" name="ecc_options_is_saving" value="1">
			<table class="form-table">
				<tbody>

					<tr valign="top">
						<th scope="row">
							<label for="ecc_featured-coupons_">Featured coupons to be displayed</label>
						</th>
						<td>
							<input name="ecc_featured-coupons_" type="number" step="1" min="1" id="ecc_featured-coupons_" value="<?php echo get_option('ecc_featured-coupons_') ?>" class="small-text">
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for="ecc_coupons-per-page_">Coupons per page</label>
						</th>
						<td>
							<input name="ecc_coupons-per-page_" type="number" step="1" min="1" id="ecc_coupons-per-page_" value="<?php echo get_option('ecc_coupons-per-page_') ?>" class="small-text">
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for="ecc_banner">Banner text <br><small>wrap with &lt;strong&gt;<span style="color:#f51464;">for pink text</span>&lt;/strong&gt;</small></label>
						</th>
						<td>
							<input name="ecc_banner" type="text" id="ecc_banner" value="<?php echo esc_attr(get_option('ecc_banner')) ?>" class="regular-text code">
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for="ecc_banner_link">Banner link</label>
						</th>
						<td>
							<input name="ecc_banner_link" type="text" id="ecc_banner_link" value="<?php echo esc_attr(get_option('ecc_banner_link')) ?>" class="regular-text code">
						</td>
					</tr>

				</tbody>
			</table>

			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
			</p>

		</form>
		<div class="clear"></div>
	</div>
	<?php
}


/**
 * Protect feedback custom fields
 */
add_filter('is_protected_meta', 'ecc_protect_meta', 10, 2);
function ecc_protect_meta ($protected, $meta_key)
{
	return preg_match('/^ecc_rating/', $meta_key) ? true : $protected;
}