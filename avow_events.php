<?php add_action('init', function(){ ob_start(); }, 0);
add_action( 'admin_head', 'add_menu_icons_styles' );

add_action('admin_menu', function(){

	add_menu_page('Booked Events', 'Events', 'read', 'avow_events', function(){
		global $avow_events_table;
		global $wpdb;
		
		if (!empty($_POST['data'])) {
			foreach ($_POST['data'] as $p) {
				if (!empty($p['date'])) {
					$query = $wpdb->prepare("
						UPDATE {$avow_events_table} 
						SET 
							name1 = '%s',
							name2 = '%s',
							phone = '%d',
							email = '%s'
						WHERE date = '%s'",
						$p['name1'], $p['name2'], $p['phone'], $p['email'], $p['date']
					);
					$wpdb->query($query);
				}
			}
			
			header("Location: ".$_SERVER['REQUEST_URI']);
		}
		
		echo "<link rel='stylesheet' href='/wp-content/themes/avow/avow-admin.css' type='text/css' media='all' >";
		
		
		$eventsQuery = "SELECT a.*, IF(date > now(), 'current', 'past') as class from {$avow_events_table} a order by date asc";
		$events = $wpdb->get_results($eventsQuery);
		
		if (empty($events)) {
			?>
				<h2>No Events Booked</h2>
			<?php
			return;
		} else {
			?>
				<h2>Booked Events</h2>
			<?php				
		}
		
		?>
		<ul class="events-list">
		<form action="" method="post">
		<?php $i = 0;
		foreach ($events as $e):
		?>
			<li class="<?php echo $e->class ?>">
				<span class="event-date"><?php echo date('m/d/Y g a', strtotime($e->date)) ?> </span>
				<input type="text" name="data[<?php echo $i ?>][name1]" value="<?php echo $e->name1 ?>" >
					&amp; 
				<input type="text" name="data[<?php echo $i ?>][name2]" value="<?php echo $e->name2 ?>" >
				<input type="text" name="data[<?php echo $i ?>][phone]" value="<?php echo $e->phone ?>" >
				<input type="text" name="data[<?php echo $i ?>][email]" value="<?php echo $e->email ?>" >
				<input type="hidden" name="data[<?php echo $i ?>][date]" value="<?php echo $e->date ?>" >
				<input type="hidden" name="data[<?php echo $i ?>][update]" value="1" >
			</li>
		<?php $i++;
		endforeach;
		?>
		</ul>
			<input type="submit" class="button button-primary button-large" value="update events" >
		</form>

		
		
		<?php
	}, 'dashicons-calendar', '26.0001');
});

function getBookedDates($after = null) 
{
	if (is_null($after)) {
		$after = date('Y-m-d 00:00:00', strtotime('+13 days'));
	}
	
	global $wpdb;
	global $avow_events_table;

	$events = $wpdb->get_results($wpdb->prepare("SELECT date from {$avow_events_table} WHERE date > '%s' and status = 'booked'", $after));
	
	$unavailable = array();
	foreach ($events as $e) {
		$unavailable[] = $e->date;
	}
	
	return $unavailable;
}

function checkAvowDate($date)
{
	global $wpdb;
	global $avow_events_table;

	$dateCheck = $wpdb->get_results($wpdb->prepare("SELECT 1 from {$avow_events_table} WHERE date = '%s' and status IN ('booked', 'unavailable', 'reserved')", $date));

	return empty($dateCheck);

}

function book($data, $paymentType, $transactionId) 
{
	global $wpdb;
	global $avow_events_table;
	
	if (checkAvowDate($data->reserve->date)) {
		$wpdb->insert($avow_events_table, array(
			'name1'  => $data->reserve->name1,
			'name2'  => $data->reserve->name2,
			'phone'  => $data->reserve->phone,
			'email'  => $data->reserve->email,
			'date'   => $data->reserve->date,
			'status' => 'booked',
			'payment_amount' => $paymentType,
			'package_id'     => $data->package->package_id,
			'package_name'   => $data->package->package_name,
			'transaction_id' => $transactionId,
		));
	} else {
		throw new UnavailableDate();
	}
}

function sendConfirmationEmail($date)
{
	$event = getEvent($date);
	$package = $packages = array_pop(get_posts(array('post_id' => $event->package_id,)));
	$m = get_post_meta($event->package_id);
	
	//print_r($package); print_r($m); print_r($event); 
	
	
	list($mailer, $message) = getMailer();
	
	$message
		->setSubject('Your Wedding Date Confirmation')
		->setBody('blhaala lsjf;aslj fks')
		->setTo(array('info@avowpdx.com', $event->email));
	;
	
	$mailer->send($message);
	
	return true;
}

function getEvent($date)
{
	global $avow_events_table;
	global $wpdb;
	
	return array_pop($wpdb->get_results($wpdb->prepare("SELECT * from {$avow_events_table} WHERE date = '%s' LIMIT 1", $date)));
}

function createEventsTable()
{
	global $wpdb;
	global $avow_events_table;
	
	$sql =
	 "CREATE TABLE $avow_events_table (
date datetime NOT NULL,
name1 varchar(255)  NOT NULL,
name2 varchar(255)  NOT NULL,
email varchar(100)  NOT NULL,
phone varchar(15)   NOT NULL,
status enum('available','booked','unavailable','reserved') DEFAULT 'available',
package_id mediumint(9),
package_name varchar(255) NOT NULL,
transaction_id varchar(50),
payment_type enum('credit card','bitcoin','dogecoin') DEFAULT 'credit card',
payment_amount enum('$200', 'full') NOT NULL,
PRIMARY KEY  (date)
	);";
	
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}
add_action('admin_init', 'createEventsTable');