<?php


namespace Ssgpress\Deployment\DeploymentOption;

require_once 'DeploymentOption.php';
require_once 'Zip.php';

use Exception;
use Ssgpress\Deployment;

class ZipDownload extends DeploymentOption {

	function __construct( deployment $parent, int $run, string $source ) {
		parent::__construct( $parent, $run, $source );
	}

	function deploy(): string {
		$this->deployment->ssgpress->logging->log( $this->run, "Starting Zip Download deployment" );

		$target_path = sprintf( "%sssgpress/run_%s.zip",
			get_temp_dir(),
			$this->run
		);

		$zip = new Zip( $this->deployment, $this->run, $this->source_path );

		$zip->set_target_path( $target_path );

		$tmp_path = $zip->deploy();
		$zip_path = WP_CONTENT_DIR.'/ssgpress/'.basename($tmp_path);

		if ( ! is_dir( dirname( $zip_path ) ) ) {
			mkdir( dirname( $zip_path ), 0755, true );
		}

		if(rename($tmp_path, $zip_path)!==true){
			$this->deployment->ssgpress->logging->log(
				$this->run,
				sprintf("Could not move Zip file to %s", $zip_path)
			);
			throw new Exception(
				sprintf("Could not move Zip file to %s", $zip_path)
			);
		}

		return content_url('ssgpress/'.basename($zip_path));
	}
}