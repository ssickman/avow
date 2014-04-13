<?php
/*
Template Name: Homepage Template
*/
?>
<?php get_header(); ?>

	<main role="main" class="homepage">
		<section id="banner" class="fixed">
			<h3 class="banner-text">
				<span>Get hitched on the quick</span>
				<a class="button banner-button button-shadow" href="#reserve">book now ></a>
			</h3>
			<img id="home-banner" src="wp-content/themes/avow/img/homepage.jpg">
		</section>
		<section id="content-wrap">
			<section id="venue">
				<h1>The Venue</h1>
				<div class="margin-standard">
					<div class="row">
						<h2 class="half r">Portland's Premier Micro Wedding Venue</h2>
						<div class="img-container half l"><img class="" src="wp-content/themes/avow/img/venue-1.jpg"></div>
					</div>
					<div class="row">					
						<h2 class="half l">Elegant Design for Your Special Day</h2>
						<div class="img-container half r"><img class="" src="wp-content/themes/avow/img/venue-2.jpg"></div>
					</div>
				</div>
			</section>
			
			<section id="reserve">
				<h1>Availability</h1>
				<div class="margin-standard">
					<h2>Reserve Your Time Now</h2>
					<div id="calendar"></div>
					<script id="clndr-template" type="text/template">						
						<div class="days">
							<div class="controls">
								<div class="clndr-previous-button"><span>&lsaquo;</span></div><div class="month"><%= month %>&nbsp;<%= year %></div><div class="clndr-next-button"><span>&rsaquo;</span></div>
							</div>
							<div class="headers">
								<% _.each(daysOfTheWeek, function(day) { %><div class="<%= day.toLowerCase() %>  day-header"><%= day %></div><% }); %>
							</div>
							<% _.each(days, function(day) { %>
								<div 
									data-day="<%= moment(day.date).format('dddd').toLowerCase() %>" 
									data-date="<%= moment(day.date).format('YYYY-MM-DD') %>" 
									class="<%= day.classes %> <%= moment(day.date).format('ddd').toLowerCase() %>" 
									id="<%= day.id %>">
										<%= day.day %>
								</div><% }); 
							%>
						</div>
						<div class="events">
							<h3>Available Times</h3>
							<div class="events-list">
								<% _.each(eventsThisMonth, function(event) { %>
									<div class="event event-<%= moment(event.date).format('YYYY-MM-DD') %> <%= event.status %>"
										data-datetime="<%=  moment(event.date).format('YYYY-MM-DD HH:mm:00') %>"
									>
										<%= moment(event.date).format('h:mm a') %>
									</div>
								<% }); %>
							</div>
						</div>						
					</script>
					<form method="post" action="/backend" class="reserve-date">
						<input type="submit" name="reserve_date" value="Reserve Your Date" style="margin:20px auto; display:block;"
							class="<?php echo stepCompleted('reserve') ? 'cupid-green' : 'clean-gray' ?>"
							data-selected-title="Date Reserved" 
							data-title="Reserve Your Date">
							
						<input type="hidden" name="date" value="2014-05-28 19:00:00" >
						<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('reserve') ?>" >
						<input type="hidden" name="action" value="reserve" >
					</form>	
				</div>
			</section>
			
			<section id="package" class="top-box-shadow">	
				<h1>Packages</h1>
				<?php
					$packages = get_posts(array(
						'post_type' => 'package',
						'meta_key'  => 'package_price',
						'orderby'   => 'meta_value_num',
						'order'     => 'ASC',
					));				
				?>
				
				<ul id="">
				<?php 
					foreach ($packages as $p): 
					$m = get_post_meta($p->ID); //print_r($meta); die();
				?>
					<li class="padding-standard">
						<h2><?php echo $p->post_title ?></h2>
						<h3><?php echo $m['package_price'][0] ?></h3>
						
						<p>
							<?php echo $p->post_content ?>
						</p>
						
						<ul class="features">
							<?php echo package_format_features($m['package_features'][0]) ?>
						</ul>
						<form method="post" action="/backend" class="select-package">
							<input type="submit" name="package_title" value="Choose <?php echo $p->post_title ?>" 
								class="select-package <?php echo $_SESSION['package_name'] == $p->post_title ? 'cupid-green' : 'clean-gray' ?>"
								data-selected-title="<?php echo $p->post_title ?> Selected" 
								data-title="Choose <?php echo $p->post_title ?>"
								data-package-name="<?php echo $p->post_title ?>">
							<input type="hidden" name="package_name" value="<?php echo $p->post_title ?>" >
							<input type="hidden" name="package_id" value="<?php echo $p->ID ?>" >
							<input type="hidden" name="package_price" value="<?php echo $m['package_price'][0] ?>" >
							<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('package') ?>" >
							<input type="hidden" name="action" value="package" >
						</form>	
					</li>	
						
				<?php endforeach; ?>
				</ul>
			</section>
			
			<section id="charge" class="<?php echo !(showStepBar() && stepCompleted('reserve') && stepCompleted('package')) ? 'hidden' : '' ?>">
				<h1>Payment</h1>
				<?php //wp_stripe_form(); ?>
				<div class="margin-standard">					
					<ul id="payment-choices">
						<li>
							<h2>Full Amount</h2>
							<p>
								Pay the full amount today and only worry about how good you're gonna look on your special day.
							</p>
							<button class="pay full cupid-green" data-pay-percent="100" data-button-text="Pay Full Amount">Pay Full Amount</button>
						</li>
						<li>
							<h2>Reservation Payment</h2>
							<p>
								Reserve your date with a payment of 20%. The full amount will be due when you arrive.
							</p>
							<button class="pay down-payment clean-gray" data-pay-percent="20" data-button-text="Pay 20%">Pay 20%</button>
						</li>
					</ul>
					<script>
						var stripeHandler = StripeCheckout.configure({
							key: '<?php echo get_stripe_key("stripe_{$siteEnvironment}_public_key") ?>',
							image: '//avowpdx.com/wp-content/themes/avow/img/avow-stripe.jpg',
							token: function(token, args) {
								document.body.style.cursor = 'wait';
								
								postForm('/charge', 'POST', { stripeToken: token.id, stripeAmount: jQuery('button.chosen-payment-method').attr('data-stripe-amount') });
								console.log(token);
								console.log(args);
							}
						});
					</script>
					</div>
			</section>
		</section>
	</main>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>
