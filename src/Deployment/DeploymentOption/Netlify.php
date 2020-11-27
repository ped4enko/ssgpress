<?php


namespace Ssgpress\Deployment\DeploymentOption;

require_once 'DeploymentOption.php';
require_once 'Zip.php';

use Ssgpress\Deployment;

class Netlify extends DeploymentOption {

	// TODO Switch to file digest method (https://docs.netlify.com/api/get-started/#file-digest-method)

	var $api_endpoint;
	var $api_token;
	var $api_site;

	function __construct( deployment $parent, int $run, string $source ) {
		parent::__construct( $parent, $run, $source );
		$options            = get_option( 'ssgp_options' );
		$this->api_endpoint = sprintf( "https://api.netlify.com/api/v1/%s/deploys", $this->api_site );
		$this->api_token    = $options['ssgp_netlify_token'];
	}

	function deploy(): string {
		$this->deployment->ssgpress->logging->log( $this->run, "Starting Netlify deployment" );

		$target_path = sprintf( "%sssgpress/run_%s.zip",
			get_temp_dir(),
			$this->run
		);

		$zip = new Zip( $this->run, $this->source_path );
		$zip->set_target_path( $target_path );

		$zip_path = $zip->deploy();

		$headers = array(
			'Content-Type'  => 'application/zip',
			'Authorization' => sprintf( 'Bearer %s', $this->api_token ),
		);

		$file     = fopen( $zip_path, 'r' );
		$filesize = filesize( $zip_path );
		$payload  = fread( $file, $filesize );
		fclose( $file );

		$args = array(
			'headers' => $headers,
			'timeout' => 3600,
			'body'    => $payload
		);

		wp_remote_post( $this->api_endpoint, $args );

		$this->has_been_run = true;
		// TODO: Implement deploy() method.
	}
}