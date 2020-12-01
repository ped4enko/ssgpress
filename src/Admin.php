<?php


namespace Ssgpress;

class Admin {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );

	}


	/**
	 * Register admin menus in WordPress
	 */
	function register_menu(): void {
		add_menu_page(
			'SSGpress',
			'SSGpress',
			'manage_options',
			'ssgp',
			array( $this, 'main_menu' ),
			'dashicons-media-archive'
		);

		add_submenu_page(
			'ssgp',
			'SSGpress',
			'Run',
			'manage_options',
			'ssgp',
			array( $this, 'main_menu' )
		);

		add_submenu_page(
			'ssgp',
			'SSGpress Prerequisites',
			'Prerequisites',
			'manage_options',
			'ssgp_prerequisites',
			array( $this, 'prerequisites_menu' )
		);

		add_submenu_page(
			'ssgp',
			'SSGpress Settings',
			'Settings',
			'manage_options',
			'ssgp_options',
			array( $this, 'options_menu' )
		);
	}


	/**
	 * Include the main admin menu template
	 */
	function main_menu(): void {
		include WP_PLUGIN_DIR . '/ssgpress/admin/main.php'; // TODO Why??
	}

	/**
	 * Include the options admin menu template
	 */
	function options_menu(): void {
		include WP_PLUGIN_DIR . '/ssgpress/admin/options.php';
	}

	/**
	 * Include the prerequisites admin menu template
	 */
	function prerequisites_menu(): void {
		include WP_PLUGIN_DIR . '/ssgpress/admin/prerequisites.php';
	}

}