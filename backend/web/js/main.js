function markDeactivated(product) {
    "use strict";
    var row_tr = $('tr[data-key="' + product + '"]');
    var active = row_tr.find('select[name="active"]').val();
    var row_tds = row_tr.find('td:not(.td-active)');
    if (active == 1) {
        row_tds.css('opacity', 1);
        row_tds.css('color', '#222');
    } else {
        row_tds.css('opacity', 0.25);
        row_tds.css('color', '#722');
    }
    return active;
}

function checkDeactivated() {
    "use strict";
    $('#products-table tbody tr').each(function () {
        markDeactivated($(this).data('key'));
    });
}


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


    // $('body').on('click', '#products-table tbody tr td:not(.td-active)',
    //     function () {
    //         var product = $(this).closest('tr').data('key');
    //         location.href = '/admin/products/update?id=' + product;
    //     }
    // );

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

