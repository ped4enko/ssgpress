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

include_once 'includes/install.php';
include_once 'includes/uninstall.php';
include_once 'includes/crawler/crawler-frontend.php';
include_once 'includes/crawler/crawler-backend.php';
include_once 'includes/register/register-admin-menus.php';
include_once 'includes/register/register-settings.php';



function ssgp_main_page_html(){
	include 'admin/main.php';
}

function ssgp_options_page_html(){
    include 'admin/options.php';
}
