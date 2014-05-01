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
	
	//grap the data we passed from the stripe callback and 
	$token       = @$_POST['stripeToken'];
	$postAmount  = @$_POST['stripeAmount'];
	
	//setup test data if the wordpress enviornment is 'test' and we are passing ?testing=1
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
		$postAmount = 45000;
		
		$cookieData = new stdClass();
		$cookieData->reserve = $cookieData->package = new stdClass();
		
		$cookieData->package->package_id = 12;
		$cookieData->package->package_name = 'The Marquam';
		
		$cookieData->reserve->name1 = 'Steve';
		$cookieData->reserve->name2 = 'Andrea';
		$cookieData->reserve->phone = '3049339016';
		$cookieData->reserve->email = 'sickman+'.(rand(1,100)).'@gmail.com';
		$cookieData->reserve->date  = date('Y-m-d H:i:s'); 
		
		//print_r($cookieData); 
	}

	if (empty($cookieData->package->package_id)) {
		throw new MissingPackageId("You haven't selected a package");
	}
	
	$m = get_post_meta($cookieData->package->package_id);
	$amount = $m['package_price'][0] * 100;
	$prettyAmount = money_format('$%i', $postAmount / 100);

	if ($postAmount == $amount) {
		$paymentType = 'full';
	} elseif ($postAmount == 200) {
		$paymentType = '$200';
	} else {
		throw new MismatchedChargeAmount("There was a problem calculating the charge amount{$tryAgain}");
	}
	
	$charge = Stripe_Charge::create(array(
	  "amount" => $postAmount,
	  "currency" => "usd",
	  "card" => $token,
	  "description" => $cookieData->package->package_name,
	));
	
	$transactionId = $charge->id;
	
	book($cookieData, $paymentType, $transactionId);
	
	sendConfirmationEmail($cookieData->reserve->date);
	
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
	echo "Successfully charged {$prettyAmount} for {$cookieData->package->package_name}";
	
	
	
	if (!$testing) {
		setcookie('avow-form-data', "", -1);
	} 

} else {
	header('Location: /');
}


exit();