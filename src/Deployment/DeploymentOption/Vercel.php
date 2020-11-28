<?php


namespace Ssgpress\Deployment\DeploymentOption;

require_once 'DeploymentOption.php';

use Ssgpress\Deployment;
use Ssgpress\Logging;

class Vercel extends DeploymentOption {

	function __construct( deployment $parent, int $run, string $source ) {
		parent::__construct( $parent, $run, $source );
	}

	function deploy(): string {
		Logging::log( $this->run, "Starting Vercel deployment" );

		// TODO: Implement deploy() method.
	}

}