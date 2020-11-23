<?php


function ssgp_register_settings(){
	register_setting('ssgp', 'ssgp_options');

	add_settings_section('ssgp_settings_crawler',
		__('Crawler', 'ssgp'),
		'',
		'ssgp'
	);

	add_settings_field(
		'ssgp_domain',
		__( 'Base URL', 'ssgp' ),
		'ssgp_settings_domain_callback',
		'ssgp',
		'ssgp_settings_crawler',
		array(
			'label_for' => 'ssgp_base_url',
		)
	);
}

function ssgp_settings_domain_callback( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'ssgp_options' );
	?>
	<input type="url"
	        id="<?php echo esc_attr( $args['label_for'] ); ?>"
	        name="ssgp_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
	        value="<?php echo $options[ $args['label_for'] ]; ?>"
			placeholder="<?php echo get_site_url() ?>">

	<p class="description">
		<?php esc_html_e( 'The base domain of the site you want to deploy.', 'ssgp' ); ?>
	</p>
	<p class="description">
		<?php esc_html_e( 'Leave empty to use relative URLs.', 'ssgp' ); ?>
	</p>
	<?php
}

add_action('admin_init', 'ssgp_register_settings');
