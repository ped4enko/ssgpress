<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

if ( isset( $_GET['settings-updated'] ) ) {
	add_settings_error( 'ssgp_messages', 'ssgp_message', __( 'Settings Saved', 'ssgp' ), 'updated' );
}

settings_errors( 'ssgp_messages' );
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
		<?php
		settings_fields( 'ssgp' );
		do_settings_sections( 'ssgp' );
		submit_button( 'Save Settings' );
		?>
    </form>
</div>