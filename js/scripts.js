(function ($, root, undefined) {
	
	var isMobile = function() {
	  return ('ontouchstart' in window)
	}();
	
	var bannerEle = '#banner';
	
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
		$('nav a[href^=#]').on('click', function(e){
			e.preventDefault();
			
			$.scrollTo( $(this).attr('href'), 850, { 'axis':'y' } );
			
		});
		
		setContentMargin();
		console.log($(bannerEle).css('height'));
		console.log($('#banner').css('height'));

		var scrollPoint = getScrollPoint();
							
		$( window )
			.on('scroll', function(e) {

				if (!isMobile && !absolute && $(this).scrollTop() >= scrollPoint) {
					//$('header').css(absoluteCss);
					$('header').addClass('scrolled');
					absolute = !absolute;
					
				} else if (!isMobile && absolute && $(this).scrollTop() < scrollPoint) {
					//$('header').css(fixedCss);
					$('header').removeClass('scrolled');
					
					absolute = !absolute;
				}
			})
			.on('resize', function() {
				setContentMargin();
				scrollPoint = getScrollPoint(parseInt($(bannerEle).css('height')));
				
			});
		
	});
	
	function setContentMargin()
	{
		$('main.homepage > section:nth-child(2)').css('marginTop', parseInt($(bannerEle).css('height')) + parseInt($('header').css('height')) + 'px');
	}
	
	function getScrollPoint()
	{	
	
		var offset = parseInt($('.scrolled.reference').css('height'));

		var position = parseInt($(bannerEle).css('height')) + offset;

		absoluteCss.top = position + 'px';
console.log(position);
		return position;
	}
	
})(jQuery, this);

