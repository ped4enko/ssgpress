<?php


namespace Ssgpress\Deployment\DeploymentOption;

use Exception;
use WP_Error;

abstract class DeploymentOption {

	var $source_path;
	var $run;
	var $has_been_run;
	var $deployed_location;

	function __construct( int $run, string $source ) {
		$this->source_path  = $source;
		$this->has_been_run = false;
		$this->run          = $run;
	}

	abstract function deploy(): ?WP_Error;

	function get_deployed_location(): string {
		if ( $this->has_been_run === false ) {
			throw new Exception( "Cannot get target location before deploy() has been successfully been run" );
		}

		return $this->deployed_location;
	}
}