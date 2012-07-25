<?php
/**
 * Package WordPRess
 * @subpackage Easy_Coupons
 * @version 0.1
 */
/*
Plugin Name: Easy Coupons
Plugin URI: http://creativeworkers.mx/
Description: This is sparta!
Author: Mario Alva
Version: 0.1
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
require 'widgets/categories.widget.php';


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
			'supports'                 => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'sticky'),
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

		if (is_page('deals'))
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


			wp_enqueue_style('', plugins_url('assets/css/styles.css', __FILE__));
			wp_enqueue_script('', plugins_url('assets/js/rate.js', __FILE__));
		}

		?>
		<script type='text/javascript' src='http://www.skintnation.com/wp-includes/js/jquery/jquery.js?ver=1.7.2'></script>

		<link rel="stylesheet" href="http://www.skintnation.com/wp-content/themes/Avenue/style.css" type="text/css" />
		<link rel="stylesheet" href="http://www.skintnation.com/wp-content/themes/Avenue/js/colorbox/colorbox.css" type="text/css" />
		<link rel="stylesheet" href="http://www.skintnation.com/wp-content/themes/Avenue/css/style_slide.css" type="text/css"> <!-- the main structure and main page elements style --> 
		<link rel="stylesheet" href="http://www.skintnation.com/wp-content/themes/Avenue/css/js_slide.css" type="text/css" media="screen"> <!-- styles for the various jquery plugins -->
		<link rel='stylesheet' id='usernoise-button-css'  href='http://www.skintnation.com/wp-content/plugins/usernoise/css/button.css?ver=3.4.1' type='text/css' media='all' />
		<link rel='stylesheet' id='boxes-css'  href='http://www.skintnation.com/wp-content/plugins/wordpress-seo/css/adminbar.css?ver=3.4.1' type='text/css' media='all' />
		<link rel='stylesheet' id='avhec-widget-css'  href='http://www.skintnation.com/wp-content/plugins/extended-categories-widget/3.3/css/avh-ec.widget.css?ver=3.6.4' type='text/css' media='all' />
		<style type="text/css" media="screen">
			html { margin-top: 28px !important; }
			* html body { margin-top: 28px !important; }
		</style>
		<style type="text/css" media="all">
		/* <![CDATA[ */
		@import url("http://www.skintnation.com/wp-content/plugins/wp-table-reloaded/css/plugin.css?ver=1.9.3");
		@import url("http://www.skintnation.com/wp-content/plugins/wp-table-reloaded/css/datatables.css?ver=1.9.3");
		/* ]]> */
		</style>
		<style type='text/css'>

		body { 

		background:#F6F6F6 url('http://www.skintnation.com/wp-content/themes/Avenue/images/patterns/pattern4.png');

		background-repeat:repeat;

		background-attachment:fixed;

		overflow:scroll;





		 }

		.arrows { color:#e821c0; }

		.block-arrows, .block-arrows a { color:#e821c0; }

		.meta-arrow { font-size:16px; color:#e821c0; }

		.tweets a, .textwidget a { color:#e821c0; }

		</style>
		<style type="text/css" media="screen">
		#vslider_optionscontainer {
			margin: 0px; ?>;
			float:none;
			}
		#vslider_options { 
			width: 728px; 
			height: 90px;
			overflow: hidden; 
			position: relative; 
			}
			

			#vslider_options a, #vslider_options a img {
				border: none !important; 
				text-decoration: none !important; 
				outline: none !important;
				} 
				
			#vslider_options h4 {
				color: #FFFFFF !important;
				margin: 0px !important;padding: 0px !important;
				font-family: Arial, Helvetica, sans-serif !important;
				font-size: 16px !important;}
				
			#vslider_options .cs-title {
				background: #ffffff;
				color: #FFFFFF  !important;
				font-family: Arial, Helvetica, sans-serif !important;
				font-size: 12px !important;
				letter-spacing: normal !important;line-height: normal !important;}
				
			#vslider_options .cs-title{ position:absolute;
			width: 718px; padding: 10px;        }
			#cs-buttons-vslider_options { display: none; }    #vslider_optionscontainer .cs-buttons {clear:both; font-size: 0px; margin: 0px 0 10px 100px; float: left; }
			   #cs-button-vslider_options{ z-index:999;outline:none;}
						#vslider_optionscontainer .cs-buttons { font-size: 0px; padding: 10px; float: left; outline: none !important;}
				   #vslider_optionscontainer .cs-buttons a { margin-left: 5px; height: 15px; width: 15px; float: left; 
									background: url('http://www.skintnation.com/wp-content/plugins/vslider/images/default_style.png') no-repeat;background-position:top;
														text-indent: -1000px;
														outline: none !important;
									 }
					 #vslider_optionscontainer .cs-buttons a:hover  { background: url('http://www.skintnation.com/wp-content/plugins/vslider/images/default_style.png') no-repeat;background-position: bottom;top:15px;outline: none !important;}
					#vslider_optionscontainer  a.cs-active { background: url('http://www.skintnation.com/wp-content/plugins/vslider/images/default_style.png') no-repeat;background-position:bottom;outline: none !important;}          
										
				
						 #vslider_options  .cs-prev,#vslider_options  .cs-next { outline:none; }
				   #vslider_options  .cs-prev {margin-left:8px; line-height: 50px;width: 50px;height:50px; background: url('http://www.skintnation.com/wp-content/plugins/vslider/images/nav_style1_arrows-prev.png')no-repeat; text-indent: -999px;}
			  #vslider_options  .cs-next {margin-right: 5px; line-height: 50px;width: 50px;height:50px; background: url('http://www.skintnation.com/wp-content/plugins/vslider/images/nav_style1_arrows-next.png')no-repeat; text-indent: -999px;}
					 
			   #vslider_options,#vslider_options img {
				border:0px solid #FFFFFF; 
				border-radius:0px;
				-moz-border-radius:0px;
				-webkit-border-radius:0px;
				}
		</style>
		<link rel="stylesheet" media="screen" type="text/css" href="http://www.skintnation.com/wp-content/plugins/email-newsletter/widget/widget.css" />

		<?php
	}
}


