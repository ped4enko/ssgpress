<?php


namespace Ssgpress\Deployment\DeploymentOption;

require_once 'DeploymentOption.php';

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Ssgpress\Deployment;
use Ssgpress\Logging;
use ZipArchive;

class Zip extends DeploymentOption {

	var $target_path;

	function __construct( deployment $parent, int $run, string $source ) {
		parent::__construct( $parent, $run, $source );
		$options_target_path = get_option( 'ssgp_zip_target_path' );
		if ( $options_target_path == null ) {
			$this->target_path = sprintf( "%sssgpress/run_%s.zip",
				get_temp_dir(),
				$run
			);
		} else {
			$this->target_path = $options_target_path;
		}
	}

	/**
	 * Sets ZIP target path manually, should only be used for other deployment methods that rely on ZIP. For normal
	 * ZIP deployment, use WordPress' set_option('ssgp_options')
	 *
	 * @param string $path The ZIP target path
	 */
	function set_target_path( string $path ) {
		$this->target_path = $path;
	}

	function deploy(): string {

		Logging::log( $this->run, "Starting Zip deployment" );

		$za = new ZipArchive();

		if ( ! is_dir( dirname( $this->target_path ) ) ) {
			mkdir( dirname( $this->target_path ), 0755, true );
		}

		if ( $za->open( $this->target_path, ZipArchive::CREATE | ZipArchive::OVERWRITE ) !== true ) {
			Logging::log( $this->run,
				sprintf( "File %s could not be created", $this->target_path )
			);
			throw new Exception( sprintf( "File %s could not be created", $this->target_path ) );
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
		Logging::log(
			$this->run,
			sprintf( "Finished Zip deployment to %s", $this->target_path )
		);

		return $this->target_path;
	}
}