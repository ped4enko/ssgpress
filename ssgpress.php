<?php /**
 * SSGpress
 *
 * @author            Merlin Scholz
 * @copyright         2020 Merlin Scholz
 * @license           AGPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       SSGpress
 * Plugin URI:
 * Description:       A static site generator for your existing WordPress site
 * Version:           0.0.1
 * Author:            Merlin Scholz
 * Author URI:        https://scholz.ruhr
 * Text Domain:       ssgpress
 * License:           AGPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/agpl-3.0.txt
 */


add_action('admin_menu', 'ssgp_register_settings');
add_action('admin_menu', 'ssgp_register_admin_menus');
add_action('wp_ajax_ssgp_build', 'ssgp_ajax_build');

function ssgp_ajax_build(){
    check_ajax_referer('ssgp_build', 'nonce');

    // TODO async
    // TODO different file
    // TODO cache
    // TODO DB queue

    $ssgp_posts = get_posts(array('numberposts'=>-1));
	$args = array(
		'timeout'     => 20,
		'sslverify' => false
	);
    foreach($ssgp_posts as $post){
	    $response = wp_remote_get(get_permalink($post), $args);
        echo var_dump($ssgp_posts);

	    if(is_array($response)&&!is_wp_error($response)){
	        $filename = plugin_dir_path(__FILE__)."/out/".parse_url(get_permalink($post))[path]."/index.html";
		    $dirname = dirname($filename);
		    if (!is_dir($dirname))
		    {
			    mkdir($dirname, 0755, true);
		    }
		    $fp = fopen($filename, "w");
		    fwrite($fp, $response["body"]);
		    fclose($fp);
        }else{
            echo $response->get_error_message();
        }
    }

    wp_die();
}

function ssgp_register_settings(){
    register_setting('ssgp', 'ssgp_options');
    add_settings_section('ssgp_settings_crawler',
        __('Crawler', 'ssgp'),
        'ssgp_settings_crawler_callback',
        'ssgp'
    );

	add_settings_field(
		'ssgp_test',
		__( 'Test', 'ssgp' ),
		'wporg_field_pill_cb',
		'wporg',
		'wporg_section_developers',
		array(
			'label_for'         => 'wporg_field_pill',
			'class'             => 'wporg_row',
			'wporg_custom_data' => 'custom',
		)
	);
}

function ssgp_register_admin_menus() {
	$ssgp_topmenu_hook = add_menu_page(
		'SSGpress',
		'SSGpress',
		'manage_options',
		'ssgp',
		'ssgp_main_page_html'
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

function ssgp_main_page_html(){
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
	<?php
}

function ssgp_options_page_html(){
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
	<?php
}