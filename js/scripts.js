(function ($, root, undefined) {
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
		var scrollPoint = getScrollPoint();
					
		$( window )
			.on('scroll', function(e) {

				if (!absolute && $(this).scrollTop() >= scrollPoint) {
					$('header').css(absoluteCss);
					$('.top-box-shadow, .bottom-box-shadow').addClass('no-box-shadow');
					
					absolute = !absolute;
					
				} else if (absolute && $(this).scrollTop() < scrollPoint) {
					$('header').css(fixedCss);
					$('.top-box-shadow, .bottom-box-shadow').removeClass('no-box-shadow');
					
					absolute = !absolute;
				}
			})
			.on('resize', function(
			
			){
				scrollPoint = getScrollPoint();
			});
		
	});

	function getScrollPoint()
	{
		var position = parseInt($('#content').css('marginTop')) - parseInt($('header').css('height'));

		absoluteCss.top = position + 'px';

		return position;
	}
	
})(jQuery, this);

