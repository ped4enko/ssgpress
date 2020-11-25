<?php


namespace Ssgpress\Crawler;

require_once 'FindPages.php';

class FindPosts implements FindPages {

	static function find(): array {
		global $wpdb;

		$pages = $wpdb->get_results( "SELECT `id`
			FROM {$wpdb->posts}
			WHERE post_status = 'publish'
			  AND post_type = 'page'" );

		return array_map( function ( $a ) {
			return get_page_link( $a->id );
		}, $pages );
	}
}