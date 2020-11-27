<?php

namespace Ssgpress\Crawler\UrlSource;

require_once 'FindPages.php';

class Posts extends UrlSource {

	static function find(): array {
		global $wpdb;

		$pages = $wpdb->get_results( "SELECT `id`
			FROM {$wpdb->posts}
			WHERE post_status = 'publish'
			  AND post_type NOT IN ('revision', 'nav_menu_item')" );

		return array_map( function ( $a ) {
			return get_page_link( $a->id );
		}, $pages );
	}
}