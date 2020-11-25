<?php


namespace Ssgpress;

class Crawler {

	var $ssgpress;

	function __construct( &$parent ) {
		$this->ssgpress = $parent;
		add_action( 'ssgp_crawl_cron_hook', array( $this, 'cron_queue' ) );

	}

	function build() {
		$this->gen_queue();
	}

	function gen_queue() {
		global $wpdb;

		// TODO async
		// TODO different file
		// TODO cache
		// TODO DB queue

		$run = $wpdb->get_var( "SELECT COALESCE(MAX(run), 0) as `last_run` FROM {$wpdb->prefix}ssgp_log" ) + 1;

		$this->ssgpress->logging->log( $run, "Generating list of URLs to scrape" );

		$posts = get_posts( array( 'numberposts' => - 1 ) );

		$query        = "INSERT INTO {$wpdb->prefix}ssgp_queue (`run`, `url`) VALUES ";
		$values       = array();
		$placeholders = array();

		array_push( $values, $run, get_site_url() );
		$placeholders[] = "(%d, %s)";

		foreach ( $posts as $post ) {
			array_push( $values, $run, get_permalink( $post ) );
			$placeholders[] = "(%d, %s)";
		}

		$query .= implode( ', ', $placeholders );
		$wpdb->query( $wpdb->prepare( $query, $values ) );

		wp_schedule_single_event( time(), 'ssgp_crawl_cron_hook', array( $run ) );
	}

	function cron_queue( $run ) {
		global $wpdb;

		$args = array(
			'timeout'    => 20,
			'sslverify'  => false,
			'user-agent' => 'ssgp/0.0.1'
		);

		$queue = $wpdb->get_results( "SELECT `url` FROM {$wpdb->prefix}ssgp_queue WHERE `run` = {$run}" );

		$this->ssgpress->logging->log( $run, "Starting crawler" );

		foreach ( $queue as $item ) {
			$post     = $item->url;
			$response = wp_remote_get( get_permalink( $post ), $args );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$filename = get_temp_dir() . "ssgpress/run_" . $run . "/" . parse_url( get_permalink( $post ) )[ path ] . "/index.html";
				$dirname  = dirname( $filename );
				if ( ! is_dir( $dirname ) ) {
					mkdir( $dirname, 0755, true );
				}
				$fp = fopen( $filename, "w" );
				fwrite( $fp, $response["body"] );
				fclose( $fp );
			} else {
				echo $response->get_error_message();
			}
		}
	}
}