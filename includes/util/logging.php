<?php

function ssgp_log($run, $message){
	global $wpdb;
	$ssgp_logging_query = "INSERT INTO {$wpdb->prefix}ssgp_log (`run`, `message`) VALUES (%d, %s)";
	$wpdb->query($wpdb->prepare($ssgp_logging_query, array($run, $message)));
}