<?php


namespace Ssgpress;


class Install {

	var $ssgpress;

	function __construct( &$parent ) {
		$this->ssgpress = $parent;
		register_activation_hook( WP_PLUGIN_DIR . '/ssgpress/ssgpress.php', array( $this, 'init_db' ) );
		register_uninstall_hook( WP_PLUGIN_DIR . '/ssgpress/ssgpress.php', array( $this, 'drop_options' ) );
		register_uninstall_hook( WP_PLUGIN_DIR . '/ssgpress/ssgpress.php', array( $this, 'drop_db' ) );
	}

	function init_db() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;


		$ssgp_queries = [];


		array_push(
			$ssgp_queries,
			"CREATE TABLE {$wpdb->prefix}ssgp_queue (
id mediumint(8) unsigned NOT NULL auto_increment ,
run mediumint(8) unsigned NOT NULL,
url varchar(1024) NOT NULL,
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
timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY  (id)
)
COLLATE {$wpdb->collate}"
		);

		foreach ( $ssgp_queries as $query ) {
			dbDelta( $query );
		}
	}

	function drop_options() {
		delete_option( 'ssgp_options' );
	}

	function drop_db() {
		global $wpdb;

		$sql = "DROP TABLE IF EXISTS {$wpdb->prefix}ssgp_queue";
		$wpdb->query( $sql );

		$sql = "DROP TABLE IF EXISTS {$wpdb->prefix}ssgp_log";
		$wpdb->query( $sql );

	}


}