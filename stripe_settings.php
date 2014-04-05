<?php
add_action('admin_init', 'add_stripe_settings');

function add_stripe_settings() {
	register_setting( 'general', 'stripe_settings', 'stripe_sanitize');
		
	add_settings_section('stripe_settings', 'Stripe Settings', function() { }, 'general');
	add_settings_field( 'stripe_test_secret_key', 'Test Secret Key', 'stripe_key', 'general', 'stripe_settings', array( 'id' => 'stripe_test_secret_key' ) );
	add_settings_field( 'stripe_test_public_key', 'Test Public Key', 'stripe_key', 'general', 'stripe_settings', array( 'id' => 'stripe_test_public_key' ) );
	add_settings_field( 'stripe_production_secret_key', 'Production Secret Key', 'stripe_key', 'general', 'stripe_settings', array( 'id' => 'stripe_production_secret_key' ) );
	add_settings_field( 'stripe_production_public_key', 'Production Public Key', 'stripe_key', 'general', 'stripe_settings', array( 'id' => 'stripe_production_public_key' ) );
	
//	die();
}

function stripe_key($args){  
	$options = get_option('stripe_settings', ''); 
	@$value = $options[$args['id']];
	echo "<input id='{$args['id']}' name='stripe_settings[{$args['id']}]' size='40' type='text' value='{$value}' />";
}

function stripe_sanitize($value) {
	return $value;
}