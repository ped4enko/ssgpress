<?php


namespace Ssgpress;

class Admin {
	var $ssgpress;

	function __construct( &$parent ) {
		$this->ssgpress = $parent;
		add_action( 'admin_menu', array( $this, 'register_menu' ) );

	}


	function register_menu() {
		$ssgp_topmenu_hook = add_menu_page(
			'SSGpress',
			'SSGpress',
			'manage_options',
			'ssgp',
			array( $this, 'main_menu' ),
			'dashicons-media-archive'
		);

		$ssgp_menu_main_hook = add_submenu_page(
			'ssgp',
			'SSGpress',
			'Run',
			'manage_options',
			'ssgp',
			array( $this, 'main_menu' )
		);

		$ssgp_menu_options_hook = add_submenu_page(
			'ssgp',
			'SSGpress Settings',
			'Settings',
			'manage_options',
			'ssgp_options',
			array( $this, 'options_menu' )
		);
	}


	function main_menu() {
		include WP_PLUGIN_DIR . '/ssgpress/admin/main.php'; // TODO Why??
	}

	function options_menu() {
		include WP_PLUGIN_DIR . '/ssgpress/admin/options.php';
	}

}