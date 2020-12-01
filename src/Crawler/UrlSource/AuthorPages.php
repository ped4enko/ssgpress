<?php

namespace Ssgpress\Crawler\UrlSource;

require_once 'UrlSource.php';

class AuthorPages extends UrlSource {

	static function find(): array {
		global $wp_rewrite;

		$users = get_users();
		$queue = [];

		foreach ( $users as $user ) {
			$link    = get_author_posts_url( $user->id );
			$queue[] = array(
				'url'    => $link,
				'target' => substr( $link, strlen( site_url() ) ) . DIRECTORY_SEPARATOR . 'index.html'
			);
			$pages   = ceil( count_user_posts( $user->id ) / get_option( 'posts_per_page' ) );
			for ( $p = 1; $p <= $pages; $p ++ ) {
				$pagelink = sprintf(
					"%s%s/%d",
					$link,
					$wp_rewrite->pagination_base,
					$p
				);
				$queue[]  = array(
					'url'    => $pagelink,
					'target' => substr( $pagelink, strlen( site_url() ) ) . DIRECTORY_SEPARATOR . 'index.html'
				);
			}
		}

		return $queue;
	}
}