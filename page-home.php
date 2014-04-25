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
						<div class="half text r">
							<h2>Portland's Premier Micro Wedding Venue</h2>
						</div>
						
						<div class="img-container half l"><img class="" src="wp-content/themes/avow/img/venue-1.jpg"></div>
					</div>
					<div class="row">					
						<div class="half text l">
							<h2>Elegant Design for Your Special Day</h2>
						</div>
						<div class="img-container half r"><img class="" src="wp-content/themes/avow/img/venue-2.jpg"></div>
					</div>
				</div>
			</section>
			
			<section id="reserve">
				<h1>Availability</h1>
				<div class="margin-standard">
					<h2>Reserve Your Time Now</h2>
					<div id="calendar"></div>
					
					<script>
						function bindCalEvent()
						{
							$('#calendar .events .event.available').off().on('click', function(){
								$ele = $(this);
								
								$('#calendar .events .event').removeClass('clicked');
								$ele.addClass('clicked')
								
								$('#reserve-button')								
									.removeClass('cupid-green')
									.addClass('clean-gray')
			                		.attr('value', $('#reserve-button').attr('data-title'))
			                	;
			                	
			                	$('#current-reservation').addClass('show');

							});
						}
						
						(function ($, root, undefined) { $(function () {
							var currentEventsDay = null;
							
							calendar = $('#calendar').clndr({
								template: $('#clndr-template').html(),
								weekOffset: 1,
								daysOfTheWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
								doneRendering: function(){
									$('#calendar .day:visible:not(.inactive, .adjacent-month)').eq(0).trigger('click')
									bindCalEvent();
									
									if (typeof(formData.reserve.date) == 'string') {
										$('.events-list .event[data-datetime="' + formData.reserve.date + '"]').addClass('clicked');
										
										var buttonText = moment(formData.reserve.date).format('ddd, MMM Do [at] h:mm a');
										$('#reserve-button').attr('data-selected-title', buttonText).val(buttonText + ' Selected');
										$('#current-reservation').html('Current Reservation: ' + buttonText);
									}
								},
								events: <?php 
									$start = date('Y-m-d', strtotime('+2 weeks')); 
									$end   = '2014-08-31';
									echo eventsForRange(array('Fri', 'Sat', 'Sun'), $start, $end) 
								?>,
								startWithMonth: '<?php echo $startMonth ?>',
								constraints: {
								    startDate: '<?php echo $start ?>',
								    endDate: '<?php echo $end ?>'
								},
								clickEvents: {
									onMonthChange: function(month) {
									
										
									},
									nextMonth: function(month) {
										$('#calendar .day:visible:not(.inactive, .adjacent-month)').eq(0).trigger('click')
									},
									previousMonth: function(month) {
										$('#calendar .day:visible:not(.inactive, .adjacent-month)').eq(0).trigger('click')
									},
									click: function(target) {
										var $ele = $(target.element);
										
										if ($ele.hasClass('adjacent-month') || $ele.hasClass('inactive')) {
											return;
										}
										
										var targetDate = $ele.attr('data-date');
										var $targetEvents = $('.event-' + targetDate);
										
										//don't hide/reshow the same day
										if (currentEventsDay == targetDate) {
											return;
										}
										
										currentEventsDay = targetDate;
										
										$('#calendar .day').removeClass('clicked');
										$ele.addClass('clicked');
										
										$('.events .event').css('display', 'none');
										$targetEvents.show();									
									}
								}
							});
						}) })(jQuery, this);
					</script>
					
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
									<div class="event event-<%= moment(event.date).format('YYYY-MM-DD') %> <%= event.day.toLowerCase() + moment(event.date).format('HH') %> <%= event.status %>"
										data-datetime="<%=  moment(event.date).format('YYYY-MM-DD HH:mm:00') %>"
									>
										<%= moment(event.date).format('h:mm a') %>
									</div>
								<% }); %>
							</div>
						</div>						
					</script>
					<form method="post" action="/backend" class="reserve-date" data-action="reserve">
						<input name="name1" class="name1 ghost required" data-ghost="You" value="You" type="text">
						<input name="name2" class="name2 ghost required" data-ghost="Your Partner" value="Your Partner" type="text">
						<input name="phone" class="phone ghost required" data-ghost="Phone" value="Phone" type="tel">
						<input name="email" class="email ghost required" data-ghost="Email" value="Email" type="text">
						
						
						<input type="submit" name="reserve_date" value="Select Your Date"
							id="reserve-button"
							class="<?php echo stepCompleted('reserve') ? 'cupid-green' : 'clean-gray' ?>"
							data-selected-title="Date Selected" 
							data-title="Select Your Date">
						
						<div id="current-reservation"></div>
							
						<input type="hidden" name="date" class="required" value="" id="reserve-date">
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
					$m = get_post_meta($p->ID);
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
						<form method="post" action="/backend" class="select-package" data-action="package">
							<input type="submit" name="package_title" value="Choose <?php echo $p->post_title ?>" 
								class="select-package <?php echo @$cookieData->package->package_name == $p->post_title ? 'cupid-green' : 'clean-gray' ?>"
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
							<div class="button-wrap"><button class="pay full cupid-green" data-pay-percent="100" data-button-text="Pay Full Amount">Pay Full Amount</button></div>
						</li>
						<li>
							<h2>Reservation Payment</h2>
							<p>
								Reserve your date with a payment of 20%. The full amount will be due when you arrive.
							</p>
							<div class="button-wrap"><button class="pay down-payment clean-gray" data-pay-percent="20" data-button-text="Pay 20%">Pay 20%</button></div>
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
