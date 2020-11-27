<?php


namespace Ssgpress;


class Settings {

	var $ssgpress;

	function __construct( $parent ) {
		$this->ssgpress = $parent;
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register all settings in WordPress
	 */
	function register_settings(): void {
		register_setting( 'ssgp', 'ssgp_options' );

		add_settings_section( 'ssgp_settings_crawler',
			__( 'Crawler', 'ssgp' ),
			'',
			'ssgp'
		);

		add_settings_field(
			'ssgp_base_url',
			__( 'Base URL', 'ssgp' ),
			array( $this, 'domain_callback' ),
			'ssgp',
			'ssgp_settings_crawler',
			array(
				'label_for' => 'ssgp_base_url',
			)
		);

		add_settings_field(
			'ssgp_comments',
			__( 'Comments', 'ssgp' ),
			array( $this, 'comments_callback' ),
			'ssgp',
			'ssgp_settings_crawler',
			array(
				'label_for' => 'ssgp_comments',
			)
		);

		add_settings_field(
			'ssgp_comments_code',
			__( 'Custom comment code', 'ssgp' ),
			array( $this, 'comments_code_callback' ),
			'ssgp',
			'ssgp_settings_crawler',
			array(
				'label_for' => 'ssgp_comments_code',
			)
		);
	}

	/**
	 * The template for the Base URL option field
	 *
	 * @param array $args WordPress-supplied options
	 */
	function domain_callback( array $args ): void {
		$options = get_option( 'ssgp_options' ); // TODO Move this to class instance attribute
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

	/**
	 * The template for the comment handling option field
	 *
	 * @param array $args WordPress-supplied options
	 */
	function comments_callback( array $args ): void {
		$options = get_option( 'ssgp_options' );
		?>
        <select name="ssgp_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
                id="<?php echo esc_attr( $args['label_for'] ); ?>">
            <option value="initial"
				<?php echo isset( $options[ $args['label_for'] ] )
					? ( selected( $options[ $args['label_for'] ], 'initial', false ) ) : ( '' ); ?>>
				<?php esc_html_e(
					'Leave comments as they are (will result in error messages on comment submission)',
					'ssgp'
				); ?>
            </option>
            <option value="disable" <?php echo isset( $options[ $args['label_for'] ] )
				? ( selected( $options[ $args['label_for'] ], 'disable', false ) ) : ( '' ); ?>>
				<?php esc_html_e(
					'Disable comments completely',
					'ssgp'
				); ?>
            </option>
            <option value="readonly" <?php echo isset( $options[ $args['label_for'] ] )
				? ( selected( $options[ $args['label_for'] ], 'readonly', false ) ) : ( '' ); ?>>
				<?php esc_html_e(
					'Disable new comments',
					'ssgp'
				); ?>
            </option>
            <option value="replace" <?php echo isset( $options[ $args['label_for'] ] )
				? ( selected( $options[ $args['label_for'] ], 'replace', false ) ) : ( '' ); ?>>
				<?php esc_html_e(
					'Replace comments with custom code',
					'ssgp'
				); ?>
            </option>
        </select>
		<?php
	}

	/**
	 * The template for the custom comment code option field
	 *
	 * @param array $args WordPress-supplied options
	 */
	function comments_code_callback( array $args ): void {
		$options = get_option( 'ssgp_options' );
		?>
        <textarea name="ssgp_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
                  id="<?php echo esc_attr( $args['label_for'] ); ?>"
                  cols="80" rows="15"><?php echo $options[ $args['label_for'] ]; ?></textarea>
		<?php
	}


}