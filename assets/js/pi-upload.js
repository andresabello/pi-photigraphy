jQuery(document).ready(function($){
    $('.upload').click(function(e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
            .on('select', function(e){
                // This will return the selected image from the Media Uploader, the result is an object
                var uploadedImage = image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier
                // Output to the console uploaded_image
                console.log(uploadedImage);
                var imageUrl = uploadedImage.toJSON().url;
                // Let's assign the url value to the input field
                $('.text-upload').val(imageUrl);
                $('.preview-upload').attr( "src", imageUrl );
            });
    });
});