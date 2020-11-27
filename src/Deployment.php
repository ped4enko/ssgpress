<?php


namespace Ssgpress;

require_once 'Deployment/DeploymentOption/Netlify.php';
require_once 'Deployment/DeploymentOption/Vercel.php';
require_once 'Deployment/DeploymentOption/Zip.php';


use Ssgpress;
use Ssgpress\Deployment\DeploymentOption\Netlify;
use Ssgpress\Deployment\DeploymentOption\Vercel;
use Ssgpress\Deployment\DeploymentOption\Zip;
use WP_Error;

class Deployment {

	var $ssgpress;
	var $run;

	function __construct( ssgpress $parent, int $run ) {
		$this->ssgpress = $parent;
		$this->run      = $run;
	}

	public function deploy( string $source ): ?WP_Error {
		$deployment_method = get_option( 'ssgp_options' )['ssgp_deployment'];

		switch ( $deployment_method ) {
			case 'netlify':
				$deployment = new Netlify( $this, $this->run, $source );
				$deployment->deploy();
				break;
			case 'vercel':
				$deployment = new Vercel( $this, $this->run, $source );
				$deployment->deploy();
				break;
			case 'zip':
				$deployment = new Zip( $this, $this->run, $source );
				$deployment->deploy();
				break;
			default:
				$this->ssgpress->logging->log(
					$this->run,
					sprintf( "Deployment failed: Deployment method %s not known", $deployment_method )
				);
		}


		return null;
	}

}