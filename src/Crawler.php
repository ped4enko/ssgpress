<?php


namespace Ssgpress;

class Crawler {

	var $ssgpress;

	function __construct( &$parent ) {
		$this->ssgpress = $parent;
		add_action( 'ssgp_crawl_cron_hook', array( $this, 'cron_queue' ), 1 );

	}

	function gen_queue($run) : int {
		global $wpdb;

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

		return $run;
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

		$i = 0;
		$j = count($queue);

		foreach ( $queue as $item ) {
			$post     = $item->url;
			$response = wp_remote_get( get_permalink( $post ), $args );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$filename = get_temp_dir() . "/ssgpress/run_" . $run . "/" . parse_url( get_permalink( $post ) )[ path ] . "/index.html";
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

			$i++;

			if($i%10===0){
				$this->ssgpress->logging->log($run, "Crawled {$i} of {$j} pages");
			}
		}

		$this->ssgpress->logging->log($run, "Finished crawling");
	}
}