<div class="row">
    <div class="col-md-10 mx-auto my-3">
        <div class="row">
            <div class="col-lg-8">
                <div style="overflow-y: auto; width:100%;">
                    <table class="table table-cart table-mobile">
                        <thead>
                            <tr>
                                <th>ছবি</th>
                                <th>পণ্যের নাম</th>
                                <th>মূল্য</th>
                                <th>পরিমাণ</th>
                                <th>মোট</th>
                                <th>অ্যাকশন</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (session()->has('cart') && count(session()->get('cart')) > 0)
                                @foreach (session()->get('cart') as $key => $cartItem)
                                    <tr>
                                        <td class="product-col">
                                            <div class="checkout-product">
                                                <a href="{{ route('product', $cartItem['slug']) }}">
                                                    <img src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $cartItem['thumbnail'] }}"
                                                        alt="Product image">
                                                </a>
                                            </div>
                                        </td>
                                        <td><a
                                                href="{{ route('product', $cartItem['slug']) }}">{{ Str::limit($cartItem['name'], 30) }}</a>
                                        </td>
                                        <td class="price-col">
                                            {{ \App\CPU\Helpers::currency_converter($cartItem['price'] - $cartItem['discount']) }}
                                        </td>
                                        <td class="quantity-col">
                                            <div class="product-quantity d-flex align-items-center">
                                                <select name="quantity[{{ $key }}]"
                                                    id="cartQuantity{{ $key }}"
                                                    onchange="updateCartQuantity('{{ $key }}')">
                                                    @for ($i = 1; $i <= 100; $i++)
                                                        <option value="{{ $i }}" <?php if ($cartItem['quantity'] == $i) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </td>
                                        <td class="total-col">
                                            {{ \App\CPU\Helpers::currency_converter(($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity']) }}
                                        </td>
                                        <td class="remove-col"><a href="javascript:voide(0);"
                                                onclick="removeFromCart({{ $key }})"
                                                class="btn-remove"><i class="fa fa-trash-o"></i></a></td>
                                    </tr>
                                @endforeach
                            @else
                                <div class="empty-cart-box">
                                    <i class="fa fa-shopping-bag"></i>
                                    <h4>Your cart is empty.</h4>
                                    <a href="{{ route('home') }}" class="btn btn-dark">Return to shop</a>
                                </div>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                        <h2 class="address-title">আপনার ঠিকানা</h2>
                    </div>
                    <div class="card-body">
<form action="{{ route('customer.product.checkout.order') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="d-block mb-2">আপনার এরিয়া সিলেক্ট করুন</label>
                                    <div class="row">
                                        @foreach (\App\Model\ShippingMethod::where(['status' => 1])->get() as $shipping)
                                            <div class="col-md-6">
                                                <label class="shipping-box">
                                                    <input type="radio" name="shipping_method_id"
                                                        value="{{ $shipping['id'] }}"
                                                        onchange="set_shipping_id(this.value)"
                                                        {{ session()->has('shipping_method_id') && session('shipping_method_id') == $shipping['id'] ? 'checked' : '' }}>
                                                    <span class="shipping-title">
                                                        {{ $shipping['title'] }}
                                                    </span>
                                                    <span class="shipping-cost">
                                                        {{ \App\CPU\Helpers::currency_converter($shipping['cost']) }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{-- <label>পেমেন্ট পদ্ধতি নির্বাচন করুন</label> --}}
                                        <select class="form-control" name="payment_method" hidden>
                                            <option value="cash_on_delivery" selected>ক্যাশ অন ডেলিভারি</option>
                                            {{-- <option value="online_payment">অনলাইন পেমেন্ট</option> --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>নাম <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="আপনার নাম লিখুন"
                                            name="name" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="phone">ফোন নম্বর <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="phone" name="phone"
                                            placeholder="ফোন নম্বর লিখুন" required value="{{ old('phone') }}">
                                        <span id="phoneFeedback" class="small text-danger"></span>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label>আপনার ঠিকানা <span class="text-danger">*</span></label>
                                        <textarea class="form-control" placeholder="আপনার শিপিং ঠিকানা লিখুন" name="address">{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">অর্ডার করুন</button>
                        </form>
                    </div>
                </div>

            </div><!-- End .col-lg-9 -->
            <aside class="col-lg-4">
                <div class="summary summary-cart">
                    @include('web-views.partials._order-summary')
                </div>
            </aside><!-- End .col-lg-3 -->
        </div><!-- End .row -->
    </div>
</div>


<script>

    function set_shipping_id(id) {
        @foreach(session()->get('cart') as $key => $item)
        let key = '{{$key}}';
        @break
        @endforeach
        $.get({
            url: '{{url('/')}}/customer/set-shipping-method',
            dataType: 'json',
            data: {
                id: id,
                key: key
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                if (data.status == 1) {
                    toastr.success('Shipping method selected', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setInterval(function () {
                        location.reload();
                    }, 2000);
                } else {
                    toastr.error('Choose proper shipping method.', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }
</script>
