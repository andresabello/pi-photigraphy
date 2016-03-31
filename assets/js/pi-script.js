jQuery(document).ready(function($){
    //Simple Hover Effect
    $( ".pi-single" ).hover( function() {      
        if ($( this ).hasClass("slick-active") ) {
            $(this).find( ".pi-hover" ).stop().slideToggle('slow');
        }        
    });
    $( ".pi-view" ).click(function() {
        var id = $(this).data('id');
        var current = $('.pi-column').attr('class').split(' ')[1];
        var columns = $('.pi-column');
        
        $( columns ).each(function() {
            $(this).find('img').addClass('img-transition');
            $(this).removeClass( current, {duration:300} );
            $(this).addClass('pi-column col-' + id,  {duration:300} );
                               
        });
        // console.log( current );
    });
    
    /*Slider JQuery*/
    $( '.pi-slider' ).each( function(){
        var sliderID = $(this).find('.slider-settings').data('id');
        var autoPlay = $(this).find('.slider-settings').data('autoplay');
        var pauseHover = $(this).find('.slider-settings').data('pausehover');
        var sliderSpeed = $(this).find('.slider-settings').data('slidespeed');
        // var infinite = $(this).find('.slider-settings').data('infinite');
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
            infinite: true,
            centerMode: true,
            variableWidth: true,
            pauseOnHover: pauseHover,
            speed: sliderSpeed
        });
    }); 
});