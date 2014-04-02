(function ($, root, undefined) {
	
	$(function () {
		var triggerPoint = getTriggerPoint();
		var absolute = false;
		var absoluteCss = {
			position: 'absolute',
			top: '456px'
		};
		
		var fixedCss = {
			position: 'fixed',
			top: '0'
		};
		
		$( window ).scroll(function(e) {
			if (!absolute && $(this).scrollTop() >= triggerPoint) {
				$('header').css(absoluteCss);
				$('.top-box-shadow, .bottom-box-shadow').addClass('no-box-shadow');
				
				absolute = !absolute;
				
			} else if (absolute && $(this).scrollTop() < triggerPoint) {
				$('header').css(fixedCss);
				$('.top-box-shadow, .bottom-box-shadow').removeClass('no-box-shadow');
				
				absolute = !absolute;
			}
		});
		
		'use strict';
		
		// DOM ready, take it away
		
	});
	
})(jQuery, this);

function getTriggerPoint()
{
	return 463;
}