<?php
function ssgp_drop_options(){
	delete_option('ssgp_options');
}

function ssgp_drop_db(){
	global $wpdb;

	$sql = "DROP TABLE IF EXISTS {$wpdb->prefix}ssgp_queue";
	$wpdb->query($sql);

}