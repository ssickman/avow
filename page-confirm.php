<?php  

/*
Template Name: Confirm Template
*/
?>
<?php
$notFound = false;
$date  = @$_GET['date'];
$email = @$_GET['email'];
$forEmail = @isset($_GET['forEmail']);

if (empty($date) || empty($email)) {
	$notFound = true;
}

$event = getEvent($date, $email);
$package = $packages = array_pop(get_posts(array('post_id' => $event->package_id,)));
$m = get_post_meta($event->package_id);
	
if (empty($event)) {
	$notFound = true;
}

if ($notFound) {
	addFlash("We're sorry, we had trouble finding your confirmation.{$tryAgain}");
	header('Location: /');
}

//print_r($event);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<body>
<table style="font-family:helvetica,arial; font-weight:normal; color:#555; max-width:800px; margin:0 auto;" width="100%" cellspacing="0">
	<tr>
		<th style="font-weight:normal; font-size:45px; border-bottom:3px solid #eee; padding:10px 0;" cellspacing="10px" align="left" colspan="2">
			{avow}
		</th>
	</tr>
	<tr><td colspan="2" height="10">&nbsp;</td></tr>
	<tr>
		<td style="" colspan="2">
			<h2 style="margin-bottom:7px; font-size:24px;">Thank you for your reservation!</h2>
			<p style="font-size:120%; line-height:140%; margin: 0 0 40px;">
				Your ceremony is scheduled for <?php echo date('l F jS, Y @ h:i a', strtotime($event->date)) ?>
				<br />
			</p>
			
			<h3 style="margin-bottom:7px; font-size:20px;">On your day</h3>
			<p style="font-size:120%; line-height:140%; margin: 0 0 40px;">
				You should arrive 15 minutes early to ensure the setup is to your 
				liking and discuss any special additions you would like during your ceremony.
				<br />
			</p>
			
		<?php if ($event->payment_amount != 'full'): ?>
			<h3 style="margin-bottom:7px; font-size:20px;">Payment</h3>
			<p style="font-size:120%; line-height:140%; margin: 0 0 40px;">
				The remaining balance of <?php echo remainingBalance($event, $package, $m) ?> is due prior to the ceremony.
				<br />
			</p>					
		<?php endif; ?>

			<h3 style="margin-bottom:7px; font-size:20px;">Getting There</h3>
			<p style="font-size:120%; line-height:140%; margin: 0 0 40px;">
				{avow} is located centrally in downtown Portland on the 
				<a href="http://www.trimet.org/schedules/maxgreenline.htm">MAX Green</a> and 
				<a href="http://www.trimet.org/schedules/maxblueline.htm">Blue</a> lines. 
				Additionally, the <a href="http://www.trimet.org/schedules/r017.htm">17</a>, 
				<a href="http://www.trimet.org/schedules/r020.htm">20</a>, and 
				<a href="http://www.trimet.org/schedules/r014.htm">14</a> bus lines all have stops within a couple blocks.
				<br />
			</p>
		</td>
	</tr>
	<tr><td colspan="2" height="1" style="border-top:3px solid #eee;">&nbsp;</td></tr>
	<tr style="font-weight:normal; font-size:18px; border-top:3px solid #eee; padding:10px 0;">
		<td align="left"><span style="position:relative; top:-15px;">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></span></td>
		<td  align="right">
			<span style="position:relative; top:-23px;"><span style="color:#D4AF37; font-size:200%; position:relative; top:4px;">&#x2764;</span> for all</span>
		</td>
	</tr>
</table>
</body>