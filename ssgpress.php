<?php /**
 * SSGpress
 *
 * @author            Merlin Scholz
 * @copyright         2020 Merlin Scholz
 * @license           AGPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       SSGpress
 * Plugin URI:        https://github.com/merlinscholz/ssgpress
 * Description:       A static site generator for your existing WordPress site
 * Version:           0.0.1
 * Author:            Merlin Scholz
 * Author URI:        https://github.com/merlinscholz/
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

use Ssgpress\Crawler;
use Ssgpress\Deployment;
use Ssgpress\Logging;

class Ssgpress {

	var $install;
	var $ajax;
	var $admin;
	var $settings;
	var $frontend;

	function __construct() {
		$this->install  = new Ssgpress\Install( $this );
		$this->ajax     = new Ssgpress\Ajax( $this );
		$this->settings = new Ssgpress\Settings( $this );
		$this->admin    = new Ssgpress\Admin( $this );
		$this->frontend = new Ssgpress\Frontend( $this );

		add_action( 'ssgp_build_cron_hook', array( $this, 'build_async' ), 1 );
	}

	/**
	 * Prepare and schedule a new run
	 */
	function build(): void {
		Logging::log( 0, "Getting run id" );
		$run = $this->get_next_run_id();

		Logging::log( $run, "Scheduling build via wp-cron" );
		wp_schedule_single_event( time(), 'ssgp_build_cron_hook', array( $run ) );
	}

	/**
	 * Generates a run ID for a new run
	 *
	 * @return int The next free run number
	 */
	function get_next_run_id(): int {
		global $wpdb;

		return ( (int) $wpdb->get_var( "SELECT COALESCE(MAX(`run`), 0) FROM {$wpdb->prefix}ssgp_log" ) ) + 1;
	}

	/**
	 * Start a prepared run
	 *
	 * @param int $run Number of the run to build
	 */
	function build_async( int $run ): void {

		$crawler = new Crawler( $this, $run );

		Logging::log( $run, "Generating list of URLs to scrape" );
		$crawler->gen_queue();

		Logging::log( $run, "Generating list of URLs to scrape" );
		$temp_files = $crawler->crawl_queue();

		$deployment = new Deployment( $this, $run );

		Logging::log( $run, "Deploying crawled page" );

		Logging::log( $run, sprintf( "Deployed page to %s", $deployment->deploy( $temp_files ) ) );

	}
}

new Ssgpress();