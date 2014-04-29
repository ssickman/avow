<?php  

/*
Template Name: Charge Template
*/
?>
<?php
require_once('stripe-php-1.12.0/lib/Stripe.php');

$success = false;

try {

	$siteEnvironment = get_stripe_key('stripe_environment'); 

	$stripeKeyIndex = "stripe_{$siteEnvironment}_secret_key";

	$secretKey = get_stripe_key($stripeKeyIndex);
	Stripe::setApiKey($secretKey);
	
	
	$token       = @$_POST['stripeToken'];
	$postAmount  = @$_POST['stripeAmount'];
	$packageId   = @$cookieData->package->package_id;

	$testing = false;
	if (isset($_GET['testing']) && $_GET['testing'] && $siteEnvironment == 'test') {
		$token = json_decode(Stripe_Token::create(array(
		  "card" => array(
		    "number" => "4242424242424242",
		    "exp_month" => 4,
		    "exp_year" => 2015,
		    "cvc" => "314"
		  )
		)))->id;
		$testing = true;
		$postAmount = 150000;
	}

	if (empty($packageId)) {
		throw new MissingPackageId("You haven't selected a package");
	}
	
	$m = get_post_meta($packageId);
	$amount = $m['package_price'][0] * 100;
	$prettyAmount = money_format('$%i', $postAmount / 100);

	$packageName = $cookieData->package->package_name;

	if ($postAmount == $amount) {
		$payAmount = 'full';
	} elseif ($postAmount == 200) {
		$payAmount = '$200';
	} else {
		throw new MismatchedChargeAmount("There was a problem calculating the charge amount{$tryAgain}");
	}

	$charge = Stripe_Charge::create(array(
	  "amount" => $postAmount,
	  "currency" => "usd",
	  "card" => $token,
	  "description" => $packageName,
	));
	
	book($cookieData, $payAmount);
	
	$success = true;
	
} catch (Stripe_CardError $e) {
	// The card has been declined
	//echo __LINE__ . ' ' . $e->getMessage();
	addFlash("We had a problem processing your credit card.{$tryAgain}");
} catch (Stripe_InvalidRequestError $e) {
	addFlash("We had a problem processing your credit card.{$tryAgain}");
} catch (MissingPackageId $e) {
	addFlash($e->getMessage());
} catch (MismatchedChargeAmount $e) {
	addFlash($e->getMessage());
} catch(UnavailableDate $e) {
	unset($cookieData->reserve->date);
	
	setCookie('avow-form-data', json_encode($cookieData), time()+60*30, '/');
	
	addFlash("We're sorry. The date you chose is unavailable.{$tryAgain}");
}

if ($success) {
	echo "Successfully charged {$prettyAmount} for {$packageName}";
	
	
	
	if (!$testing) {
		setcookie('avow-form-data', "", -1);
	} 

} else {
	header('Location: /');
}


exit();