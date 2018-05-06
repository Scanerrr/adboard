$(document).ready(function() {
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('.ads-create input[type=file]').on('change', function() {
        $('div.gallery').empty();
        imagesPreview(this, 'div.gallery');
    });
    $('.ads-edit input[type=file]').on('change', function() {
        $('div.gallery').empty();
        imagesPreview(this, 'div.gallery');
    });

    $('.add_image').on('click', function() {
        $('.sub_images').fadeIn();
    });
});
