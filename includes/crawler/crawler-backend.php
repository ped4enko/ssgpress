<?php


function ssgp_ajax_build(){
	check_ajax_referer('ssgp_build_start', 'nonce');


	global $wpdb;

	include_once dirname(__FILE__).'/../util/logging.php';

	// TODO async
	// TODO different file
	// TODO cache
	// TODO DB queue

	/*$args = array(
		'timeout' => 20,
		'sslverify' => false,
		'user-agent' => 'ssgp/0.0.1'
	);
	foreach($ssgp_posts as $post){

		$response = wp_remote_get(get_permalink($post), $args);

		if(is_array($response)&&!is_wp_error($response)){
			$filename = plugin_dir_path(__FILE__)."/../out/".parse_url(get_permalink($post))[path]."/index.html";
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
	}*/

	$ssgp_run = $wpdb->get_var("SELECT COALESCE(MAX(run), 0) as `last_run` FROM {$wpdb->prefix}ssgp_log") + 1;

	ssgp_log($ssgp_run, "Starting build process.");

	$ssgp_posts = get_posts(array('numberposts'=>-1));
	$queue = array();

	$ssgp_query ="INSERT INTO {$wpdb->prefix}ssgp_queue (`run`, `url`) VALUES ";
	$ssgp_values = array();
	$ssgp_placeholders = array();

	array_push($ssgp_values, $ssgp_run, get_site_url());
	$ssgp_placeholders[] = "(%d, %s)";

	foreach ($ssgp_posts as $post){
		array_push($ssgp_values, $ssgp_run, get_permalink($post));
		$ssgp_placeholders[] = "(%d, %s)";
	}

	$ssgp_query .= implode(', ', $ssgp_placeholders);
	$wpdb->query($wpdb->prepare($ssgp_query, $ssgp_values));
	wp_die();
}

add_action('wp_ajax_ssgp_build_start', 'ssgp_ajax_build');
