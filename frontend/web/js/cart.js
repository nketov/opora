jQuery(document).ready(function ($) {
    'use strict';
    var cartWrapper = $('.cd-cart-container');

    if (cartWrapper.length > 0) {
        //store jQuery objects
        var cartBody = cartWrapper.find('.body')
        var cartList = cartBody.find('ul').eq(0);
        var cartTotal = cartWrapper.find('.checkout').find('span');
        var cartTrigger = cartWrapper.children('.cd-cart-trigger');
        var cartCount = cartTrigger.children('.count');
        var undo = cartWrapper.find('.undo');
        var undoTimeoutId;

        //add product to cart
        $('body').on('click', '.cd-add-to-cart', function (event) {

            event.preventDefault();

            var qty = Number($('#cd-product-' + $(this).data('id')).val());
            var remains = Number($(this).data('remains'));
            var unit = $(this).data('unit');

            if (qty === remains) {
                alert('Максимальное количество данного продукта '+remains+' '+unit);
                return;
            }

            $(this).css('pointer-events', 'none');
            cartCount.css('animation', '1s shake');

            addToCartAjax($(this).data(), 1);

            $(this).css('pointer-events', 'auto');
            cartCount.css('animation', 'none');

        });

        //open/close cart
        cartTrigger.on('click', function (event) {
            event.preventDefault();
            toggleCart();
        });

        //close cart when clicking on the .cd-cart-container::before (bg layer)
        cartWrapper.on('click', function (event) {
            if ($(event.target).is($(this))) toggleCart(true);
        });

        //delete an item from the cart
        cartList.on('click', '.delete-item', function (event) {
            event.preventDefault();
            removeProduct($(event.target).parents('.product'));
        });

        //update item quantity
        cartList.on('change', 'select', function () {
            var product = $(this).closest('.product');
            var qty = Number(product.find('select').val());
            product.find('select').val(0);

            $.post('/products/delete-cart?id=' + product.data('id'),
                function () {
                    addToCartAjax(product.data(), qty);
                });
            quickUpdateCart();
        });

        //reinsert item deleted from the cart
        undo.on('click', 'a', function (event) {
            clearInterval(undoTimeoutId);
            event.preventDefault();

            var product = cartList.find('.deleted');
            var qty = Number(product.find('select').val());
            product.find('select').val(0);

            addToCartAjax(product.data(), qty);

            product.addClass('undo-deleted').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function () {
                $(this).off('webkitAnimationEnd oanimationend msAnimationEnd animationend').removeClass('deleted undo-deleted').removeAttr('style');
                quickUpdateCart();
            });

            undo.removeClass('visible');
        });
    }

    function toggleCart(bool) {
        var cartIsOpen = (typeof bool === 'undefined') ? cartWrapper.hasClass('cart-open') : bool;

        if (cartIsOpen) {
            cartWrapper.removeClass('cart-open');
            //reset undo
            clearInterval(undoTimeoutId);
            undo.removeClass('visible');
            cartList.find('.deleted').remove();

            setTimeout(function () {
                cartBody.scrollTop(0);
                //check if cart empty to hide it
                if (Number(cartCount.find('li').eq(0).text()) == 0) cartWrapper.addClass('empty');
            }, 500);
        } else {
            cartWrapper.addClass('cart-open');
        }
    }

    function addToCart(data, quantity) {
        var cartIsEmpty = cartWrapper.hasClass('empty');

        addProduct(data, quantity);

        updateCartCount(cartIsEmpty, quantity);

        updateCartTotal(data.price, true, quantity);

        cartWrapper.removeClass('empty');
    }


    function addToCartAjax(data, quantity) {
        $.ajax({
                url: '/products/add-cart',
                data: {data: data, qty: quantity},
                type: 'post',
                async: false,
                success: function () {
                    addToCart(data, quantity);
                },
                error: function (e) {
                    console.log('Error!');
                    console.log(e.responseText);
                }
            }
        );
    }

    function addProduct(data, quantity) {

        var summ = (Number(data.price) * Number(quantity)).toFixed(2);

        if ($('#product_' + data.id).length > 0) {
            var product = $('#product_' + data.id);

            var qty = Number(product.find('select').val());

            qty = Number(qty) + Number(quantity);
            product.find('select').val(qty);

            summ = (Number(summ) + Number(product.find('.price').text().replace(' грн', '').replace('Цена не указана', 0))).toFixed(2);

            product.find('.price').text(summ + ' грн')

            if (summ == 0) {
                product.find('.price').text('Цена не указана');
            }

        } else {

            var deleteLink = '<a href="#0" class="delete-item">Удалить</a>';

            var selectOptions = '';
            for (var i = 1; i <= data.remains; i++) {
                selectOptions += '<option value="' + i + '">' + i + '</option>'
            }

            var selectSpan = '<span class="select"><select id="cd-product-' + data.id + '" name="quantity" >' + selectOptions + '</select></span>';

            var quantityBlock = '<div class="quantity"><label for="cd-product-' + data.id + '">Количество</label>' + selectSpan + '&nbsp;&nbsp;'+data.unit+'</div>';

            var spanPrice = '<span class="price">' + summ + ' грн</span>';

            if (summ == 0) {
                spanPrice = '<span class="price">Цена не указана</span>';
            }

            var nameLink = '<a class="product-name" href="/product/' + data.id + '">' + data.name + '</a>';

            var imageBlock = '<div class="product-image"><a href="/product/' + data.id + '"><img src="' + data.image + '" alt="' + data.name + '"></a></div>';

            var productAdded = $('<li id="product_' + data.id + '" class="product">' + imageBlock + nameLink + spanPrice + '<div class="actions">' + quantityBlock + deleteLink + '</div></li>');
            productAdded.data(data);

            cartList.prepend(productAdded);

            $('#cd-product-' + data.id).val(quantity);
        }

    }

    function removeProduct(product) {

        $.post('/products/delete-cart?id=' + product.data('id'),
            function () {
                clearInterval(undoTimeoutId);
                cartList.find('.deleted').remove();

                var topPosition = product.offset().top - cartBody.children('ul').offset().top,
                    productQuantity = Number(product.find('.quantity').find('select').val()),
                    productTotPrice = Number(product.data('price')) * productQuantity;

                product.css('top', topPosition + 'px').addClass('deleted');

                updateCartTotal(productTotPrice, false);
                quickUpdateCart(-productQuantity);
                undo.addClass('visible');

                //wait 10sec before completely remove the item
                undoTimeoutId = setTimeout(function () {
                    undo.removeClass('visible');
                    cartList.find('.deleted').remove();
                }, 20000);
            }
        );
    }

    function quickUpdateCart(quantity) {

        if (typeof quantity === 'undefined') {
            quantity = 0;
            var price = 0;

            cartList.children('li:not(.deleted)').each(function () {

                var singleQuantity = Number($(this).find('select').val());
                var singlePrice = Number($(this).data('price'));
                var singleSumm = (singlePrice * singleQuantity).toFixed(2);
                $(this).find('.price').text(singleSumm + ' грн');

                if (singleSumm == 0) {
                    $(this).find('.price').text('Цена не указана');
                }

                quantity = quantity + singleQuantity;
                price = (Number(price) + Number(singleSumm)).toFixed(2);

            });

            cartTotal.text(price);
            cartCount.find('li').eq(0).text(quantity);
            cartCount.find('li').eq(1).text(quantity + 1);

        } else {
            var actual = Number(cartCount.find('li').eq(0).text()) + quantity;
            var next = actual + 1;

            cartCount.find('li').eq(0).text(actual);
            cartCount.find('li').eq(1).text(next);
        }


    }

    function updateCartCount(emptyCart, quantity) {

        var actual = Number(cartCount.find('li').eq(0).text()) + quantity;
        var next = actual + 1;

        if (emptyCart) {
            cartCount.find('li').eq(0).text(actual);
            cartCount.find('li').eq(1).text(next);
        } else {
            cartCount.addClass('update-count');


            setTimeout(function () {
                cartCount.find('li').eq(0).text(actual);
            }, 150);

            setTimeout(function () {
                cartCount.removeClass('update-count');
            }, 200);

            setTimeout(function () {
                cartCount.find('li').eq(1).text(next);
            }, 230);
        }
    }

    function updateCartTotal(price, bool, quantity) {

        var summ = price * quantity + Number($(this).find('.price').text());

        bool ? cartTotal.text((Number(cartTotal.text()) + Number(summ)).toFixed(2)) : cartTotal.text((Number(cartTotal.text()) - Number(price)).toFixed(2));
    }

    $.ajax({
            url: '/products/ajax-get-session',
            success: function (cart) {
                for (var id in cart) {
                    var product = cart[id];
                    product.id = id;
                    product.price = Number(product.price);
                    addToCart(product, Number(product.qty));
                    quickUpdateCart();
                }
            },
            error: function (e) {
                console.log('Error!')
                console.log(e.responseText);
            }
        }
    );


    // addToCart({ image: "/images/products/p_9157_image_1.jpg", name: "Diamant Crystal Palace2", price: "210000.25", id: 91572 },2);


});