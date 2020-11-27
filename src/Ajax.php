<?php


namespace Ssgpress;


class Ajax {

	var $ssgpress;

	function __construct( $parent ) {
		$this->ssgpress = $parent;
		add_action( 'wp_ajax_ssgp_build', array( $this, 'build' ) );
		add_action( 'wp_ajax_ssgp_refresh_logs', array( $this, 'logs' ) );
	}

	/**
	 * Check request validity and initiate a new run
	 */
	function build(): void {
		check_ajax_referer( 'ssgp_build', 'nonce' );
		$this->ssgpress->build();

		wp_die();
	}

	/**
	 * Check request validity and queue, format and return logs for all runs
	 */
	function logs(): void {
		check_ajax_referer( 'ssgp_refresh_logs', 'nonce' );
		$logs = $this->ssgpress->logging->get_all();
		foreach ( $logs as $line ) {
			echo sprintf( "Run %s at %s: %s\n", $line->run, $line->timestamp, $line->message );
		}
		wp_die();
	}
}