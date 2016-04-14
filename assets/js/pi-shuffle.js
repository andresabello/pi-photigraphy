jQuery(document).ready(function($){
    var $grid = $('#grid');

    $grid.shuffle({
        itemSelector: '.portfolio-item' // the selector for the items in the grid
    });

    $('.portfolio-sorting a').click(function (e) {
        e.preventDefault();

        // set active class
        $('.portfolio-sorting a').removeClass('active');
        $(this).addClass('active');

        // get group name from clicked item
        var groupName = $(this).attr('data-group');

        // reshuffle grid
        $grid.shuffle('shuffle', groupName );
    });
});