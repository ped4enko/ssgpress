<?php


namespace Ssgpress;


class Frontend {

	var $ssgpress;

	function __construct( &$parent ) {
		$this->ssgpress = $parent;
		add_action( 'init', array( $this, 'fix_crawl_links' ) );
		add_action( 'wp_head', array( $this, 'custom_generator' ) );
	}

	function fix_crawl_links() {
		$options = get_option( 'ssgp_options' );
		if ( $_SERVER['HTTP_USER_AGENT'] === 'ssgp/0.0.1' ) {
			define( 'WP_HOME', $options['ssgp_base_url'] );
			define( 'WP_SITEURL', $options['ssgp_base_url'] );
		}
	}


	function custom_generator() {
		echo '<meta name="generator" content="SSGpress" />' . "\n";
	}

}