(function ($, root, undefined) {
	
	var isMobile = function() {
	  return ('ontouchstart' in window)
	}();
	
	var bannerEle = '#banner';
	var bannerOverlap = 30;
	
	var absolute = false;
			
	$(function () {
		$('nav a[href^=#]').on('click', function(e){
			e.preventDefault();
			var location = $(this).attr('href');
			$.scrollTo( location, 500, { 'axis':'y', offset: {top: -1 * parseInt($('.scrolled.reference').css('height')) } } );
			
			ga('send', 'pageview', location.replace('#', '/'));
		});
		
		$('#logo.scrollto').on('click', function(e){
			e.preventDefault();
			
			$.scrollTo( 0, 500, { 'axis':'y', offset: {top: -1 * parseInt($('.scrolled.reference').css('height')) } } );
			
			ga('send', 'pageview', '/');
			
		});
		
		$('form.select-package').on('submit', function(e){
			e.preventDefault();
			var $form = $(this);
			$.ajax({
	            type: "POST",
	            url: $(this).attr('action'),
	            data: $(this).serialize(),
	            dataType: "json",
	            success: function(data) {
	                console.log($form.find('input[type=submit]'));
	                
	                console.log(data);

	                $('form input[type=submit]').each(function(ele, i){
	                	$(this)
	                		.removeClass('cupid-green')
	                		.addClass('clean-gray')
	                		.attr('value', $(this).attr('data-package-title'))
	                	;
	                });
	                
	                $form.find('input[type=submit]')
	                	.removeClass('clean-gray')
	                	.addClass('cupid-green')
	                	.attr('value', $form.find('input[type=submit]').attr('data-selected-package-title'))
	                ;
	            },
	            error: function(){
	                  console.log('error');
	            }
	        });
		})
		
		setContentMargin();
		
		var scrollPoint = getScrollPoint();
							
		$( window )
			.on('scroll', function(e) {

				if (!absolute && $(this).scrollTop() >= scrollPoint - bannerOverlap) {
					$('header').addClass('scrolled');
					absolute = !absolute;
					
				} else if (absolute && $(this).scrollTop() < scrollPoint - bannerOverlap) {
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

