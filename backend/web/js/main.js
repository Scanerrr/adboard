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

    $('.custom-form input[type=file]').on('change', function(e) {
        const customFileUpload = $(this).prev().prev();
        imagesPreview(this, customFileUpload);
    });

    $('.add_image').on('click', (e) => {
        $('.additional_images').fadeIn();
        $(e.currentTarget).prop('disabled', true);
    });
});
