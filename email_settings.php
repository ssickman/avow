<?php
add_action('admin_init', 'add_email_settings');

function add_email_settings() {
	register_setting( 'general', 'email_settings', 'email_sanitize');
		
	add_settings_section('email_settings', 'Email Settings', function() { }, 'general');
	add_settings_field( 'email_smtp_server', 'SMTP Server', 'email_key', 'general', 'email_settings', array( 'id' => 'email_smtp_server' ) );
	add_settings_field( 'email_smtp_port', 'Port', 'email_key', 'general', 'email_settings', array( 'id' => 'email_smtp_port' ) );
	add_settings_field( 'email_smtp_protocol', 'Protocol', 'email_key', 'general', 'email_settings', array( 'id' => 'email_smtp_protocol' ) );
	add_settings_field( 'email_from', 'From Address', 'email_key', 'general', 'email_settings', array( 'id' => 'email_from' ) );
	add_settings_field( 'email_password', 'Password', 'email_key', 'general', 'email_settings', array( 'id' => 'email_password' ) );
	add_settings_field( 'email_replyto', 'Reply To', 'email_key', 'general', 'email_settings', array( 'id' => 'email_replyto'));
//	die();
}

function email_key($args){  
	$options = get_option('email_settings', '');
	$value = $options[$args['id']];

	echo "<input id='{$args['id']}' name='email_settings[{$args['id']}]' size='40' type='text' value='{$value}' />";
}

function email_sanitize($value) {
	

	return $value;
}