<?php  

/*
Template Name: Backend Template
*/
?>
<?php

$action = @$_POST['action'];

if ($siteEnvironment == 'test') {
	$action = $startingAction;	
}

$verified = wp_verify_nonce($_POST['nonce'], $action);


switch ($action) {
	case 'book':
		$nextAction = 'package';
		


		break;
	case 'package':
		$nextAction = 'charge';
		
		$_SESSION['package_id']   = $_POST['package_id'];
		$_SESSION['package_name'] = $_POST['package_name'];
		break;
		
	default:
}

$data = json_encode(array(
	'nonce' => array('value' => wp_create_nonce($nextAction), 'action' => $nextAction),
	'status' => 'ok',	
));

echo $data;
exit();