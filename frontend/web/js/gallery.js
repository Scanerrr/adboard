$(function () {

    $(document).on('click','#show-gallery-html', function(e) {
        e.preventDefault();
        var _this = $(this);
        var galleryPlaceArea = _this.closest('#galleryPlaceArea');
        galleryPlaceArea.find('#fileUploadGallery').hide();
        galleryPlaceArea.find('#photosDefault').show();
        galleryPlaceArea.find('.tooltip-gallery-bottom').hide();
    });

    $('input[type=file]').on('change', function(){
        var reset = $('#fileupload'+this.id).show();
        reset.on('click', function(){
            var idfile = parseInt(this.id.replace(/\D+/g,""));
            var inputval = $('#'+idfile);
            inputval['0'].value = '';
            $(this).hide();
        });
    });

});


function setPreviewPhoto() {
    if ($('#sortable').find('.photo-preview a').length == 0) {
        if (!$('#samplePhoto').hasClass('no_img'))
            $('#samplePhoto').addClass('no_img').find('img').remove();
        return;
    }
    $('#countImages').html($('.photo-preview.in').length);
    $('#samplePhoto').removeClass('no_img').html($('#sortable').find('.photo-preview:first-child a').html()).promise().done(function (e) {
        window.images_status--;
        if (window.images_status <= 0) {
            $('label[for="sortable"]').hide();
            $('#galleryPlaceArea p').hide();
            window.images_status = 0;
        }
    });
}
function gallerySort() {
    $('#fileUploadGallery #sortable').find('.photo-preview.in').sort(function (a, b) {
        var contentA = parseInt($(a).attr('data-position'));
        var contentB = parseInt($(b).attr('data-position'));
        return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
    }).prependTo('#fileUploadGallery #sortable');
}
$(document).on('click', '#customeWrapper .btn-submit', function () {
    $(this).closest('#customerWrapper').fadeOut(400, function () {
        $(this).remove();
    });
});
$(document).on('click', '#customeWrapper', function (e) {
    if (e.target = this) {
        $(this).fadeOut(400, function () {
            $(this).remove();
        });
    }
});
$(document).keydown(function (e) {
    if (e.keyCode == 27 && $('#customeWrapper').length) {
        $('#customeWrapper').fadeOut(400, function () {
            $(this).remove();
        });
    }
})
function customerAlert(innerText) {
    yii.confirm(innerText,function () {}, function () {});
}
$(document).ready(function () {
    setPreviewPhoto();
    gallerySort();
    var globalFileUpload = document.getElementById('fileUpload').cloneNode();
    setTimeout(function () {
        $('#sortable.gallery-sortable').sortable({
            items: '.photo-preview',
            deactivate: function (event, ui) {
                setPreviewPhoto();
            }
        });
    }, 100);

    //что бы не загруженные ячейки при сортировке всегда были последними
    $(".gallery-sortable").on("sortupdate", function (event, ui) {
        var posCounter = 1;
        $('#fileUploadGallery #sortable').find('.photo-preview.in').each(function () {
            $(this).attr('data-position', posCounter++);
        });
        gallerySort();
    });

    // установливаем фотку в первую ячейку
    $('#fileUploadGallery').on('click', '.btn-set-cover', function (e) {
        e.preventDefault();
        $(this).closest('.photo-preview').prependTo('#fileUploadGallery #sortable');
        setPreviewPhoto();
    });

    $('#fileUploadGallery').on('click', '.btn-delete', function (e) {
        var parent = $(this).parent();
        var filename = $(this).attr('data-name');
        if ($(this).hasClass('disabled')){
            return;
        }

        $(this).addClass('disabled');
        var data = {
            'filename': filename,
            'id': parent.attr('data-id')
        };
        $.ajax({
            url: '/ads/uploaddelete',
            type: 'POST',
            data: data,
            success: function () {
                window.images_status--;
            }

        });

        parent.attr('data-id', 0);
        parent.removeAttr('data-id', 0);
        parent.removeClass('in');

        if (!$('.photo-preview').hasClass('in')) {
            $('.gallery-block .label-photo').removeClass('tick');
        }
        parent.html('<span class="fa-stack fa-2x"> <i class="fa fa-camera fa-stack-2x"></i> <i class="fa fa-circle fa-stack-1x"></i> <i class="fa fa-plus fa-stack-1x"></i> </span>');
        parent.appendTo('#fileUploadGallery .gallery-sortable');
        setPreviewPhoto();

        e.preventDefault();
    });

    //перенаправление клика с ячейки на кнопку файла
    $('.gallery-sortable').on('click', '.photo-preview:not(.in)', function (e) {
        if ($(e.target).is('button')){
            return;
        }
        $('#fileUpload').click();
    });

    window.images_status = 0;

    $(document).on('change', '#fileUpload', function () {

        var form = document.getElementById('galleryPlaceArea');
        var data = new FormData();

        var inputs = form.querySelectorAll('input');
        var count_files = this.files.length;
        var counter = 1;
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type != 'file') {
                data.append(inputs[i].name, inputs[i].value);
            }
        }


        var $set = $('.photo-preview:not(.in)');

        for (var i = 0; i < this.files.length; i++) {
            var count = document.querySelectorAll('.photo-preview.in').length;
            if (this.files[i].size > 5242880) {
                customerAlert(document.getElementById('error_1').innerHTML);
                counter++;
                this.files[i].name = '';
                continue;
            }


            if (this.files[i].type.search(/(gif|jpe?g|png)$/) == -1) {
                if (this.files[i].name.search(/(\.gif|\.jpe?g|\.png)$/) == -1) {
                    customerAlert(document.getElementById('error_2').innerHTML);
                    counter++;
                    this.files[i].name = '';
                    continue;
                }

            }
            if ((i + 1 + count) > 10) {
                customerAlert(document.getElementById('error_3').innerHTML);
                counter++;
                this.files[i].name = '';
                break;
            }

            var $photo_previw = $('.photo-preview:not(.in)');
            $photo_previw.not('.proccess').first().find('span').hide();
            $photo_previw.not('.proccess').first().addClass('proccess');
            var tmpData = data;
            tmpData.append('image', this.files[i]);

            var cur_iteration = $set.index($photo_previw[0]);

            $.ajax({
                url: "/ads/upload",
                dataType: 'json',
                type: 'POST',
                processData: false,
                contentType: false,
                data: tmpData,
                current_iteration: cur_iteration,
                beforeSend: function () {
                    window.images_status++;
                },
                success: function (result) {
                    var first = $('.photo-preview:not(.in)').eq(this.current_iteration);
                    first.removeClass('proccess');
                    first.show('span');
                    if (result.error > 0) {
                        first.find('span > i').css('display','');
                        var text = document.getElementById('error_' + result.error).innerHTML;
                        customerAlert(text);
                    } else {
                        if (!$('.photo-preview:not(.in)').length) {
                            customerAlert(document.getElementById('error_3').innerHTML);
                            return;
                        }
                        first.attr('data-id', 0);
                        var container = document.createElement('div');
                        container.className = 'photo-container';
                        var a = document.createElement('div');
                        //a.href= result.url;
                        //a.setAttribute('target', '_blank');
                        var img = new Image();
                        img.src = result.url;

                        first.find('input').val(result.name);
                        first.append(container);
                        container.appendChild(a).appendChild(img);

                        var btnDel = document.createElement('a');
                        btnDel.className = 'btn-delete delete';
                        btnDel.setAttribute('data-name', result.name);

                        first.append(btnDel);

                        var value = document.createElement('input');
                        value.type = 'hidden';
                        value.value = 0;
                        first.append(value);

                        value = document.createElement('input');
                        value.type = 'hidden';
                        value.name = "files[]";
                        value.value = result.name;
                        first.append(value);

                        first.addClass('in');
                        $('.gallery-block .label-photo').addClass('tick');
                        setPreviewPhoto();

                        if (counter >= count_files) {
                            $('.proccess').each(function () {
                                $(this).removeClass('proccess');
                            })
                        }
                        counter++;
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var first = $('.photo-preview:not(.in)').first().removeClass('.proccess');
                    window.images_status--;
                }
            });
            if ((i + 1) >= this.files.length) {

                var tmpClone = globalFileUpload.cloneNode();
                var fileupload = document.getElementById('fileUpload');
                var parent = fileupload.parentNode;

                document.getElementById('fileUpload').parentNode.removeChild(fileupload);
                parent.appendChild(tmpClone);

            }
        }


    });
});

