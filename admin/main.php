<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
    <h1 id="title"><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <form action="javascript:void(0)" method="post" id="ssgp_build_form" style="display: inline">
		<?php submit_button( __( 'Build', 'ssgp' ), 'primary', 'build', false ); ?>
    </form>
    <form action="javascript:void(0)" method="post" id="ssgp_refresh_logs_form" style="display: inline">
		<?php submit_button( __( 'Refresh Logs', 'ssgp' ), 'secondary', 'refresh_logs', false ); ?>
    </form>
    </p>
    <p>
        <textarea name="log"
                  id="log"
                  cols="80" rows="15"
                  disabled
                  style="font-family: monospace;"
                  placeholder="<?php esc_html_e( "Press 'Refresh Logs' to view the logs" ); ?>"></textarea>
    </p>
    </form>
    <script>
        jQuery(document).ready(function ($) {
            $('#ssgp_build_form').on('submit', function (e) {
                $('#build').prop('disabled', true);
                e.preventDefault();
                var request = {
                    'action': 'ssgp_build',
                    'nonce': '<?php echo wp_create_nonce( 'ssgp_build' ); ?>'
                }
                jQuery.ajax(ajaxurl, {
                    type: "POST",
                    data: request,
                    statusCode: {
                        200: function (response) {
                            jQuery('#title').append('<div class="notice notice-success"><p>Build started</p></div>');
                            console.log(response);
                        },
                        500: function (response) {
                            jQuery('#title').append('<div class="notice notice-error"><p>An error occurred: \'' + response.body + '\'</p></div>');
                        },
                    }
                })
            })
            $('#ssgp_refresh_logs_form').on('submit', function (e) {
                $('#refresh_logs').prop('disabled', true);
                e.preventDefault();
                var request = {
                    'action': 'ssgp_refresh_logs', // TODO
                    'nonce': '<?php echo wp_create_nonce( 'ssgp_refresh_logs' ); ?>'
                }
                jQuery.ajax(ajaxurl, {
                    type: "POST",
                    data: request,
                    statusCode: {
                        200: function (response) {
                            $('#log').val(response);
                            $('#refresh_logs').prop('disabled', false);
                        },
                        500: function (response) {
                            jQuery('#title').append('<div class="notice notice-error"><p>An error occurred: \'' + response.body + '\'</p></div>');
                        },
                    }
                })
            })
        })
    </script>
</div>