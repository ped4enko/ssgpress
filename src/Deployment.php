<?php


namespace Ssgpress;

require_once 'Deployment/DeploymentOption/Netlify.php';
require_once 'Deployment/DeploymentOption/Vercel.php';
require_once 'Deployment/DeploymentOption/Zip.php';
require_once 'Deployment/DeploymentOption/ZipDownload.php';


use Exception;
use Ssgpress\Deployment\DeploymentOption\Netlify;
use Ssgpress\Deployment\DeploymentOption\Vercel;
use Ssgpress\Deployment\DeploymentOption\Zip;
use Ssgpress\Deployment\DeploymentOption\ZipDownload;

class Deployment {

	var $run;

	function __construct( int $run ) {
		$this->run = $run;
	}

	public function deploy( string $source ): string {
		$deployment_method = get_option( 'ssgp_deployment' );

		switch ( $deployment_method ) {
			case 'netlify':
				$deployment = new Netlify( $this, $this->run, $source );
				break;
			case 'vercel':
				$deployment = new Vercel( $this, $this->run, $source );
				break;
			case 'zip-dir':
				$deployment = new Zip( $this, $this->run, $source );
				break;
			case 'zip-download':
				$deployment = new ZipDownload( $this, $this->run, $source );
				break;
			default:
				Logging::log(
					$this->run,
					sprintf( "Deployment failed: Deployment method %s not known", $deployment_method )
				);
				throw new Exception(
					sprintf( "Deployment failed: Deployment method %s not known", $deployment_method )
				);
		}

		return $deployment->deploy();
	}

}