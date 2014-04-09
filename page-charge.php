<?php  

/*
Template Name: Charge Template
*/
?>
<?php
require_once('stripe-php-1.12.0/lib/Stripe.php');
$stripeKeyIndex = "stripe_{$siteEnvironment}_secret_key";

$secretKey = get_stripe_key($stripeKeyIndex);

Stripe::setApiKey($secretKey);

// Get the credit card details submitted by the form
$token = @$_POST['stripeToken'];


// Create the charge on Stripe's servers - this will charge the user's card
try {
	$charge = Stripe_Charge::create(array(
	  "amount" => 1000, // amount in cents, again
	  "currency" => "usd",
	  "card" => $token,
	  "description" => "payinguser@example.com")
	);
	
	
} catch(Stripe_CardError $e) {
  // The card has been declined
} catch(Stripe_InvalidRequestError $e) {

} 

header('Location: /');
exit();