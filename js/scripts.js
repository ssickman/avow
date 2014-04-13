(function() {  if (!window.console) {    window.console = {};  }  var m = [    "log", "info", "warn", "error", "debug", "trace", "dir", "group",    "groupCollapsed", "groupEnd", "time", "timeEnd", "profile", "profileEnd",    "dirxml", "assert", "count", "markTimeline", "timeStamp", "clear"  ];  for (var i = 0; i < m.length; i++) {    if (!window.console[m[i]]) {      window.console[m[i]] = function() {};    }      } })();
(function ($, root, undefined) {
	
	var isMobile = function() {
	  return ('ontouchstart' in window)
	}();
	
	var bannerEle = '#banner';
	var bannerOverlap = 30;
	
	var absolute = false;
			
	$(function () {
		$('.nav a[href^=#], .banner-button, #checkout-steps a').on('click', function(e){
			
			var location = $(this).attr('href');
			scrollTo(location, e);
		});
		
		$('.home-scroll').on('click', function(e){
			e.preventDefault();
			
			$.scrollTo( 0, 500, { 'axis':'y', offset: {top: -1 * parseInt($('.scrolled.reference').css('height')) } } );
			
			ga('send', 'pageview', '/');
			
		});
		
		$('.flash a').on('click', function(e){
			e.preventDefault();
			$(this).parent().css('display', 'none').fadeOut(1000);
		});
		
		$('form.select-package, form.reserve-date').on('submit', function(e){
			e.preventDefault();
			var $form = $(this);
			
			//do not allow payment while options are changing
			//$('#checkout-steps > a.done + a.done + a').unbind();
			$('#charge').addClass('hidden');
			
			$.ajax({
	            type: "POST",
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            dataType: "json",
	            success: function(data) {
	                console.log(data);
					
					if (data.status == 'ok') {
		                
		                if ($form.hasClass('select-package')) {
			                $('form.select-package input[type=submit]').each(function(ele, i){
			                	$(this)
			                		.removeClass('cupid-green')
			                		.addClass('clean-gray')
			                		.attr('value', $(this).attr('data-title'))
			                	;
			                });
		                }
		                
		                $('#checkout-steps')
		                	.removeClass('hidden')
		                	.slideDown(700)
		                		.find('a.' + $form.find('input[name=action]')
		                			.val())
		                			.addClass('done')
		                ;
		               
		                selectPackage($form.find('input[type=submit]'));
		                
		                bindPayment();
		                
	               	} else {
	               		addFlash(data.flash.errorMessage, data.flash.errorClass);
	               	}
	            },
	            error: function(){
	                 addFlash('There was a problem connecting to the server.<br><br>Please try again', 'warning');
	            }
	        });
		})
		
		
		selectPackage($('.select-package.cupid-green'));
		selectPackage($('form.reserve-date .cupid-green'));
		bindPayment();
		
		var scrollPoint = getScrollPoint();
		shrinkHeader(scrollPoint);
		
		$('#home-banner').on('load', function(){
			setContentMargin();
		});
						
		$( window )
			.on('scroll', function(e) {
				shrinkHeader(scrollPoint);
			})
			.on('resize', function() {
				setContentMargin();
				scrollPoint = getScrollPoint(parseInt($(bannerEle).css('height')));
				
			})
		;
		
		controlFlash();
		
		var currentEventsDay = null;
		$('#calendar').clndr({
			template: $('#clndr-template').html(),
			weekOffset: 1,
			daysOfTheWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			events: [
				{ date: '2014-04-18 18:00', status: 'available' },
				{ date: '2014-04-18 20:00', status: 'available' },
				{ date: '2014-04-19 10:00', status: 'available' },
				{ date: '2014-04-19 12:00', status: 'available' },
				{ date: '2014-04-19 14:00', status: 'available' },
				{ date: '2014-04-19 16:00', status: 'reserved' },
				{ date: '2014-04-19 18:00', status: 'booked' },
				{ date: '2014-04-19 20:00', status: 'available' },
			],
			clickEvents: {
				onMonthChange: function(month) {
					currentEventsDay = null;
				},
				click: function(target) {
					var $ele = $(target.element);
					var targetDate = $ele.attr('data-date');
					var $targetEvents = $('.event-' + targetDate);
					var slideDuration = 150;
					
					//don't hide/reshow the same day
					if (currentEventsDay == targetDate) {
						return;
					}
					
					currentEventsDay = targetDate;
					
					$('#calendar .day').removeClass('clicked');
					$ele.addClass('clicked');
					
					
					if (screenIs(['small', 'medium'])) {  console.log('1');
						$('.events').slideUp(slideDuration, function(){
							$('.events .event').css('display', 'none');
							
							if ($targetEvents.length > 0) {
								$targetEvents.show();
								$('.events').slideDown(slideDuration, function(){
									
								}); 
								scrollTo('#calendar');
							}	
						});
					} else {
						$('.events').css('width', '0px');
						$('.events .event').css('display', 'none');
						if ($targetEvents.length > 0) {
							$targetEvents.show();
							$('.events').css('width', '315px');
						}
					}
				}
			}
		});
		
	});
	
	function bindPayment()
	{
		var $pay = $('#checkout-steps > a.done + a.done + a');
		
		if ($pay.length > 0){
			
			$pay.on('click', function(e){
				scrollTo($(this).attr('href'), e);
			});

			
			setupPaymentButtons();

			/*
		
			$pay.on('click', function(e){
		    	var $selectedPackage = $('input.select-package.cupid-green');
				stripeHandler.open({
					name: 'Avow',
					description: $selectedPackage.attr('data-package-name'),
					amount: $selectedPackage.parent().find('input[name=package_price]').val() * 100
				});
				e.preventDefault();
		    });
		*/
		    
		    $('#charge').removeClass('hidden');
		} else {
			$('#checkout-steps > a:last-child').off().on('click', function(e){
				e.preventDefault();
			});
		}
	}
	
	function setupPaymentButtons()
	{
		var $selectedPackage = $('input.select-package.cupid-green');
		
		$('#charge button.pay').each(function(i, ele){
			var payPercent = $(this).attr('data-pay-percent');
			var amount = $selectedPackage.parent().find('input[name=package_price]').val();
			var amountStripe = amount * payPercent;
			var amountPretty = '$' + (amount * payPercent / 100);
			
			$(this)
				.html($(this).attr('data-button-text') + ' (' + amountPretty + ')')
				.on('click', function(e){
			    	$('button.pay').removeClass('chosen-payment-method');
			    	$(this)
			    		.addClass('chosen-payment-method')
			    		.attr('data-stripe-amount', amountStripe);
			    	;
			    	
					stripeHandler.open({
						name: 'Avow',
						description: $selectedPackage.attr('data-package-name'),
						amount: amountStripe
					});
					e.preventDefault();
			    });
		})
	}
	
	function scrollTo(location, e)
	{
		if ((e)) {
			e.preventDefault();
		}
		
		var offset = -1 * ( parseInt($('.scrolled.reference').css('height'))  + parseInt($('#checkout-steps').outerHeight()) );
		$.scrollTo(location, 400, { axis: 'y', offset: {top:  offset} });
		
		ga('send', 'pageview', location.replace('#', '/'));
	}
	
	function shrinkHeader(scrollPoint)
	{
		if (!absolute && $(this).scrollTop() >= scrollPoint - bannerOverlap) {
			$('.top-header').addClass('scrolled');
			absolute = !absolute;
			
		} else if (absolute && $(this).scrollTop() < scrollPoint - bannerOverlap) {
			$('.top-header').removeClass('scrolled');
			absolute = !absolute;
		}
	}
	
	function selectPackage($ele) 
	{
		$ele.removeClass('clean-gray')
	    	.addClass('cupid-green')
	    	.attr('value', $ele.attr('data-selected-title'))
	    ;
	}
	
	function setContentMargin()
	{
		$('main.homepage > section:nth-child(2)').css('marginTop', (parseInt($(bannerEle).css('height')) + parseInt($(bannerEle).css('top'))) + 'px');
	}
	
	function getScrollPoint()
	{	
	
		var offset = parseInt($('.scrolled.reference').css('height'));
		offset = 0;
		
		var position = parseInt($(bannerEle).css('height')) - offset;

		return position;
	}
	
})(jQuery, this);

function postForm(action, method, input) 
{
    "use strict";
    var form;
    form = jQuery('<form />', {
        action: action,
        method: method,
        style: 'display: none;'
    });
    if (typeof input !== 'undefined') {
        jQuery.each(input, function (name, value) {
            jQuery('<input />', {
                type: 'hidden',
                name: name,
                value: value
            }).appendTo(form);
        });
    }
    form.appendTo('body').submit();
}

function addFlash(errorMessage, errorClass) 
{
	jQuery('.flash').addClass(errorClass).find('div').html(errorMessage);
	controlFlash();
}

function controlFlash() {
	$('.flash').hide();
	if ($('.flash > div').html().length > 0) { 
		$('.flash').show().delay(10000).fadeOut(1000); 
	}

}

function screenIs(className) 
{
	if (typeof(className) == 'string') {
		return jQuery('.' + className + '-screen').css('display') == 'block';
	} else {
		for (var i = 0, length = className.length; i < length; i++) {
			if (jQuery('.' + className[i] + '-screen').css('display') == 'block') {
				return true;
			}
		}
	}
}
