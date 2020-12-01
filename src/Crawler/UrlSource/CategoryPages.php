<?php

namespace Ssgpress\Crawler\UrlSource;

require_once 'UrlSource.php';

class CategoryPages extends UrlSource {

	static function find(): array {/*
		global $wp_rewrite;

		$categories = get_taxonomies(
			array('public'=>true),
			'objects'
		);
		$queue = [];

		foreach ()
			foreach ($categories as $category){
				$link = get_categor($category->id);
				$queue[] = array(
					'url'=>$link,
					'target'=>substr($link, strlen(site_url('index.php')) ).DIRECTORY_SEPARATOR.'index.html'
				);
				$pages = ceil(count_user_posts($category->id)/get_option('posts_per_page'));
				for($p = 1; $p<=$pages; $p++){
					$pagelink = sprintf(
						"%s%s/%d",
						$link,
						$wp_rewrite->pagination_base,
						$p
					);
					$queue[] = array(
						'url'=> $pagelink,
						'target'=>substr($pagelink, strlen(site_url('index.php')) ).DIRECTORY_SEPARATOR.'index.html'
					);
				}
			}

		return $queue;*/
		return [];
	}
}