<?php


namespace Ssgpress\Deployment\DeploymentOption;

use Ssgpress\Deployment;

abstract class DeploymentOption {

	var $deployment;
	var $source_path;
	var $run;

	function __construct( deployment $parent, int $run, string $source ) {
		$this->deployment  = $parent;
		$this->source_path = $source;
		$this->run         = $run;
	}

	/**
	 * Deploy the prepared site
	 * @return string The location to which the site got deployed
	 */
	abstract function deploy(): string;
}