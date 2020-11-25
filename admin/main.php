<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <textarea name="log" id="log" cols="80" rows="15" disabled></textarea><br>
    <p>
    <form action="javascript:void(0)" method="post" id="ssgp_build_form" style="display: inline">
		<?php submit_button( __( 'Build', 'ssgp' ), 'primary', 'build', false ); ?>
    </form>
    <form action="javascript:void(0)" method="post" id="ssgp_refresh_logs_form" style="display: inline">
		<?php submit_button( __( 'Refresh Logs', 'ssgp' ), 'secondary', 'refresh_logs', false ); ?>
    </form>
    </p>
    </form>
    <script>
        jQuery(document).ready(function ($) {
            $('#ssgp_build_form').on('submit', function (e) {
                e.preventDefault();
                var data = {
                    'action': 'ssgp_build_start',
                    'nonce': '<?php echo wp_create_nonce( 'ssgp_build_start' ); ?>'
                }
                jQuery.post(ajaxurl, data, function (response) {
                    alert(response);
                })
            })
            $('#ssgp_refresh_logs_form').on('submit', function (e) {
                e.preventDefault();
                var data = {
                    'action': 'ssgp_build_start', // TODO
                    'nonce': '<?php echo wp_create_nonce( 'ssgp_build_start' ); ?>'
                }
                jQuery.post(ajaxurl, data, function (response) {
                    alert(response);
                })
            })
        })
    </script>
</div>