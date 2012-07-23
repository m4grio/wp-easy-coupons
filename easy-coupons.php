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
			'supports'                 => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'sticky')
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
