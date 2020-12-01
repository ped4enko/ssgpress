<?php


namespace Ssgpress;


use Ssgpress;

class Install {

	function __construct( ) {
		register_activation_hook( sprintf( "%s/ssgpress/ssgpress.php", WP_PLUGIN_DIR ), array( $this, 'init_db' ) );
		register_uninstall_hook( sprintf( "%s/ssgpress/ssgpress.php", WP_PLUGIN_DIR ), array( $this, 'drop_options' ) );
		register_uninstall_hook( sprintf( "%s/ssgpress/ssgpress.php", WP_PLUGIN_DIR ), array( $this, 'drop_db' ) );
	}

	/**
	 * Generate necessary tables in the Database
	 */
	function init_db(): void {
		require_once( sprintf( "%swp-admin/includes/upgrade.php", ABSPATH ) );

		global $wpdb;


		$ssgp_queries = [];


		array_push(
			$ssgp_queries,
			"CREATE TABLE {$wpdb->prefix}ssgp_queue (
id mediumint(8) unsigned NOT NULL auto_increment ,
run mediumint(8) unsigned NOT NULL,
url varchar(1024) NOT NULL,
target varchar(1024) NOT NULL,
PRIMARY KEY  (id)
)
COLLATE {$wpdb->collate}"
		);

		array_push(
			$ssgp_queries,
			"CREATE TABLE {$wpdb->prefix}ssgp_log (
id mediumint(8) unsigned NOT NULL auto_increment ,
run mediumint(9) unsigned NOT NULL,
message varchar(1024) NULL,
timestamp DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (id)
)
COLLATE {$wpdb->collate}"
		);

		foreach ( $ssgp_queries as $query ) {
			dbDelta( $query );
		}
	}

	/**
	 * Delete user-supplied settings options
	 */
	function drop_options(): void {
		delete_option( 'ssgp_options' );
	}

	/**
	 * Delete database tables
	 */
	function drop_db(): void {
		global $wpdb;

		$sql = "DROP TABLE IF EXISTS {$wpdb->prefix}ssgp_queue";
		$wpdb->query( $sql );

		$sql = "DROP TABLE IF EXISTS {$wpdb->prefix}ssgp_log";
		$wpdb->query( $sql );

	}


}