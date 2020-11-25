<?php


namespace Ssgpress;


class Logging {
	var $ssgpress;

	function __construct( &$parent ) {
		$this->ssgpress = $parent;
	}

	function log( $run, $message ): void {
		global $wpdb;
		$logging_query = "INSERT INTO {$wpdb->prefix}ssgp_log (`run`, `message`, `timestamp`) VALUES (%d, %s, %s)";
		$wpdb->query( $wpdb->prepare( $logging_query, array( $run, $message, current_time( 'mysql' ) ) ) );
	}

	function get_all( $run = null, $sort_desc = true ): array {
		global $wpdb;

		$order = $sort_desc === true ? 'DESC' : 'ASC';

		if ( $run === null ) {
			return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ssgp_log ORDER BY timestamp {$order}" );
		}

		return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ssgp_log WHERE `run`={$run} ORDER BY timestamp {$order}" );
	}
}