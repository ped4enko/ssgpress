<?php

namespace Ssgpress;

use Ssgpress;

require_once 'Crawler/UrlSource/Posts.php';
require_once 'Crawler/UrlSource/ArchivePages.php';
require_once 'Crawler/UrlSource/Attachments.php';
require_once 'Crawler/UrlSource/AuthorPages.php';
require_once 'Crawler/UrlSource/CategoryPages.php';
require_once 'Crawler/UrlSource/StaticFiles.php';

class Crawler {

	var $ssgpress;
	var $run;

	function __construct( ssgpress $parent, int $run ) {
		$this->ssgpress = $parent;
		$this->run      = $run;
		add_action( 'ssgp_crawl_cron_hook', array( $this, 'cron_queue' ), 1 );

	}

	function gen_queue(): void {
		global $wpdb;

		$this->ssgpress->logging->log( $this->run, "Generating list of URLs to scrape" );

		// We only get source URLs instead of also target URLs so that there are no differences in the generated link
		$urls = Crawler\UrlSource\Posts::find();

		$sql_data         = array();
		$sql_placeholders = array();

		foreach ( $urls as $url ) {
			$sql_data[]         = $this->run;
			$sql_data[]         = $url;
			$sql_placeholders[] = '(%d, %s)';
		}

		$sql = sprintf( "INSERT INTO %sssgp_queue (`run`, `url`) VALUES\n", $wpdb->prefix );
		$sql .= implode( ",\n", $sql_placeholders );
		$wpdb->query( $wpdb->prepare( $sql, $sql_data ) );
	}

	function crawl_queue( $target = null ): string {    // TODO Parallelize?
		global $wpdb;

		$args = array(
			'timeout'    => 20,
			'sslverify'  => false,
			'user-agent' => 'ssgp/0.0.1'
		);

		$this->ssgpress->logging->log( $this->run, "Starting crawler" );

		$queue = $wpdb->get_results(
			sprintf( "SELECT `url` FROM %sssgp_queue WHERE `run` = %s", $wpdb->prefix, $this->run )
		);

		$i = 0;
		$j = count( $queue );
		if ( $target === null ) {
			$root_path = sprintf( "%sssgpress/run_%s",
				get_temp_dir(),
				$this->run
			);
		} else {
			$root_path = $target;
		}

		foreach ( $queue as $item ) {
			$response = wp_remote_get( $item->url, $args );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {

				$filename = sprintf( "%s/%sindex.html",
					$root_path,
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
					$this->run,
					sprintf( "Error archiving %s: %s", $item->url, $response->get_error_message() )
				);
			}

			$i ++;

			// TODO Remove item from queue

			if ( $i % 10 === 0 ) {
				$this->ssgpress->logging->log( $this->run, sprintf( "Crawled %s of %s pages", $i, $j ) );
			}
		}

		$this->ssgpress->logging->log( $this->run, "Finished crawling" );

		return $root_path;
	}
}