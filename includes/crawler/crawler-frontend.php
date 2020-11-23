<?php


function fix_crawl_links(){
	$options = get_option('ssgp_options');
	if($_SERVER['HTTP_USER_AGENT']==='ssgp/0.0.1'){
		define( 'WP_HOME', $options['ssgp_base_url'] );
		define( 'WP_SITEURL', $options['ssgp_base_url'] );
	}
}

add_action('init', 'fix_crawl_links');
