<?php
if ( ! defined( 'ABSPATH' ) )
     exit;
?>
<div class="wrap">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
	<form action="javascript:void(0)" method="post" id="ssgp_build_form">
		<textarea name="log" id="log" cols="30" rows="10" disabled></textarea><br>
		<input type="submit">
	</form>
	<script>
        jQuery(document).ready(function($){
            $('#ssgp_build_form').on('submit', function(e){
                e.preventDefault();
                var data = {
                    'action': 'ssgp_build',
                    'nonce': '<?php echo wp_create_nonce('ssgp_build'); ?>'
                }
                jQuery.post(ajaxurl, data, function(response){
                    alert(response);
                })
            })
        })
	</script>
</div>