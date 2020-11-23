<?php


function ssgp_ajax_build(){
	check_ajax_referer('ssgp_build', 'nonce');

	// TODO async
	// TODO different file
	// TODO cache
	// TODO DB queue

	$ssgp_posts = get_posts(array('numberposts'=>-1));
	$args = array(
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
	}

	wp_die();
}

add_action('wp_ajax_ssgp_build', 'ssgp_ajax_build');
