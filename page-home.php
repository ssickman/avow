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
			<img id="home-banner" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABXgAAAMxCAMAAABmUiubAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2hpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYxIDY0LjE0MDk0OSwgMjAxMC8xMi8wNy0xMDo1NzowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0iMDE5RTVBOUY3NTlFOUEwNzJFRDkxM0ZDRTE2MTU3ODUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MzA2RTgzNzdDMzZEMTFFM0EyMENFNjVFNERDQ0JBRkYiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MzA2RTgzNzZDMzZEMTFFM0EyMENFNjVFNERDQ0JBRkYiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIExpZ2h0cm9vbSAzLjYgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NTc5N0JGMjZCNkVBMTFFM0IyNDBBRTgzNkVCRDU3QjYiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NTc5N0JGMjdCNkVBMTFFM0IyNDBBRTgzNkVCRDU3QjYiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4QIpjfAAAABlBMVEX///8AAABVwtN+AAAEcElEQVR42uzBMQEAAADCoPVPbQwfoAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAziaAAAMAeCgAASkgQLUAAAAASUVORK5CYII=">
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
						<input name="name1" class="name1 ghost required" data-ghost="You" value="<?php echo reserveFormValue('name1', 'You') ?>" type="text">
						<input name="name2" class="name2 ghost required" data-ghost="Your Partner" value="<?php echo reserveFormValue('name2', 'Your Partner') ?>" type="text">
						<input name="phone" class="phone ghost required" data-ghost="Phone" value="<?php echo reserveFormValue('phone', 'Phone') ?>" type="tel">
						<input name="email" class="email ghost required" data-ghost="Email" value="<?php echo reserveFormValue('email', 'Email') ?>" type="text">
						
						
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
					$packageCount = 0;
					foreach ($packages as $p): 
					$m = get_post_meta($p->ID);
				?>
					<li class="padding-standard <?php echo strtolower(str_replace(' ', '-', $p->post_title)) ?> package-<?php echo ++$packageCount ?>">
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
				<div>					
					<ul id="payment-choices">
						<li class="padding-standard">
							<h2>Full Amount</h2>
							<p>
								Pay the full amount today and only worry about how good you're gonna look on your special day.
							</p>
							<div class="button-wrap"><button class="pay full cupid-green" data-pay-type="full" data-button-text="Pay Full Amount">Pay Full Amount</button></div>
						</li>
						<li class="padding-standard">
							<h2>Reservation Payment</h2>
							<p>
								Reserve your date with a payment of $200. The full amount will be due when you arrive.
							</p>
							<div class="button-wrap"><button class="pay down-payment clean-gray" data-pay-type="partial" data-button-text="Pay $200">Pay $200</button></div>
						</li>
					</ul>
					
					</div>
			</section>
		</section>
	</main>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>
