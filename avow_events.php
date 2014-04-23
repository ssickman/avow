<?php
add_action( 'admin_head', 'add_menu_icons_styles' );

add_action('admin_menu', function(){

	add_menu_page('Booked Events', 'Events', 'read', 'avow_events', function(){
		echo 'whatup !?';
	}, 'dashicons-calendar', '26.0001');
});