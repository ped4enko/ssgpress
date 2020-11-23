<?php

function ssgp_init_db(){
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	global $wpdb;


	$ssgp_queries = [];


	array_push(
		$ssgp_queries,
		"CREATE TABLE {$wpdb->prefix}ssgp_queue (
id mediumint(8) unsigned NOT NULL auto_increment ,
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
PRIMARY KEY  (id)
)
COLLATE {$wpdb->collate}"
	);

	foreach ( $ssgp_queries as $query ) {
		dbDelta( $query );
	}
}

