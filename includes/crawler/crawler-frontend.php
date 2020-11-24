<?php


function ssgp_fix_crawl_links() {
	$options = get_option( 'ssgp_options' );
	if ( $_SERVER['HTTP_USER_AGENT'] === 'ssgp/0.0.1' ) {
		define( 'WP_HOME', $options['ssgp_base_url'] );
		define( 'WP_SITEURL', $options['ssgp_base_url'] );
	}
}


function ssgp_custom_generator() {
	echo '<meta name="generator" content="SSGpress" />' . "\n";
}

add_action( 'init', 'ssgp_fix_crawl_links' );
add_action( 'wp_head', 'ssgp_custom_generator' );