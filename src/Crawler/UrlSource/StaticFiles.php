<?php

namespace Ssgpress\Crawler\UrlSource;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

require_once 'UrlSource.php';

class StaticFiles extends UrlSource {

	static function find(): array {
		$files   = [];
		$files[] = array(
			'url'    => home_url(),
			'target' => '/index.html'
		);
		$files[] = array(
			'url'    => site_url( 'robots.txt' ),
			'target' => '/robots.txt'
		);

		while ( true ) {
			$rand_string = bin2hex( random_bytes( 24 ) );
			if ( wp_remote_retrieve_response_code( wp_remote_get( site_url( 'index.php/' . $rand_string ) ) ) === 404 ) {
				break;
			}
		}
		$files[] = array(
			'url'    => site_url( $rand_string ),
			'target' => '/404.html'
		);

		// TODO: Implement find() method.

		// 404
		// RSS
		// sitemap
		// robots
		$file_tree = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( WP_CONTENT_DIR . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR ),
			RecursiveIteratorIterator::LEAVES_ONLY
		);


		foreach ( $file_tree as $name => $file ) {
			if ( ! $file->isDir() ) {
				$files[] = array(
					'url'    => site_url( substr( $file->getPathname(), strlen( ABSPATH ) ) ),
					'target' => substr( $file->getPathname(), strlen( ABSPATH ) )
				);
			}
		}

		$file_tree = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( ABSPATH . DIRECTORY_SEPARATOR . 'wp-includes' ),
			RecursiveIteratorIterator::LEAVES_ONLY
		);


		foreach ( $file_tree as $name => $file ) {
			if ( ! $file->isDir() ) {
				$files[] = array(
					'url'    => site_url( substr( $file->getPathname(), strlen( ABSPATH ) ) ),
					'target' => substr( $file->getPathname(), strlen( ABSPATH ) )
				);
			}
		}

		return $files;
	}
}