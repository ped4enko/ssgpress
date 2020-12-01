<?php

namespace Ssgpress\Crawler\UrlSource;

require_once 'UrlSource.php';

class Posts extends UrlSource {

	static function find(): array {
		global $wpdb;

		$pages = $wpdb->get_results( "SELECT `id`
			FROM {$wpdb->posts}
			WHERE post_status = 'publish'
			  AND post_type NOT IN ('revision', 'nav_menu_item')" );

		$queue = [];
		foreach ( $pages as $page ) {
			$link    = get_permalink( $page->id );
			$queue[] = array(
				'url'    => $link,
				'target' => substr( $link, strlen( site_url() ) ) . DIRECTORY_SEPARATOR . 'index.html'
			);
		}

		return $queue;
	}
}