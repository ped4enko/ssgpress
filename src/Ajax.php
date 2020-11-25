<?php


namespace Ssgpress;


class Ajax {

	var $ssgpress;

	function __construct( &$parent ) {
		$this->ssgpress = $parent;
		add_action( 'wp_ajax_ssgp_build', array( $this, 'build' ) );
		add_action( 'wp_ajax_ssgp_refresh_logs', array( $this, 'logs' ) );
	}

	function build() {
		check_ajax_referer( 'ssgp_build', 'nonce' );

		$this->ssgpress->crawler->build();
		wp_die();
	}

	function logs() {
		check_ajax_referer( 'ssgp_refresh_logs', 'nonce' );
		$logs = $this->ssgpress->logging->get_all();
		foreach ( $logs as $line ) {
			echo "Run " . $line->run . " at " . $line->timestamp . ": " . $line->message . "\n";
		}
		wp_die();
	}
}