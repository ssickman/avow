(function ($, root, undefined) {
	
	var isMobile = function() {
	  return ('ontouchstart' in window)
	}();
	
	var bannerEle = '#banner';
	var bannerOverlap = 30;
	
	var absolute = false;
			
	$(function () {
		$('.nav a[href^=#], .banner-button, #checkout-steps a').on('click', function(e){
			e.preventDefault();
			var location = $(this).attr('href');
			var offset = -1 * ( parseInt($('.scrolled.reference').css('height')) /* + parseInt($('#checkout-steps').css('height')) */ );
			$.scrollTo(location, 500, { axis: 'y', offset: {top:  offset} });
			
			ga('send', 'pageview', location.replace('#', '/'));
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
			$('#checkout-steps > a.done + a.done + a').unbind();
			
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
		
		setContentMargin();
		selectPackage($('.select-package.cupid-green'));
		selectPackage($('form.reserve-date .cupid-green'));
		bindPayment();
		
		var scrollPoint = getScrollPoint();
		shrinkHeader(scrollPoint);
						
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
		$('#checkout-steps > a.done + a.done + a').bind('click', function(e){
        	$('.stripe-button-el').trigger('click');
        });
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
