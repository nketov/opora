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

    $('body').on('click', '.card-img-block,.card-text', function () {
        var product = $(this).closest('.card').data('key');
        location.href = '/product/' + product;
    });

    $('body').on('click', '#site-header-logo', function () {
        $('.site-header-logo').css('box-shadow', 'none');
        location.href = '/';
    });

    $('.left-catalog > div').hover(function () {
        if ($('.sub-menu a', this).length < 1) return;
        clearTimeout($.data(this, 'timer'));
        $('.sub-menu', this).stop(true, true).slideDown(350);
    }, function () {
        $.data(this, 'timer', setTimeout($.proxy(function () {
            $('.sub-menu', this).stop(true, true).slideUp(100);
        }, this), 150));
    });


    $('.left-catalog > div').on('taphold',function () {
        if ($('.sub-menu a', this).length < 1) return;
        clearTimeout($.data(this, 'timer'));
        $('.sub-menu', this).stop(true, true).slideDown(350);
    }, function () {
        $.data(this, 'timer', setTimeout($.proxy(function () {
            $('.sub-menu', this).stop(true, true).slideUp(100);
        }, this), 150));
    });

    $('.sub-menu > div').hover(function () {
        if ($('.sub-submenu a', this).length < 1) return;
        clearTimeout($.data(this, 'timer'));
        $('.sub-submenu', this).stop(true, true).slideDown(350);
    }, function () {
        $.data(this, 'timer', setTimeout($.proxy(function () {
            $('.sub-submenu', this).stop(true, true).slideUp(100);
        }, this), 150));
    });


    $('.sub-menu > div').on('taphold',function () {
        if ($('.sub-submenu a', this).length < 1) return;
        clearTimeout($.data(this, 'timer'));
        $('.sub-submenu', this).stop(true, true).slideDown(350);
    }, function () {
        $.data(this, 'timer', setTimeout($.proxy(function () {
            $('.sub-submenu', this).stop(true, true).slideUp(100);
        }, this), 150));
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

    $('.header-car-delete').on('click', function (e) {
        location.href = '/products/delete-car';
    });

    $('body').on('click', '.add-garage', function (e) {
        e.preventDefault();
        var position = $(this).closest('tr').data('key');
        var td = $(this).closest('td');

        $.ajax({
                url: '/products/add-garage?position=' + position,
                success: function (response) {
                    console.log(response);
                    var res = JSON.parse(response);
                    if (res.link == 'NULL') {
                        alert('Текущий автомобиль не выбран!');
                    } else {
                        td.html(res.link);
                        td.next('td').html(res.delete);
                    }
                },
                error: function (e) {
                    console.log(e.responseText);
                }
            }
        );
    });

    $('body').on('click', '.choose-garage', function (e) {
        e.preventDefault();
        var position = $(this).closest('tr').data('key');
        var td = $(this).closest('td');

        $.ajax({
                url: '/products/choose-garage?position=' + position,
                success: function (response) {
                   $('#site-header .header-car a').html(response);
                },
                error: function (e) {
                    console.log(e.responseText);
                }
            }
        );
    });


    $('body').on('click', '.delete-garage', function (e) {
        e.preventDefault();
        if (confirm("Удалить автомобиль?")) {
            var position = $(this).closest('tr').data('key');
            var td = $(this).closest('td');
            $.ajax({
                    url: '/products/delete-garage?position=' + position,
                    success: function (response) {
                            td.html('');
                            td.prev('td').html(response);
                    },
                    error: function (e) {
                        console.log(e.responseText);
                    }
                }
            );
        }
    });


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
                        url: '/products/add-car',
                        data: data,
                        type: 'post',
                        datatype: 'html',
                        // async: false,
                        success: function (response) {
                            var res = JSON.parse(response);
                            console.log(res.select_render);
                            $('#site-header .header-car a').html(res.car_name);
                            $('#td-category-panel').html(res.select_render);
                            $('.car-info').html(res.car_render);
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


    $(document).on('pjax:start', function () {
            $("#td_wheel-preloader").show(750);
            $('.list-wrapper').slideUp(1000);
            $('.main-content').css('min-height', $('.main-content').css('height'));
        })
        .on('pjax:end', function () {
            $("#td_wheel-preloader").hide(750);
            $('.list-wrapper').slideUp(0).slideDown(1000);
        })


    $('body').on('change', '#td_category,#td_sub_cat', function (e) {

            var category = parseInt($(this).val(), 10);

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


    $('#post-image').change(function () {
        $('.post-img').remove();
    });

    $('.image_container img').not(".empty").on('click', function () {
        window.open($(this).attr('src'));

    });

    $('#orderform-delivery').change(function () {

        $('.np-hide').css('display', 'none');

        if ($(this).val() == 1) {
            $('.nova-poshta-block').css('display', 'block');
        }
        if ($(this).val() == 2) {
            $('.nova-courier-address').css('display', 'block');
        }

    });

    $(window).resize(function () {
        // topCatalogResize();
    });

    // $('#td_category').trigger('change');
    // $('#td_sub_cat').trigger('change');


});