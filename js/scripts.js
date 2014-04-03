(function ($, root, undefined) {
	
	 isMobile = function() {
	  return ('ontouchstart' in window)
	}();
	
	var absolute = false;
	var absoluteCss = {
		position: 'absolute',
		top: '469px'
	};
	
	var fixedCss = {
		position: 'fixed',
		top: '0'
	};
		
	$(function () {
		setContentMargin();
		
		console.log($('#home-banner').css('height'));
		
		var scrollPoint = getScrollPoint();
							
		$( window )
			.on('scroll', function(e) {

				if (!isMobile && !absolute && $(this).scrollTop() >= scrollPoint) {
					$('header').css(absoluteCss);
					$('.top-box-shadow, .bottom-box-shadow').addClass('no-box-shadow');
					
					absolute = !absolute;
					
				} else if (!isMobile && absolute && $(this).scrollTop() < scrollPoint) {
					$('header').css(fixedCss);
					$('.top-box-shadow, .bottom-box-shadow').removeClass('no-box-shadow');
					
					absolute = !absolute;
				}
			})
			.on('resize', function(
			
			){
				setContentMargin();
				scrollPoint = getScrollPoint(parseInt($('#home-banner').css('height')));
			});
		
	});
	
	function setContentMargin()
	{
		$('#packages').css('marginTop', parseInt($('#home-banner').css('height')) + parseInt($('header').css('height')) + 'px');
	}
	
	function getScrollPoint()
	{	
		var position = parseInt($('#home-banner').css('height'));

		absoluteCss.top = position + 'px';

		return position;
	}
	
})(jQuery, this);

