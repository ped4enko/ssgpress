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

require_once 'includes/Admin.php';
require_once 'includes/Crawler.php';
require_once 'includes/Settings.php';

// TODO Move this to classes
include_once 'includes/install.php';
include_once 'includes/uninstall.php';
include_once 'includes/crawler/crawler-frontend.php';

class Ssgpress {
	var $crawler;
	var $admin;
	var $settings;

	function __construct() {
		$settings = new Ssgpress\Settings();
		$admin    = new Ssgpress\Admin();
		$crawler  = new Ssgpress\Crawler();

	}
}

new Ssgpress();