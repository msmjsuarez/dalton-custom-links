<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/msmjsuarez
 * @since      1.0.0
 *
 * @package    Dalton_Custom_Links
 * @subpackage Dalton_Custom_Links/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dalton_Custom_Links
 * @subpackage Dalton_Custom_Links/includes
 * @author     MJ Suarez <msmjsuarez@gmail.com>
 */
class Dalton_Custom_Links_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */


	public static function activate()
	{
		self::create_table();
	}

	public function create_table()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'dalton_custom_links';

		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				text text NOT NULL,
				url text NOT NULL,
				position varchar(100) NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}
}
