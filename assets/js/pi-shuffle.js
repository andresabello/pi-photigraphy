jQuery(document).ready(function($){
    var $grid = $('#grid');

    $grid.shuffle({
        speed: 500,
        easing: 'ease-in-out',
        itemSelector: '.portfolio-item',
        delimeter: ',',
        initialSort: 'all'
    });

    $('.portfolio-sorting').on('change', function (e) {
        e.preventDefault();
        //get option selected
        var option = $("option:selected", this);
        // get group name from clicked item
        var groupName = option.attr('data-group');
        // set active class
        $('.portfolio-sorting').find('.active').removeClass('active');
        option.addClass('active');

        // reshuffle grid
        $grid.shuffle('shuffle', groupName );
    });

    //Hover Title and Tags
    $( ".portfolio-item" ).hover( function() {
        $(this).find(".portfolio-item__details").stop().slideToggle('slow');
    });

});