<?php  

/*
Template Name: Backend Template
*/
?>
<?php
$errors = array(
	'reserve'    => "We had an issue reserving your date.{$tryAgain}",
	'package' => "We had an issue selecting your package{$tryAgain}",
);

$data = array();

$action = @$_POST['action'];
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';


if ($siteEnvironment == 'test') {

}

$verified = @wp_verify_nonce($_POST['nonce'], $action);
$success = false;

if ($verified && 1==1) {
	
	switch ($action) {
		case 'reserve':
			
			break;
		case 'package':
			
			$_SESSION['package_id']   = $_POST['package_id'];
			$_SESSION['package_name'] = $_POST['package_name'];
			break;
			
		default:
	}
	
	completeStep($action);
	$success = true;
	
} else {
	$nextAction = $action;
	
	if ($isAjax) {
		$data['flash'] = array('errorMessage' => $errors[$action], 'errorClass' => 'warning');
	} else {
		addFlash($errors[$action]);
		header('Location: /');
		exit();
	}
}
$data = json_encode(array_merge($data, array(
	'status' => $success ? 'ok' : 'error',	
)));

echo $data;
exit();