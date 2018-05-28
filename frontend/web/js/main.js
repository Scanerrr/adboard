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

    $('.search').bind('typeahead:select', function(ev, suggestion) {
        $('#search-top').submit();
    });

    $('.get-subcategories').on('click', function () {
        var $this = $(this);
        var category_id = $this.data('category_id');
        var slug = $this.data('category_slug');
        $.ajax({
            url: '/site/get-subcategories',
            method: 'POST',
            data: { id: category_id },
            dataType: 'JSON',
            success: function (data) {
                if (data.error) {
                    alert(data.message);
                }
                if (data.success) {
                    var modal = $('#modal-subcategories');
                    $('#modal-main-category > a').attr('href', '/site/' + slug).text('Смотреть все объявления в ' + $this.text());
                    $.each(data.categories, function (i, cat) {
                        modal.find('#second-level-categories').append('<li><a href="/site/' + slug + '/' + cat.slug +'">'+ cat.name +'</a></li>')
                    });
                    modal.modal();
                }
            }
        });

    });
});
