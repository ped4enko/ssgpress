<?php


namespace Ssgpress;


class Logging {
	/**
	 * Add new message to log
	 *
	 * @param int $run The run to which the message belongs
	 * @param string $message The message to add to the log
	 */
	static function log( int $run, string $message ): void {
		global $wpdb;
		$logging_query = "INSERT INTO {$wpdb->prefix}ssgp_log (`run`, `message`) VALUES (%d, %s)";
		$wpdb->query( $wpdb->prepare( $logging_query, array( $run, $message ) ) );
	}

	/**
	 * Retrieve all messages from the central log database
	 *
	 * @param int $run The run whose messages should be returned
	 * @param bool $sort_desc Wether to sort the results by a descending or an ascending timestamp
	 *
	 * @return array Sorted array of log objects
	 */
	static function get_all( int $run = null, bool $sort_desc = true ): array {
		global $wpdb;

		$order = $sort_desc === true ? 'DESC' : 'ASC';

		if ( $run === null ) {
			return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}ssgp_log ORDER BY timestamp {$order}" );
		}

		return $wpdb->get_results(
			"SELECT * FROM {$wpdb->prefix}ssgp_log
WHERE `run`={$run}
ORDER BY `timestamp` {$order}, `id` {$order} "
		);
	}
}