/**
 * Add the sidebar areas
 */
register_sidebar(array(
	'name'                             => 'Coupons top',
	'id'                               => 'coupons_top',
	'before_widget'                    => '<div id="%1$s" class="widget %2$s">',
	'after_widget'                     => '</div>',
	'before_title'                     => '<h3 class="widget-title">',
	'after_title'                      => '</h3>',
));
register_sidebar(array(
	'name'                             => 'Coupons bottom',
	'id'                               => 'coupons_bottom',
	'before_widget'                    => '<div id="%1$s" class="widget %2$s">',
	'after_widget'                     => '</div>',
	'before_title'                     => '<h3 class="widget-title">',
	'after_title'                      => '</h3>',
));


/**
 * Register the widgets
 */
add_action('widgets_init', 'ecc_register_widgets');
function ecc_register_widgets ()
{
	register_widget('Categories_Widget');
}


/**
 * The coupons
 *
 * @var mixed $category
 */
function the_coupons ($category=false)
{


	$coupons = new WP_Query(array(
		'post_type' => 'ecc_coupon'
	));

	ob_start();
		include 'views/coupon.php';
		$content = ob_get_contents();
	ob_end_clean();

	
	?>
	<div id="coupons-wrapper">
		<?php echo $content ?>
	</div>
	<?php
}


/**
 * Column to feedback
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
 * Add expiration to coupons
 */
add_action('post_submitbox_misc_actions', 'add_expiration_date');
function add_expiration_date ()
{
	global $post;
	if (get_post_type($post) == 'ecc_coupon')
	{


		if ($expiration = get_post_meta($post->ID, '_expiration_d', true))
			$expiry = date('d/m/Y', $expiration);

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

		update_post_meta($post->ID, '_featured_coupon_d', $_POST['_featured_coupon_d'] == 'on' ? 1 : 0);
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