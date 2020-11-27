<?php


namespace Ssgpress\Deployment\DeploymentOption;

require_once 'DeploymentOption.php';
require_once 'Zip.php';

use WP_Error;

class Netlify extends DeploymentOption {

	// TODO Switch to file digest method (https://docs.netlify.com/api/get-started/#file-digest-method)

	var $api_endpoint;
	var $api_token;
	var $api_site;

	function __construct( int $run, string $source ) {
		parent::__construct( $run, $source );
		$options            = get_option( 'ssgp_options' );
		$this->api_endpoint = sprintf( "https://api.netlify.com/api/v1/%s/deploys", $this->api_site );
		$this->api_token    = $options['ssgp_netlify_token'];
	}

	function deploy(): ?WP_Error {
		$target_path = sprintf( "%sssgpress/run_%s.zip",
			get_temp_dir(),
			$this->run
		);

		$zip = new Zip( $this->run, $this->source_path );
		$zip->set_target_path( $target_path );

		$zip_deploy = $zip->deploy();
		if ( is_wp_error( $zip_deploy ) ) {
			return $zip_deploy;
		}

		$zip_path = $zip->get_deployed_location();

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

		$this->has_been_run = true;
		// TODO: Implement deploy() method.
	}
}