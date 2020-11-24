<?php

function ssgp_register_admin_menus() {
	$ssgp_topmenu_hook = add_menu_page(
		'SSGpress',
		'SSGpress',
		'manage_options',
		'ssgp',
		'ssgp_main_page_html',
		'dashicons-media-archive'
	);

	$ssgp_menu_main_hook = add_submenu_page(
		'ssgp',
		'SSGpress',
		'Run',
		'manage_options',
		'ssgp',
		'ssgp_main_page_html'
	);

	$ssgp_menu_options_hook = add_submenu_page(
		'ssgp',
		'SSGpress Settings',
		'Settings',
		'manage_options',
		'ssgp_options',
		'ssgp_options_page_html'
	);
}

add_action( 'admin_menu', 'ssgp_register_admin_menus' );
