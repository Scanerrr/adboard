$(() => {
    const imagesPreview = (input, placeToInsertImagePreview) => {
        const fileTypes = ['jpg', 'jpeg', 'png'];
        if (input.files) {
            const filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                const extension = input.files[0].name.split('.').pop().toLowerCase();  //file extension from input file
                const isSuccess = fileTypes.indexOf(extension) > -1;
                if (isSuccess) {
                    const reader = new FileReader();
                    placeToInsertImagePreview.empty();
                    reader.onload = event => {
                        $($.parseHTML('<img>')).attr({
                            src: event.target.result
                        }).appendTo(placeToInsertImagePreview);
                    }

                    reader.readAsDataURL(input.files[i]);
                } else {
                    alert('Файл должен иметь расширение [\'jpg\', \'jpeg\', \'png\']');
                }
            }
        }

    };

    $('#main_image').on('change', function (e) {
        const customFileUpload = $(this).prev().prev();
        imagesPreview(this, customFileUpload);
    });

    $('.add_image').on('click', (e) => {
        $('.additional_images').fadeIn();
        $(e.currentTarget).prop('disabled', true);
    });

    // $('.search').bind('typeahead:select', function(ev, suggestion) {
    //     $('#search-top').submit();
    // });

    $(document.body).on('click', '.get-subcategories', e => {
        const $this = $(e.currentTarget);
        getSubcategories($this.data('category_id'), $this.data('category_slug'), $this.text());
    });

    $('#category_name').on('click', () => {
        $.ajax({
            url: '/site/get-all-categories',
            method: 'POST',
            // data: { id: category_id },
            dataType: 'JSON',
            success: data => {
                if (data.success) {
                    const modal = $('#modal-categories .modal-body');
                    modal.empty();
                    modal.append('<div class="main-categories" id="modal-categories-content">' +
                        '<a href=""></a></div>');
                    const cats = modal.find('#modal-categories-content');
                    cats.append('<ul class="sub-categories-list"></ul>');
                    $.each(data.categories, (i, cat) => {
                        const image = cat.image ? cat.image : '/img/categories/default.png';
                        cats.find('ul').append(`<li>
                            <span class="category-thumbnail"><img src="${image}"></span>
                            <a class="get-subcategories" 
                            data-category_slug="${cat.slug}"
                            data-category_id ="${cat.id }"
                            data-href="/ads/category/${cat.slug}/">${cat.name}</a></li>`);


                    });

                    $('#modal-categories').modal();
                }
            }
        });
    });

    function getSubcategories(category_id, slug, text) {
        $.ajax({
            url: '/site/get-subcategories',
            method: 'POST',
            data: {id: category_id},
            dataType: 'JSON',
            success: data => {
                if (data.error) {
                    alert(data.message);
                }
                if (data.success) {
                    const modal = $('#modal-categories .modal-body');
                    modal.empty();
                    modal.append('<div id="modal-categories-content">' +
                        '<div id="modal-main-category" class="modal-main-category-div"><a href=""></a></div></div>');
                    modal.find('#modal-main-category').append('<ul id="second-level-categories" class="sub-categories-list"></ul>');
                    const subcats = modal.find('#second-level-categories');
                    $('#modal-main-category > a').attr('href', '/ads/category/' + slug).text('Смотреть все объявления в ' + text);
                    $.each(data.categories, (i, cat) => {
                        subcats.append('<li><a href="/ads/category/' + slug + '/' + cat.slug + '">' + cat.name + '</a></li>')
                    });
                    $('#modal-categories').modal();
                }
            }
        });
    }


    $('[data-target="#lightbox"]').on('click', function (event) {
        const $img = $(this).find('img'),
            src = $img.attr('src'),
            alt = $img.attr('alt'),
            $lightbox = $('#lightbox');
        $lightbox.find('img').attr('src', src);
        $lightbox.find('img').attr('alt', alt);
    });


    $(document.body).on('click', '.changeType', function () {
        $(this).closest('.phone-input').find('.type-text').text($(this).text());
        $(this).closest('.phone-input').find('.type-input').val($(this).data('type-value'));
    });

    $(document.body).on('click', '.btn-remove-phone', function () {
        $(this).closest('.phone-input').remove();
    });


    $('.btn-add-phone').click(() => {

        const index = $('.phone-input').length + 1;

        if (index > 3) return false;

        const phone_iput = $('.phone-list .phone-input').first().clone();
        const name = phone_iput.find('.type-input').attr('name');
        phone_iput.find('.type-input').attr('name', );
        phone_iput.find('input[type=text]').val('').after('<span class="input-group-btn">'+
            '<button style="margin-top: 5px;" class="btn btn-danger btn-remove-phone" type="button"><span class="glyphicon glyphicon-remove"></span></button>'+
            '</span>');

        $('.phone-list').append(phone_iput);

    });

    // TODO: icons preview
    $('.phone-list .type-input').on('change', function () {
        let icon = '';
        if ($(this).val() == 1) {
            icon = '<i class="fab fa-viber"></i> ';
        } else if ($(this).val() == 2) {
            icon = '<i class="fab fa-telegram"></i> ';
        } else {
            icon = '<i class="fab fa-whatsapp"></i> ';
        }
        $('.phone-type-icon').append(icon);
    })

});


