<?php


namespace Ssgpress\Deployment\DeploymentOption;

require_once 'DeploymentOption.php';

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use WP_Error;
use ZipArchive;

class Zip extends DeploymentOption {

	var $target_path;

	function __construct( int $run, string $source ) {
		parent::__construct( $run, $source );
	}

	function set_target_path( string $path ) {
		$this->target_path = realpath( $path );
	}

	function deploy(): ?WP_Error {
		// TODO: Implement deploy() method.

		$za = new ZipArchive();

		if ( ! is_dir( dirname( $this->target_path ) ) ) {
			mkdir( dirname( $this->target_path ), 0755, true );
		}

		if ( $za->open( $this->target_path, ZipArchive::CREATE | ZipArchive::OVERWRITE ) !== true ) {
			return new WP_Error();
		}

		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( $this->source_path ),
			RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ( $files as $name => $file ) {
			if ( ! $file->isDir() ) {
				$file_path     = $file->getRealPath();
				$relative_path = substr( $file_path, strlen( $this->source_path ) + 1 );
				$za->addFile( $file_path, $relative_path );
			}
		}

		$za->close();
		$this->has_been_run      = true;
		$this->deployed_location = $this->target_path;

		return null;
	}
}