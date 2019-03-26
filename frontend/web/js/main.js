//
// function topCatalogResize() {
//     "use strict";
//     let height = $('.top-catalog div:last').offset().top - 388;
//     $('.top-catalog').css('height', height);
// }
function animateCSS(element, animationName, callback) {
    const node = document.querySelector(element)
    node.classList.add('animated', animationName)

    function handleAnimationEnd() {
        node.classList.remove('animated', animationName)
        node.removeEventListener('animationend', handleAnimationEnd)

        if (typeof callback === 'function') callback()
    }

    node.addEventListener('animationend', handleAnimationEnd)
}


$(function () {
    "use strict";
    // topCatalogResize();

    $('.hamburger').mouseover(function () {
        $('.top-catalog').css('opacity', '0.99');
        $('.top-catalog').css('z-index', '35');
    });
    $('.main-content,.header-img').mouseover(function () {
        $('.top-catalog').css('opacity', '0');
        $('.top-catalog').css('z-index', '-1');
    });


    $('body').on('change', 'input#prices', function () {
        var prices = ($(this).val()).split(',');
        $('.bage-min').text(prices[0] + ' грн');
        $('.bage-max').text(prices[1] + ' грн');
    });


    $('body').on('change', '#left-filter-form input:not(#prices),#left-filter-form select', function () {
        $(this).closest('form').submit();
    });

    $('body').on('click', '.card-img,.card-text', function () {
        var product = $(this).closest('.card').data('key');

        if ($('#pjax_car_category').length) {
            window.open('/product/' + product, '_blank');
        }
        else {
            location.href = '/product/' + product;
        }

    });

    $('body').on('click', '#site-header-logo', function () {
        $('.site-header-logo').css('box-shadow', 'none');
        location.href = '/';
    });

    $('.image_icon').on('click', function () {
            $('.image_icon').css('border', 'none');
            $(this).css('border', '#C3A solid 3px');
            $('#image').attr('src', $(this).attr('src'));
        }
    );

    $('.product-view').on('click', function (e) {
        e.preventDefault();
        var product = $(this).data('id');
        location.href = '/product/' + product;
    });

    $('.phone-change').on('click', function (e) {
        e.preventDefault();
        $('#phone-modal').modal('show');
    });

    $('body').on('click', '#brands-table tbody tr',
        function () {
            var year_ref = '';
            var year_number = $('select[name="year"]').val()
            if (year_number != 0) {
                year_ref = '/year/' + year_number;
            }
            var mfa_id = $(this).closest('tr').find('td').eq(1).text();
            location.href = '/tecdoc/models/' + mfa_id + year_ref;
        }
    );

    $('body').on('click', '#models-table tbody tr',
        function () {
            var year_ref = '';
            var year_number = $('h3').data('year');
            if (year_number != 0) {
                year_ref = '/year/' + year_number;
            }

            var mod_id = $(this).closest('tr').find('td').eq(1).text();
            location.href = '/tecdoc/types/' + mod_id + year_ref;
        }
    );

    $('body').on('click', '#types-table tbody tr',
        function () {
            var type_id = $(this).closest('tr').find('td').eq(1).text();
            location.href = '/tecdoc/test-tree/' + type_id;
        }
    );

    $('body').on('click', '#test-tree li',
        function (e) {
            var category = $(this).data('key');
            var type = $('#test-tree').data('type');
            e.stopPropagation();
            location.href = '/tecdoc/category/' + category + '/type/' + type;
        }
    );

    $('#td_type_id').on('change',
        function () {
            if (parseInt($(this).val(), 10) > 0) {
                $('.td_submit').css('opacity', '0.99');
            }
            else {
                $('.td_submit').css('opacity', '0');
            }

        }
    );

    $('.td_submit').on('click',
        function (e) {
            e.preventDefault();
            var name = $('#td_mfa_id option:selected').text()
                + " " + $('#td_type_id option:selected').text();

            var data = $('#tecdoc-search-form').find('select').serialize();

            data += '&TecdocSearch%5Bcar_name%5D=' + name;

            $('#pjax_car_category').slideUp(1000);
            $('#td-category-panel .select2').slideUp(1000, function () {
                history.pushState({}, null, '/car');

                $('#td-category-panel').html('<div id="select-preloader"></div>');
                $.ajax({
                        url: '/tecdoc/add-car',
                        data: data,
                        type: 'post',
                        datatype: 'html',
                        // async: false,
                        success: function (response) {
                            var res = JSON.parse(response);
                            console.log(res.select_render);
                            $('#site-header .header-car').html(res.car_name);
                            $('#td-category-panel').html(res.select_render);
                            $('#td-category-panel .select2').slideUp(0).slideDown(1000);

                        },
                        error: function (e) {
                            console.log(e.responseText);
                        }
                    }
                );
            });


        }
    );

    $(document)
        .on('pjax:start', function () {
            $("#td_wheel-preloader").show(750);
            $('.list-wrapper').slideUp(1000);
            $('.main-content').css('min-height', $('.main-content').css('height'));
        })
        .on('pjax:end', function () {
            $("#td_wheel-preloader").hide(750);
            $('.list-wrapper').slideUp(0).slideDown(1000);
        })


    $('body').on('change', '#td_category,#td_sub_cat', function (e) {

            let category = parseInt($(this).val(), 10);

            if (!(category > 0)) return;
            console.log(category);

            $("#td_wheel-preloader").show(750);
            $("#pjax_car_category").slideUp(1000);
            $.pjax.reload({
                container: "#pjax_car_category",
                timeout: false,
                type: 'get',
                url: '/car',
                data: {'category': category}
            }).done(function () {
                $("#td_wheel-preloader").hide(750);
                $("#pjax_car_category").slideDown(1000);
            });

        }
    );


    $('.cd-cart footer .btn-cart').on('click', function (e) {

        console.log($(this).data('guest'));
        if ($(this).data('guest') === 1) {
            e.preventDefault();
            $('#order-phone-modal').modal('show');
        }
    });


    $("#header_pjax_form").on("pjax:end", function () {
        $.pjax.reload({container: "#pjax_text_search", timeout: 5000});
    });

    $('.form-footer-text.toggle').click(function (e) {
        e.preventDefault();
        $('.login-form:not("#request-password-reset-form")').animate({height: "toggle", opacity: "toggle"}, 'slow');
    });

    $('.form-footer-text.reset').click(function (e) {
        e.preventDefault();
        $('#request-password-reset-form').animate({height: "toggle", opacity: "toggle"}, 'slow');
        $(this).closest('form').animate({height: "toggle", opacity: "toggle"}, 'slow');
    });


    $(window).resize(function () {
        // topCatalogResize();
    });


});