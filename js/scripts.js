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
			$('.flash').stop();
			
			$(this).parent().css('display', 'none').fadeOut(1000);
		});
		
			
		$('form.reserve-date input').not('input[type=submit]')
			.on('focus', function(e){
				var $this = $(this);
				if ($this.val() == $this.attr('data-ghost')) {
					$this.val('');
				}

				$this.removeClass('ghost').removeClass('required-visual');
			})
			.on('blur', function(e){
				var $this = $(this);
				if ($this.val().trim().length === 0) {
					$this.val($this.attr('data-ghost')).addClass('ghost');
				}
			})
			.on('keydown', function(e){
				 formData = JSON.parse(readCookie('avow-form-data', "{}"));

	            formData['reserve'] = $('form.reserve-date').serializeObject();
	            createCookie('avow-form-data', JSON.stringify(formData), .042);
	
			})
			.each(function(i, ele){
				var $this = $(ele);
				if ($this.val() != $this.attr('data-ghost')) {
					$this.removeClass('ghost');
				}
			});
		
		$('form.select-package, form.reserve-date').on('submit', function(e){
			e.preventDefault();
			
			var $form = $(this);
			var formAction = $form.attr('data-action');
			
			if (formAction == 'reserve') {
				$('#reserve-date').val($('.events-list .event.clicked').attr('data-datetime'));
				
				$('.flash').stop();
				
				var validForm = true;
				
				$('form.reserve-date input.required').each(function(i, ele){
					var $this = $(ele);
					if ($this.val().trim().length < 2 || $this.val() == $this.attr('data-ghost')) {
						
						//scroll to the first blank (and visible) field 
						if (validForm) {

							//flash the error if the date hasn't been selected
							if ($this.attr('name') == 'date') {
								addFlash('Please choose a date &amp; time', 2000);
							} else {
								if (jQuery(window).scrollTop() > $this.offset().top - 40) {
									$.scrollTo($this, 200, { 'axis':'y', offset: {top: -60}});
								}
							}
						}						
						
					
						//shake the text label
						var shakeTime = 75;
						$this
							.stop()
							.delay(250)
							.animate({ paddingLeft: '20px' }, shakeTime)
							.animate({ paddingLeft: '06px' }, shakeTime)
							.animate({ paddingLeft: '20px' }, shakeTime)
							.animate({ paddingLeft: '06px' }, shakeTime)
							.animate({ paddingLeft: '20px' }, shakeTime)
							.animate({ paddingLeft: '06px' }, shakeTime)
							//.animate({ paddingLeft: '20px' }, shakeTime)
							//.animate({ paddingLeft: '06px' }, shakeTime)
							.addClass('required-visual')
						;
						
						validForm = false;
					}
				});
				
				if (!validForm) {
					return;
				}
				
				var buttonText = moment($('.events-list .event.clicked').attr('data-datetime')).format('ddd, MMM Do [at] h:mm a');
				$('#reserve-button').attr('data-selected-title', buttonText + ' Selected');
				$('#current-reservation').html('Current Reservation: ' + buttonText).removeClass('show');
				
			}
			
			//revert all the packages
			if ($form.hasClass('select-package')) {
                $('form.select-package input[type=submit]').each(function(i, ele){
                	$(this)
                		.removeClass('cupid-green')
                		.addClass('clean-gray')
                		.attr('value', $(this).attr('data-title'))
                	; 
                });
            }
            
            //now select new package
            selectPackage($form.find('input[type=submit]'));
            
            formData = JSON.parse(readCookie('avow-form-data', "{}"));

            formData[formAction] = $form.serializeObject();
            createCookie('avow-form-data', JSON.stringify(formData), .042);
                       
            $('#checkout-steps')
            	.removeClass('hidden')
            	.slideDown(700)
            		.find('a.' + formAction)
            			.addClass('done')
            ;
            
            bindPayment();

            scrollPoint = getScrollPoint();
		})
		
		
		selectPackage($('.select-package.cupid-green'));
		selectPackage($('form.reserve-date .cupid-green'));
		bindPayment();
		
		var scrollPoint = getScrollPoint();
		shrinkHeader(scrollPoint);
		

		setContentMargin();

						
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
	});
	
	function bindPayment()
	{
		var $pay = $('#checkout-steps > a.done + a.done + a');
		
		if ($pay.length > 0){
			
			$pay.on('click', function(e){
				scrollTo($(this).attr('href'), e);
			});

			
			setupPaymentButtons();
		    
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
			var $this = $(this);
			var payType = $this.attr('data-pay-type');
			var amount = $selectedPackage.parent().find('input[name=package_price]').val();
			var amountStripe = payType == 'full' ? amount * 100 : 20000;
			var buttonText = $this.attr('data-button-text') + (payType == 'full' ? ' $' + amount : '');
			console.log(buttonText);
			$this
				.off()
				.html(buttonText)
				.on('click', function(e){
			    	$('button.pay').removeClass('chosen-payment-method');
			    	$(this)
			    		.addClass('chosen-payment-method')
			    		.attr('data-stripe-amount', amountStripe);
			    	;
			    	
					stripeHandler.open({
						name: 'Avow',
						description: $selectedPackage.attr('data-package-name'),
						amount: amountStripe,
						email: $('form.reserve-date input.email').val()
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
	
	var $highest = 0;
	function setContentMargin()
	{
		$('main.homepage > section:nth-child(2)').css('marginTop', (parseInt($(bannerEle).css('height')) + parseInt($(bannerEle).css('top'))) + 'px');
		
		//and also set package heights
		var $topRow = $('.package-1, .package-2, .package-3');
		if (screenIs('large', 'xlarge')) {
			
			var hi = 0;
			
			if (!$highest) {
				$topRow.not('.chosen').each(function(){
				  var h = $(this).height();
				  if(h > hi){
				     hi = h;
				     $highest = $(this);  
				  }    
				});
			}
			
			$topRow.not($highest).css('height', $highest.height());	
		} else if (screenIs('small', 'medium')) {
			$topRow.attr('style', '');
		}
	}
	
	function getScrollPoint()
	{	
	
		var offset = $('#checkout-steps').outerHeight(true);
		
		var position = parseInt($(bannerEle).css('height')) - offset + 30;

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

function addFlash(errorMessage, delay) 
{
	jQuery('.flash')/* .addClass(errorClass) */.find('div').html(errorMessage);
	controlFlash(delay);
}

function controlFlash(delay) {
	delay = delay || 10000;
	
	$('.flash').stop().hide();
	
	if ($('.flash > div').html().length > 0) { 
		$('.flash')
			.fadeIn(200, function(){
				$('.flash > a').focus();
			})
			.delay(delay).fadeOut(1000); 
	}

}

function screenIs(className) 
{
	if (arguments.length == 1) {
		return jQuery('.' + className + '-screen').css('display') == 'block';
	} else {
		for (var i = 0, length = arguments.length; i < length; i++) {
			if (jQuery('.' + arguments[i] + '-screen').css('display') == 'block') {
				return true;
			}
		}
	}
}

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}

function readCookie(name, defaultValue) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    
    if (typeof(defaultValue) != 'undefined') {
	    return defaultValue;
    }
    
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

var formData = JSON.parse(readCookie('avow-form-data', "{}"));