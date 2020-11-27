<?php

namespace Ssgpress;

require_once 'Crawler/UrlSource/Posts.php';
require_once 'Crawler/UrlSource/ArchivePages.php';
require_once 'Crawler/UrlSource/Attachments.php';
require_once 'Crawler/UrlSource/AuthorPages.php';
require_once 'Crawler/UrlSource/CategoryPages.php';
require_once 'Crawler/UrlSource/StaticFiles.php';

class Crawler {

	var $ssgpress;

	function __construct( $parent ) {
		$this->ssgpress = $parent;
		add_action( 'ssgp_crawl_cron_hook', array( $this, 'cron_queue' ), 1 );

	}

	function gen_queue( $run ): void {
		global $wpdb;

		$this->ssgpress->logging->log( $run, "Generating list of URLs to scrape" );

		// We only get source URLs instead of also target URLs so that there are no differences in the generated link
		$urls = Crawler\UrlSource\Posts::find();

		$sql_data         = array();
		$sql_placeholders = array();

		foreach ( $urls as $url ) {
			$sql_data[]         = $run;
			$sql_data[]         = $url;
			$sql_placeholders[] = '(%d, %s)';
		}

		$sql = sprintf( "INSERT INTO %sssgp_queue (`run`, `url`) VALUES\n", $wpdb->prefix );
		$sql .= implode( ",\n", $sql_placeholders );
		$wpdb->query( $wpdb->prepare( $sql, $sql_data ) );
	}

	function crawl_queue( $run ): void {    // TODO Parallelize?
		global $wpdb;

		$args = array(
			'timeout'    => 20,
			'sslverify'  => false,
			'user-agent' => 'ssgp/0.0.1'
		);

		$this->ssgpress->logging->log( $run, "Starting crawler" );

		$queue = $wpdb->get_results(
			sprintf( "SELECT `url` FROM %sssgp_queue WHERE `run` = %s", $wpdb->prefix, $run )
		);

		$i = 0;
		$j = count( $queue );

		foreach ( $queue as $item ) {
			$response = wp_remote_get( $item->url, $args );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {

				$filename = sprintf( "%sssgpress/run_%s/%sindex.html",
					get_temp_dir(),
					$run,
					parse_url( $item->url )['path']
				);

				$dirname = dirname( $filename );

				if ( ! is_dir( $dirname ) ) {
					mkdir( $dirname, 0755, true );
				}
				$fp = fopen( $filename, "w" );
				fwrite( $fp, $response['body'] );
				fclose( $fp );

			} else {
				$this->ssgpress->logging->log(
					$run,
					sprintf( "Error archiving %s: %s", $item->url, $response->get_error_message() )
				);
			}

			$i ++;

			// TODO Remove item from queue

			if ( $i % 10 === 0 ) {
				$this->ssgpress->logging->log( $run, sprintf( "Crawled %s of %s pages", $i, $j ) );
			}
		}

		$this->ssgpress->logging->log( $run, "Finished crawling" );
	}
}