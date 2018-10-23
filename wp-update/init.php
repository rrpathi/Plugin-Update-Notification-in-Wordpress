<?php
/*
Plugin Name: Plugin Update
Plugin URI: https://github.com/rrpathi/Event-Tracking-Wordpress-Plugin
Description: The complete list of the WordPress Update Notification.
Version: 2.0.1
Author: Ragupathi
Author URI: https://github.com/rrpathi
License:     GPLv2 
*/


	add_filter('site_transient_update_plugins', 'push_update' );
	function push_update($transient){
		$plugin_slug = basename(dirname(__FILE__)).'/'.basename(__FILE__);
		$localplugin_version =  $transient->checked[$plugin_slug];
		// Remote Url
		// $url = plugin_dir_url(__FILE__).'info.json';
		$url = 'http://localhost/info.json';
		$server_data = wp_remote_get( $url);	
		$latest_plugin_version = json_decode($server_data['body']);
		$server_plugin_version = $latest_plugin_version->version;
		if($server_plugin_version >$localplugin_version){
			$res = new stdClass();
			$res->slug = $latest_plugin_version->slug;
			$res->new_version = $latest_plugin_version->version;
			// $res->plugin = $plugin_slug;
			$res->package = $latest_plugin_version->download_url;
			$transient->response[$plugin_slug] = $res;
			return $transient;
		}else{
			return $transient;
		}
	}

// echo Error