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
	$postAmount  = @$_POST['stripeAmount'];
	$packageId   = @$_SESSION['package_id'];

	if (empty($packageId)) {
		throw new MissingPackageId("You haven't selected a package");
	}
	
	$m = get_post_meta($packageId);
	$amount = $m['package_price'][0] * 100;
	$prettyAmount = money_format('$%i', $postAmount / 100);
	$packageName = $_SESSION['package_name'];
	
	if (!($postAmount == $amount || $postAmount == $amount * .2)) {
		throw new MismatchedChargeAmount("There was a problem calculating the charge Amount{$tryAgain}");
	}

	$charge = Stripe_Charge::create(array(
	  "amount" => $postAmount,
	  "currency" => "usd",
	  "card" => $token,
	  "description" => $packageName,
	));
	
	$success = true;
	
} catch(Stripe_CardError $e) {
  // The card has been declined
  echo __LINE__ . ' ' . $e->getMessage();
} catch(Stripe_InvalidRequestError $e) {
	addFlash("We had a problem processing your credit card.{$tryAgain}");
} catch(MissingPackageId $e) {
	addFlash($e->getMessage());
} catch(MismatchedChargeAmount $e) {
	addFlash($e->getMessage());
}

if ($success) {
	echo "Successfully charged {$prettyAmount} for {$packageName}";
	
	unset(
		$_SESSION['package_id'], 
		$_SESSION['package_name'],
		$_SESSION['completed_steps']
	);

} else {
	header('Location: /');
}


exit();