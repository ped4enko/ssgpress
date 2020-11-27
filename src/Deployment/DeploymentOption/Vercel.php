<?php


namespace Ssgpress\Deployment\DeploymentOption;

require_once 'DeploymentOption.php';

use WP_Error;

class Vercel extends DeploymentOption {

	function __construct( int $run, string $source ) {
		parent::__construct( $run, $source );
	}

	function deploy(): ?WP_Error {
		// TODO: Implement deploy() method.
	}

}