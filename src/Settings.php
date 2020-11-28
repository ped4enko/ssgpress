<?php


namespace Ssgpress;


use Ssgpress;

class Settings {

	var $ssgpress;

	function __construct( ssgpress $parent ) {
		$this->ssgpress = $parent;
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register all settings in WordPress
	 */
	function register_settings(): void {

		add_settings_section( 'ssgp_settings_crawler',
			__( 'Crawler', 'ssgp' ),
			'',
			'ssgp'
		);

		register_setting( 'ssgp', 'ssgp_base_url' );
		add_settings_field(
			'ssgp_base_url',
			__( 'Base URL', 'ssgp' ),
			array( $this, 'base_url_callback' ),
			'ssgp',
			'ssgp_settings_crawler',
			array(
				'label_for' => 'ssgp_base_url',
			)
		);

		add_settings_section( 'ssgp_settings_deployment',
			__( 'Deployment', 'ssgp' ),
			array( $this, "test" ),
			'ssgp'
		);

		register_setting( 'ssgp', 'ssgp_deployment' );
		add_settings_field(
			'ssgp_deployment',
			__( 'Deployment Method', 'ssgp' ),
			array( $this, 'deployment_callback' ),
			'ssgp',
			'ssgp_settings_deployment',
			array(
				'label_for' => 'ssgp_deployment',
			)
		);

		add_settings_section( 'ssgp_settings_deployment_netlify',
			__( 'Deployment on Netlify', 'ssgp' ),
			'',
			'ssgp'
		);
	}

	function test() {
		?>
        asdasd
		<?php
	}

	/**
	 * The template for the Base URL option field
	 *
	 * @param array $args WordPress-supplied options
	 */
	function base_url_callback( array $args ): void {
		$options = get_option( $args['label_for'] ); // TODO Move this to class instance attribute
		?>
        <input type="url"
               id="<?php echo esc_attr( $args['label_for'] ); ?>"
               name="ssgp_base_url"
               value="<?php echo $options; ?>"
               placeholder="<?php echo get_site_url() ?>">

        <p class="description">
			<?php esc_html_e( 'The base domain of the site you want to deploy.', 'ssgp' ); ?>
        </p>
        <p class="description">
			<?php esc_html_e( 'Leave empty to use relative URLs.', 'ssgp' ); ?>
        </p>
		<?php
	}

	/**
	 * The template for the deployment method option field
	 *
	 * @param array $args WordPress-supplied options
	 */
	function deployment_callback( array $args ): void {
		$options = get_option( $args['label_for'] );
		?>
        <select name="<?php echo esc_attr( $args['label_for'] ); ?>"
                id="<?php echo esc_attr( $args['label_for'] ); ?>">
            <option value="netlify"
				<?php echo isset( $options )
					? ( selected( $options, 'netlify', false ) ) : ( '' ); ?>>
				<?php esc_html_e(
					'Netlify',
					'ssgp'
				); ?>
            </option>
            <option value="vercel"
				<?php echo isset( $options )
					? ( selected( $options, 'vercel', false ) ) : ( '' ); ?>>
				<?php esc_html_e(
					'Vercel',
					'ssgp'
				); ?>
            </option>
            <option value="zip-dir"
				<?php echo isset( $options )
					? ( selected( $options, 'zip-dir', false ) ) : ( '' ); ?>>
				<?php esc_html_e(
					'Zip File to specific location',
					'ssgp'
				); ?>
            </option>
            <option value="zip-download"
				<?php echo isset( $options )
					? ( selected( $options, 'zip-download', false ) ) : ( '' ); ?>>
				<?php esc_html_e(
					'Zip File to be downloaded through browser',
					'ssgp'
				); ?>
            </option>
        </select>
		<?php
	}

}