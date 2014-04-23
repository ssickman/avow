<?php
add_action( 'admin_head', 'add_menu_icons_styles' );

add_action('admin_menu', function(){

	add_menu_page('Booked Events', 'Events', 'read', 'avow_events', function(){
		global $avow_events_table;
		global $wpdb;
		
		$eventsQuery = "SELECT a.*, IF(date > now(), 'current', 'past') as class from {$avow_events_table} a order by date asc";
		$events = $wpdb->get_results($eventsQuery);
		
		if (!empty($_POST)) {
			print_r($_POST);
		}
		
		if (empty($events)) {
			?>
				<h2>No Events Booked</h2>
			<?php
			return;
		}
		
		?>
		<ul>
		<?php
		foreach ($events as $e):
		?>
			<li class="<?php echo $e->class ?>"><?php echo date('m/d/Y g a', strtotime($e->date)) ?> <?php echo "{$e->name1} &amp; {$e->name2}" ?> <?php echo $e->phone ?> <?php echo $e->email ?>
		<?php
		endforeach;
		?>
		</ul>
		
		<form action="" method="post">
			<input type="hidden" name="test" value="blah">
			<input type="submit" >
		</form>
		
		<?php
	}, 'dashicons-calendar', '26.0001');
});