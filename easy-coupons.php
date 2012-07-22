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
 * Create post type on WP to the coupons
 *
 * @uses Category
 * @uses Tag
 */
add_action('init', 'create_coupons_type');
function create_coupons_type ()
{
	register_post_type('ecc_coupon',
		array(
			'labels' => array(
				'name' => 'Coupons',
				'singular_name' => 'Coupon'
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'coupon'),
			'show_in_admin_bar' => true,
			'taxonomies' => array('category', 'post_tag'),
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'revisions'
			)
		)
	);
}

add_filter('pre_get_posts', 'include_coupons_in_categories_and_tags');
function include_coupons_in_categories_and_tags ($query)
{
	if (is_category() || is_tag())
	{
		$post_type = get_query_var('post_type');
		
		if ($post_type)
			$post_type = $post_type;
	
		else
			$post_type = array('post','ecc_coupon');
	
		$query->set('post_type',$post_type);
		return $query;
	}
}

/**
 * Add the sidebar areas
 */
register_sidebar(array(
	'name'=> 'Coupons top',
	'id' => 'coupons_top',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2 class="offscreen">',
	'after_title' => '</h2>',
));
register_sidebar(array(
	'name'=> 'Coupons bottom',
	'id' => 'coupons_bottom',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
));