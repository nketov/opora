$(document).ready(function () {
    "use strict";

    $.uploadPreview({
        input_field: "#image-upload",   // Default: .image-upload
        preview_box: "#image-preview",  // Default: .image-preview
        label_field: "#image-label",    // Default: .image-label
        label_default: "Выберите фото",   // Default: Choose File
        label_selected: "Измените фото",  // Default: Change File
        no_label: false                 // Default: false
    });


    $('#excel-upload').on('beforeSubmit ', function () {

        $('#upload-overlay').css({"display": "grid", "animation-name": "show"});
        var formData = new FormData($('#excel-upload')[0]);

        $.ajax({
            type: "POST",
            url: '/admin/upload',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            success: function (report) {
                $('.uploader-panel').html(report).css({"width": "100%", "text-align": "left", "top": "-25px"});
                $(".content-header h1").html('Отчёт о загрузке');
                $('#upload-overlay').html('');
                $('#upload-overlay').css({"animation-name": "hide"});
            },
            error: function (xhr) {
                $('.uploader-panel').html(xhr.responseText).css({
                    "width": "100%",
                    "text-align": "left",
                    "top": "-25px"
                });
                $(".content-header h1").html('Отчёт о загрузке');
                $('#upload-overlay').html('');
                $('#upload-overlay').css({"animation-name": "hide"});
            }
        });
        return false;
    });


    $('body').on('click', '#synonym-table tbody tr td:not(:last-child)',
        function () {
            var collection_id = $(this).closest('tr').data('key');
            location.href = '/admin/synonym/update?id=' + collection_id;
        }
    );


    $('body').on('click', '#post-table tbody tr td:not(.td-status)',
        function () {
            var product = $(this).closest('tr').data('key');
            location.href = '/admin/post/view?id=' + product;
        }
    );

    $('body').on('change', '#post-table select[name="status"],#post-view select[name="status"]',
        function () {
        var post = 0;
            if (parseInt($(this).closest('td').data('key'), 10) > 0) {
               post = $(this).closest('td').data('key');
            } else {
               post = $(this).closest('tr').data('key');
            }
            var status = $(this).closest('tr').find('select[name="status"]').val();
            $.ajax({
                method: "POST",
                url: '/admin/post/status',
                data: {status: status, post: post}
            })
                .done(function (data) {
                    console.log('Status Changed');
                });
        }
    );


    $('body').on('click', '#orders-table tbody tr td:not(.td-status)',
        function () {
            var order = $(this).closest('tr').data('key');
            location.href = '/admin/order/view?id=' + order;
        }
    );

    $('body').on('click', '#actions-table tbody tr td:not(:last-child)',
        function () {
            var action_id = $(this).closest('tr').data('key');
            location.href = '/admin/actions/update?id=' + action_id;
        }
    );

    $('body').on('click', '#articles-table tbody tr td:not(:last-child)',
        function () {
            var action_id = $(this).closest('tr').data('key');
            location.href = '/admin/article/update?id=' + action_id;
        }
    );

    $('body').on('click', '#vacancies-table tbody tr td:not(:last-child)',
        function () {
            var action_id = $(this).closest('tr').data('key');
            location.href = '/admin/vacancy/update?id=' + action_id;
        }
    );


    $('body').on(
        'click',
        '#images-form .image_icon, .btn-add-image',
        function () {
            var product = $(this).data('product');
            var key = $(this).data('key');

            location.href = '/admin/products/image-upload?product=' + product + '&key=' + key;
        }
    );

    $('body').on(
        'click',
        '#image-delete-modal #delete-confirm',
        function () {
            var product = $('#image-delete-button').data('product');
            var key = $('#image-delete-button').data('key');

            location.href = '/admin/products/update?id=' + product + '&delete_key=' + key;
        }
    );


    $('body').on(
        'click',
        '#grid-main-page div',
        function () {
            var modal = $('#main-page-modal');
            var key = $(this).data('key');
            modal.data('key', key);
            var product = $(this).find('img').data('product');
            if (product === undefined) {
                modal.find('.modal-title').text('Добавление товара');
            }
            $('#main-page-select').val(product).trigger('change');

        }
    );


    $('body').on(
        'click',
        '#main-page-modal #confirm',
        function () {
            var modal = $('#main-page-modal');
            var product = $('#main-page-select').val();
            var key = modal.data('key');

            $.ajax({
                method: "POST",
                // url: '/admin/products/active',
                data: {key: key, product: product}
            })
                .done(function (data) {
                    var div = $('#grid-main-page div[data-key="' + key + '"]');
                    div.html(data);
                    modal.find('#cancel').click();
                });

        }
    );


});

