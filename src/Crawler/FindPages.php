<?php


namespace Ssgpress\Crawler;


interface FindPages {
	/**
	 * Return array of URLs of specific page type
	 * @return array URLs of requested page type
	 */
	static function find(): array;
}