<?php


namespace Ssgpress;


require_once 'Deployment/Netlify.php';
require_once 'Deployment/Zip.php';

class Deployment {

	var $ssgpress;

	function __construct( $parent ) {
		$this->ssgpress = $parent;
	}

	public function deploy( $run ) {
	}

}