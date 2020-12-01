<?php


namespace Ssgpress;


class PostProcessing {
	var $run;

	public function __construct( $run ) {
		$this->run = $run;
	}

	public function process( $temp_files ) {
		// Get files recursively
		// Cache them in DB?
		// Replace default url with custom one
		/*
		$file_tree = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $temp_files ),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		$files_list = [];

		foreach ( $file_tree as $name => $file ) {
			if ( ! $file->isDir() ) {
				$files_list[] = $file->getPathname();
			}
		}

		foreach ( $files_list as $file ){
			if (!$content = file_get_contents ($file)) {
				logging::log($this->run, sprintf("File %s could not be opened", $file));
				throw new Exception(sprintf("File %s could not be opened", $file));
			}

			$content = str_replace(
				site_url(),
				rtrim(get_option('ssgp_base_url'), '/'),
				$content
			);

			if (!file_put_contents ($file, $content)) {
				logging::log($this->run, sprintf("File %s could not be written", $file));
				throw new Exception(sprintf("File %s could not be written", $file));
			}
		}*/
	}
}