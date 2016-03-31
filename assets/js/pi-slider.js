jQuery(document).ready(function($){
	$( '.pi-slider' ).each( function(){
		var sliderID = $(this).find('.slider-settings').data('id');
		var autoPlay = $(this).find('.slider-settings').data('autoplay');
		var pauseHover = $(this).find('.slider-settings').data('pausehover');
		var sliderSpeed = $(this).find('.slider-settings').data('slidespeed');
		var infinite = $(this).find('.slider-settings').data('infinite');
		var arrows = $(this).find('.slider-settings').data('arrows');
		var dots = $(this).find('.slider-settings').data('dots');
		var color = $(this).find('.slider-settings').data('color');
		var opacity = $(this).find('.slider-settings').data('opacity');
		var height = $(this).find('.slider-settings').data('height');
		var caption = $(this).find('.slider-settings').data('caption');

		$(this).css({
					'height' : height + 'px'
				});
		$('#pi-slider-' + sliderID).slick({
			autoplay: autoPlay,	
			dots: dots,
			arrows: arrows,
			infinite: infinite,
			centerMode: true,
			variableWidth: true,
			pauseOnHover: pauseHover,
			speed: sliderSpeed
		});
	});	

});