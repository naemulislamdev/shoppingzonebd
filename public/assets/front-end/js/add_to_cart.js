        function addWishlist(product_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('store-wishlist') }}",
                method: 'POST',
                data: {
                    product_id: product_id
                },
                success: function(data) {
                    if (data.value == 1) {
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('.countWishlist').html(data.count);
                        $('.countWishlist-' + product_id).text(data.product_count);
                        $('.tooltip').html('');

                        // Product AI API integration
                        const productAPI = new ProductAPI("https://ai.szbdfinancing.com");
                        async function loadProduct() {
                            const product = await productAPI.analyzeProduct(product_id, "view");
                            console.log(product);
                        }

                        loadProduct();

                    } else if (data.value == 2) {
                        Swal.fire({
                            type: 'info',
                            title: 'WishList',
                            text: data.error
                        });
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'WishList',
                            text: data.error
                        });
                    }
                }
            });
        }

        function removeWishlist(product_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('delete-wishlist') }}",
                method: 'POST',
                data: {
                    id: product_id
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    Swal.fire({
                        type: 'success',
                        title: 'WishList',
                        text: data.success
                    });
                    $('.countWishlist').html(data.count);
                    $('#set-wish-list').html(data.wishlist);
                    $('.tooltip').html('');

                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function addToCart(form_id, redirect_to_checkout = false) {
            
            if (form_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.post({
                    url: '{{ route('cart.add') }}',
                    data: $('#' + form_id).serializeArray(),
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {

                        if (data.data == 1) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Cart',
                                text: "Product already added in cart"
                            }).then(() => {
                                window.location.href = "{{ route('shop-cart') }}";
                            });
                            return false;
                        } else if (data.data == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cart',
                                text: 'Sorry, product out of stock.'
                            });
                            return false;
                        }

                        toastr.success('Item has been added in your cart!', {
                            CloseButton: true,
                            ProgressBar: true
                        });

                        $('#total_cart_count').text(data.count);
                        updateNavCart();
                        if (redirect_to_checkout) {
                            location.href = "{{ route('shop-cart') }}";
                        }
                        console.log(data.product.id);


                        if (data.product) {
                            window.dataLayer = window.dataLayer || [];

                            dataLayer.push({
                                event: "add_to_cart",
                                ecommerce: {
                                    currency: "BDT",
                                    value: (data.product.price * data.product.quantity).toFixed(2),
                                    items: [{
                                        item_id: data.product.id,
                                        item_name: data.product.name,
                                        item_brand: data.product.brand ?? "",
                                        item_category: data.product.category ?? "",
                                        item_variant: data.product.variant ?? "",
                                        price: parseFloat(data.product.price),
                                        quantity: parseInt(data.product.quantity)
                                    }]
                                }
                            });
                        }
                        // END DATALAYER PUSH
                        // Product AI API integration
                        const productAPI = new ProductAPI("https://ai.szbdfinancing.com");

                        async function loadProduct() {
                            const product = await productAPI.analyzeProduct(data.data.id, "view");
                            console.log(product);
                        }

                        loadProduct();
                        //End
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            } else {
                Swal.fire({
                    type: 'info',
                    title: 'Cart',
                    text: 'Something want wrong!'
                });
            }
        }

        function buy_now(form_id) {


            addToCart(form_id, true);
        }
        $('.new-av-product').on('click', function() {
            var product_id = $(this).data('pid');
            addToCart(product_id, true);
        });

        function currency_change(currency_code) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('currency.change') }}',
                data: {
                    currency_code: currency_code
                },
                success: function(data) {
                    toastr.success('Currency changed to' + data.name);
                    location.reload();
                }
            });
        }

        function removeFromCart(key) {

            $.post('{{ route('cart.remove') }}', {
                _token: '{{ csrf_token() }}',
                key: key
            }, function(data) {
                updateTotalCart();
                updateNavCart();
                $('#cart-summary').empty().html(data);
                toastr.info('Item has been removed from cart', {
                    CloseButton: true,
                    ProgressBar: true
                });
            });
        }

        function updateTotalCart() {
            $.post('<?php echo e(route('cart.totalCart')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>'
            }, function(data) {
                $('#total_cart_count').text(data);
            });
        }

        function updateNavCart() {
            $.post('<?php echo e(route('cart.nav_cart')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>'
            }, function(data) {
                $('#cart_items').html(data);
            });
        }

        function cartQuantityInitialize() {
            $('.btn-number').click(function(e) {
                // console.log("Ok");
                e.preventDefault();

                fieldName = $(this).attr('data-field');
                type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {

                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }

                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function() {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());

                var name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Sorry, the minimum value was reached'
                    });
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Sorry, stock limit exceeded.'
                    });
                    $(this).val($(this).data('oldValue'));
                }


            });
            $(".input-number").keydown(function(e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }

        function updateQuantity(key, element) {
            $.post('<?php echo e(route('cart.updateQuantity')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>',
                key: key,
                quantity: element.value
            }, function(data) {
                updateNavCart();
                $('#cart-summary').empty().html(data);
            });
        }

        function updateCartQuantity(key) {
            var quantity = $("#cartQuantity" + key).children("option:selected").val();
            $.post('<?php echo e(route('cart.updateQuantity')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>',
                key: key,
                quantity: quantity
            }, function(data) {
                // console.log(data);
                if (data['data'] == 0) {
                    toastr.error('Sorry, stock limit exceeded.', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $("#cartQuantity" + key).val(data['qty']);
                } else {
                    toastr.info('Quantity updated!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    updateNavCart();

                    $('#cart-summary').empty().html(data);
                }


            });
        }

        $('#add-to-cart-form input').on('change', function() {
            getVariantPrice();
        });

        function getVariantPrice() {
            if ($('#add-to-cart-form input[name=quantity]').val() > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{ route('cart.variant_price') }}',
                    data: $('#add-to-cart-form').serializeArray(),
                    success: function(data) {
                        $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                        $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                        $('#available-quantity').html(data.quantity);
                        $('.cart-qty-field').attr('max', data.quantity);
                    }
                });
            }
        }
