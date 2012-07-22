<?php
/**
 * @package Easy_Coupons
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
 * The DB table
 */
define ('ECC_TABLENAME', 'ecc_coupons');

/**
 * Install
 */
register_activation_hook(__FILE__, 'ecc_install');
function ecc_install ()
{

	global $wpdb;
	global $tws_db_version;


	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


	/**
	 * Metadata of table
	 */
	$tws_db_version = 1;
	$table_name = $wpdb->prefix . ECC_TABLENAME;


	/**
	 * Get current db version
	 */
	$current_db = (float) get_option('_ecc_db_version');
	var_dump($current_db);
	die($current_db);


	/**
	 * If installed is not the actual version lets check for upgrades
	 */
	if ($current_db <= $tws_db_version)
	{
		// Here will be the upgrades
	}


	/**
	 * The dable doesn't exists, lets create it
	 */
	if ( ! (bool) $current_db)
	{
		
		dbDelta("CREATE TABLE " . $table_name . " (
			`id_coupon` varchar(25) collate utf8_unicode_ci NOT NULL,
			`data` text collate utf8_unicode_ci NOT NULL,
			`text` varchar(140) collate utf8_unicode_ci NOT NULL,
			`search_query` text collate utf8_unicode_ci,
			`from_user` varchar(29) collate utf8_unicode_ci default NULL,
			`is_fav` tinyint(1) unsigned DEFAULT '0',
			`date_add` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
		) comment = 'Twitter Feed cache data';");
		add_option('_ecc_db_version', $tws_db_version);
	}
}