<?php /**
 * SSGpress
 *
 * @author            Merlin Scholz
 * @copyright         2020 Merlin Scholz
 * @license           AGPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       SSGpress
 * Plugin URI:
 * Description:       A static site generator for your existing WordPress site
 * Version:           0.0.1
 * Author:            Merlin Scholz
 * Author URI:        https://scholz.ruhr
 * Text Domain:       ssgpress
 * License:           AGPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/agpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'src/Install.php';
require_once 'src/Ajax.php';
require_once 'src/Admin.php';
require_once 'src/Crawler.php';
require_once 'src/Frontend.php';
require_once 'src/Settings.php';
require_once 'src/Logging.php';
require_once 'src/Deployment.php';

class Ssgpress {

	// TODO async
	// TODO different file
	// TODO cache
	// TODO DB queue

	var $install;
	var $ajax;
	var $crawler;
	var $admin;
	var $settings;
	var $frontend;
	var $logging;
	var $deployment;

	function __construct() {
		$this->install    = new Ssgpress\Install( $this );
		$this->ajax       = new Ssgpress\Ajax( $this );
		$this->settings   = new Ssgpress\Settings( $this );
		$this->admin      = new Ssgpress\Admin( $this );
		$this->crawler    = new Ssgpress\Crawler( $this );
		$this->frontend   = new Ssgpress\Frontend( $this );
		$this->logging    = new Ssgpress\Logging( $this );
		$this->deployment = new Ssgpress\Deployment( $this );

		add_action( 'ssgp_build_cron_hook', array( $this, 'build_async' ), 1 );
	}

	function build() {
		$this->logging->log( 0, "Getting run id" );
		$run = $this->get_next_run_id();

		$this->logging->log( $run, "Scheduling build via wp-cron" );
		wp_schedule_single_event( time(), 'ssgp_build_cron_hook', array( $run ) );
	}

	function build_async($run){

		$this->logging->log( $run, "Generating list of URLs to scrape" );
		$this->crawler->gen_queue( $run );

		$this->logging->log( $run, "Generating list of URLs to scrape" );
		$this->crawler->crawl_queue( $run );

		$this->logging->log( $run, "Deploying crawled page" );
		$this->deployment->deploy( $run );

	}

	function get_next_run_id(): int {
		global $wpdb;

		return $wpdb->get_var( "SELECT COALESCE(MAX(run), 0) as `last_run` FROM {$wpdb->prefix}ssgp_log" ) + 1;
	}
}

new Ssgpress();