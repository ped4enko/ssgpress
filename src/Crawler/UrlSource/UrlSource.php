<?php


namespace Ssgpress\Crawler\UrlSource;


abstract class UrlSource {
	/**
	 * Return array of URLs of specific page type
	 * @return array URLs of requested page type
	 */
	abstract static function find(): array;
}