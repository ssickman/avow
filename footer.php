			<!-- footer -->
			<footer class="footer" role="contentinfo">
				<div class="inner">
					<p class="copyright">
						&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> 
					</p>
					<span class="for-all"><span class="heart">&#x2764;</span> for all</span>
				</div>
			</footer>
			<!-- /footer -->

		</div>
		<!-- /wrapper -->

		<?php 
			wp_footer(); 
			
			
			$siteEnvironment = get_stripe_key('stripe_environment'); 
			
			$cookieData = getCookieData();

			
			$minMonth = $startMonth = null;
		?>
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
                	
                	if ($('#current-reservation').html().trim().length > 5) {
	                	$('#current-reservation').addClass('show');
	                }

				});
			}
			
			(function ($, root, undefined) { $(function () {
				var currentEventsDay = null;
				var initialCalClick = true;
				
				calendar = $('#calendar').clndr({
					template: $('#clndr-template').html(),
					weekOffset: 1,
					daysOfTheWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
					doneRendering: function(){
						$('#calendar .day:visible:not(.inactive, .adjacent-month)').eq(0).trigger('click')
						bindCalEvent();
						
						if (formData.hasOwnProperty('reserve') && formData.reserve != null) {
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

							if (!initialCalClick) {
								$('#reserve-button')								
									.removeClass('cupid-green')
									.addClass('clean-gray')
			                		.attr('value', $('#reserve-button').attr('data-title'))
			                	;
			                		
			                	$('#current-reservation').addClass('show');
				                
								
							} initialCalClick = false;
							
							$ele.addClass('clicked');
							
							$('.events .event').css('display', 'none');
							$targetEvents.show();									
						}
					}
				});
				
			}) })(jQuery, this);
		</script>
		
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


		<!-- analytics -->
		<script>
		(function(f,i,r,e,s,h,l){i['GoogleAnalyticsObject']=s;f[s]=f[s]||function(){
		(f[s].q=f[s].q||[]).push(arguments)},f[s].l=1*new Date();h=i.createElement(r),
		l=i.getElementsByTagName(r)[0];h.async=1;h.src=e;l.parentNode.insertBefore(h,l)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-47493736-1', {cookieDomain: window.location.hostname == 'local.avowpdx.com' ? 'none' : 'avowpdx.com'});
		ga('send', 'pageview');
		</script>
		
		<div class="screen-container">
			<div class="small-screen"></div>
			<div class="medium-screen"></div>
			<div class="large-screen"></div>
			<div class="xlarge-screen"></div>
		</div>
	
	</body>
</html>
