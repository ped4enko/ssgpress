<?php


namespace Ssgpress;


class Logging {
	var $ssgpress;

	function __construct( &$parent ) {
		$this->ssgpress = $parent;
	}

	function log( $run, $message ): void {
		global $wpdb;
		$logging_query = "INSERT INTO {$wpdb->prefix}ssgp_log (`run`, `message`) VALUES (%d, %s)";
		$wpdb->query( $wpdb->prepare( $logging_query, array( $run, $message ) ) );
	}

	function get_all( $run = null, $sort_desc = true ): array {
		global $wpdb;

		$order = $sort_desc === true ? 'DESC' : 'ASC';

		if ( $run === null ) {
			return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ssgp_log ORDER BY timestamp {$order}" );
		}

		return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ssgp_log WHERE `run`={$run} ORDER BY `timestamp` {$order}, `id` {$order} " );
	}
}