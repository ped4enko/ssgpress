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

	var $run;

	function __construct( int $run ) {
		$this->run      = $run;
		add_action( 'ssgp_crawl_cron_hook', array( $this, 'cron_queue' ), 1 );

	}

	function gen_queue(): void {
		global $wpdb;

		$queue = array_merge(
			Crawler\UrlSource\Posts::find(),
			Crawler\UrlSource\AuthorPages::find(),
			Crawler\UrlSource\CategoryPages::find(),
			Crawler\UrlSource\StaticFiles::find()
		);

		$sql_data         = array();
		$sql_placeholders = array();


		foreach ( $queue as $map ) {
			$sql_data[]         = $this->run;
			$sql_data[]         = $map['url'];
			$sql_data[]         = $map['target'];
			$sql_placeholders[] = '(%d, %s, %s)';
		}

		$sql = sprintf( "INSERT INTO %sssgp_queue (`run`, `url`, `target`) VALUES\n", $wpdb->prefix );
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

		Logging::log( $this->run, "Starting crawler" );

		$queue = $wpdb->get_results(
			sprintf( "SELECT `url`, `target` FROM %sssgp_queue WHERE `run` = %s", $wpdb->prefix, $this->run )
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

				if(strlen($response["body"])>0) {
					$filename = sprintf(
						"%s%s%s",
						$root_path,
						DIRECTORY_SEPARATOR,
						$item->target
					);

					$dirname = dirname( $filename );

					if ( ! is_dir( $dirname ) ) {
						mkdir( $dirname, 0755, true );
					}
					$fp = fopen( $filename, "w" );
					fwrite( $fp, $response['body'] );
					fclose( $fp );
				}
			} else {
				Logging::log(
					$this->run,
					sprintf( "Error archiving %s: %s", $item->url, $response->get_error_message() )
				);
			}

			$i ++;

			// TODO Remove item from queue

			if ( $i % 10 === 0 ) {
				Logging::log( $this->run, sprintf( "Crawled %s of %s pages", $i, $j ) );
			}
		}

		Logging::log( $this->run, "Finished crawling" );

		return $root_path;
	}
}