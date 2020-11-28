<?php


namespace Ssgpress;


use Ssgpress;

class Frontend {

	var $ssgpress;

	function __construct( ssgpress $parent ) {
		$this->ssgpress = $parent;
		if ( $_SERVER['HTTP_USER_AGENT'] === 'ssgp/0.0.1' ) {
			add_action( 'init', array( $this, 'fix_crawl_links' ) );
			add_action( 'wp_head', array( $this, 'custom_generator' ) );
		}
	}

	/**
	 * Redefine WP_HOME and WP_SITEURL temporarily to the future base URL
	 */
	function fix_crawl_links(): void {
		$base_url = get_option( 'ssgp_base_url' );
		define( 'WP_HOME', $base_url );
		define( 'WP_SITEURL', $base_url );
	}

	/**
	 * Add 'SSGpress' to the HTML meta generator tag
	 */
	function custom_generator(): void {
		echo '<meta name="generator" content="SSGpress" />' . "\n";
	}

}