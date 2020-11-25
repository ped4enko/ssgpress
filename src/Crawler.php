<?php

namespace Ssgpress;

use Ssgpress\Crawler\FindPosts;

require_once 'Crawler/FindPosts.php';

class Crawler {

	var $ssgpress;

	function __construct( &$parent ) {
		$this->ssgpress = $parent;
		add_action( 'ssgp_crawl_cron_hook', array( $this, 'cron_queue' ), 1 );

	}

	function gen_queue( $run ): void {
		global $wpdb;

		$this->ssgpress->logging->log( $run, "Generating list of URLs to scrape" );

		$urls = FindPosts::find();

		$sql_data         = array();
		$sql_placeholders = array();

		foreach ( $urls as $url ) {    // TODO Map?
			$sql_data[]         = $run;
			$sql_data[]         = $url;
			$sql_placeholders[] = '(%d, %s)';
		}

		$sql = "INSERT INTO {$wpdb->prefix}ssgp_queue (`run`, `url`) VALUES\n";
		$sql .= implode( ",\n", $sql_placeholders );
		$wpdb->query( $wpdb->prepare( $sql, $sql_data ) );
	}

	function cron_queue( $run ): void {
		global $wpdb;

		$args = array(
			'timeout'    => 20,
			'sslverify'  => false,
			'user-agent' => 'ssgp/0.0.1'
		);

		$this->ssgpress->logging->log( $run, "Starting crawler" );

		$queue = $wpdb->get_results( "SELECT `url` FROM {$wpdb->prefix}ssgp_queue WHERE `run` = {$run}" );

		$i = 0;
		$j = count( $queue );

		foreach ( $queue as $item ) {
			$response = wp_remote_get( $item->url, $args );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {

				$filename = get_temp_dir() . "ssgpress/run_" . $run . "/" . parse_url( $item->url )['path'] . "index.html";

				$dirname = dirname( $filename );

				if ( ! is_dir( $dirname ) ) {
					mkdir( $dirname, 0755, true );
				}
				$fp = fopen( $filename, "w" );
				fwrite( $fp, $response['body'] );
				fclose( $fp );

			} else {
				$this->ssgpress->logging->log( $run, "Error archiving {$item->url}: {$response->get_error_message()}" );
			}

			$i ++;

			if ( $i % 10 === 0 ) {
				$this->ssgpress->logging->log( $run, "Crawled {$i} of {$j} pages" );
			}
		}

		$this->ssgpress->logging->log( $run, "Finished crawling" );
	}
}