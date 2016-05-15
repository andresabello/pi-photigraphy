jQuery(document).ready(function($){
    $('#demo-theme').on('click', function(e){
        e.preventDefault();

        var me = $(this);
        if ( me.data('requestRunning') ) {
            return;
        }

        me.data('requestRunning', true);
        var currentFile = 0;
        var total = $( '#total-files' ).val();
        createListings( currentFile, total );
    });
    function createListings( number, total ){
        var data = {
            action: 'create_listings',
            currentFile: number,
            nonce: pi_import_ajax.nonce
        };
        $.ajax({
            type: 'POST',
            url: pi_import_ajax.ajaxURL,
            data: data,
            timeout: 0,
            success: function( response, textStatus, XMLHttpRequest ) {
                if( response.data === 'done' ){
                    alert(response.data);
                    location.reload();
                }else{
                    createListings(response.data, total);
                    $('#current-file').val(response.data);

                    percent = (response.data / total) * 100;
                    $( '#print-current' ).text( percent.toFixed(2) + '%' );
                    $( '#progress-bar' ).find('.percent').animate({
                        "width": percent.toFixed(2) + "%",
                        "padding": "0 2px"
                    }, 300 );
                    $( '#progress-bar' ).find('.percent').text( parseInt(percent) + "%");
                    $('#status-number').text(response.data);
                    // console.log( percent.toFixed(2) + '%' );
                }
                console.log(response.data);

            },
            error: function( MLHttpRequest, textStatus, errorThrown ) {
                alert( pi_import_ajax.failMessage + errorThrown );
            },
            complete: function() {
            }
        });
    }
});
