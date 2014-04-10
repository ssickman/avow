<?php  

/*
Template Name: Charge Template
*/
?>
<?php
require_once('stripe-php-1.12.0/lib/Stripe.php');

$success = false;

try {
	$stripeKeyIndex = "stripe_{$siteEnvironment}_secret_key";

	$secretKey = get_stripe_key($stripeKeyIndex);
	
	Stripe::setApiKey($secretKey);
	
	$token       = @$_POST['stripeToken'];
	$packageId   = @$_SESSION['package_id'];


	if (empty($packageId)) {
		throw new MissingPackageId("You haven't selected a package");
	}
	
	$m = get_post_meta($packageId);
	$amount = $m['package_price'][0] * 100;
	$prettyAmount = money_format('$%i', $amount / 100);
	$packageName = $_SESSION['package_name'];
	
	$charge = Stripe_Charge::create(array(
	  "amount" => $amount,
	  "currency" => "usd",
	  "card" => $token,
	  "description" => $packageName,
	));
	
	$success = true;
	
} catch(Stripe_CardError $e) {
  // The card has been declined
  echo __LINE__ . ' ' . $e->getMessage();
} catch(Stripe_InvalidRequestError $e) {
  addFlash('We had a problem processing your credit card.<br><br>Please try again or give us a call at 503.555.5555');
} catch(MissingPackageId $e) {
	addFlash($e->getMessage());
}

if ($success) {
	echo "Successfully charged {$prettyAmount} for {$packageName}";
	
	unset(
		$_SESSION['package_id'], 
		$_SESSION['package_name']
	);
}
exit();
header('Location: /');
exit();