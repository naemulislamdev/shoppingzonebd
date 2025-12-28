@extends('layouts.front-end.app')

@section('title', \App\CPU\translate('My Shopping Cart'))

@push('css_or_js')
    <meta property="og:image" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="og:title" content="{{ $web_config['name']->value }} " />
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

    <meta property="twitter:card" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="twitter:title" content="{{ $web_config['name']->value }}" />
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/front-end') }}/css/shop-cart.css" />
    <style>
        p,
        span,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        th,
        label {
            font-family: 'SolaimanLipi', sans-serif !important;
            font-weight: 400;
            font-size: 18px;
        }

        .address-title {
            font-size: 22px;
        }

        .card-header {
            padding: 6px 0px;
            margin-bottom: 0;
            border-bottom: 0px solid rgba(0, 0, 0, .125);
            background: #424242;
            color: #ffffff;
            text-align: center;
        }

        header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 9999;
            border-bottom: 1px solid hsla(0, 0%, 100%, .14);
            background: #fff;
            transition: 0.5s;
        }

        .menu-area>ul>li>a {
            text-decoration: none;
            color: #343a40;
        }

        .menu-icon {
            color: #504f4f;
        }

        .header-icon>a>.fa {
            color: #464545;
        }

        .shipping-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .shipping-box input[type="radio"] {
            margin-right: 10px;
            accent-color: #33ad07;
            /* red accent */
        }

        .shipping-box:hover {
            border-color: #33ad07;
            background: #fff5f5;
        }

        .shipping-box input[type="radio"]:checked+.shipping-title {
            font-weight: bold;
            color: #f26d21;
        }
    </style>
@endpush

@section('content')
    <div class="container pb-5 mb-2 mt-3">
        <div class="row">
            <div class="col-md-10 mx-auto my-3">
                <div class="row">
                    <div class="col-lg-8">
                        <div style="overflow-y: auto; width:100%;" id="checkout-cart-items">
                            @include('layouts.front-end.partials.cart_details')
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">
                                <h2 class="address-title">আপনার ঠিকানা</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('customer.product.checkout.order') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="session_id" value="{{ session()->getId() }}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="d-block mb-2">আপনার এরিয়া সিলেক্ট করুন</label>
                                            <div class="row">
                                                @foreach (\App\Model\ShippingMethod::where(['status' => 1])->get() as $shipping)
                                                    <div class="col-md-6">
                                                        <label class="shipping-box">
                                                            {{-- data-cost must be a numeric (unformatted) value in BDT --}}
                                                            <input type="radio" name="shipping_method"
                                                                class="shipping-method" id="shipping_{{ $shipping['id'] }}"
                                                                data-cost="{{ \App\CPU\Helpers::currency_converter2($shipping['cost']) }}"
                                                                value="{{ $shipping['id'] }}">
                                                            <span class="shipping-title">{{ $shipping['title'] }}</span>
                                                            <span
                                                                class="shipping-cost">{{ \App\CPU\Helpers::currency_converter($shipping['cost']) }}</span>
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
                                                <input type="text" class="form-control auto-save"
                                                    placeholder="আপনার নাম লিখুন" name="name"
                                                    value="{{ old('name') }}">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="phone">ফোন নম্বর <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control auto-save" id="phone"
                                                    name="phone" placeholder="ফোন নম্বর লিখুন" required
                                                    value="{{ old('phone') }}">
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
                                                <textarea class="form-control auto-save" placeholder="আপনার শিপিং ঠিকানা লিখুন" name="address">{{ old('address') }}</textarea>
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
                            {{-- Now include the partial and send the formatted and raw values --}}
                            @include('web-views.partials._order-summary')

                        </div>
                    </aside>
                </div><!-- End .row -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grandTotalElement = document.getElementById('grand-total');
            let baseTotal = parseFloat(
                (grandTotalElement.dataset.raw || "0").toString().replace(/,/g, "")
            );
            console.log('Base Total:', baseTotal);

            const shippingRadios = document.querySelectorAll('.shipping-method');
            const preloader = document.getElementById('preloader');

            function formatBDT(value) {
                return value.toFixed(2) + ' ৳';
            }

            function updateTotal(shippingCost) {
                if (preloader) preloader.style.display = 'inline-block';
                setTimeout(function() {
                    const total = parseFloat(baseTotal) + parseFloat(shippingCost || 0);
                    // show formatted number
                    if (grandTotalElement) {
                        grandTotalElement.innerHTML = formatBDT(total);
                        // also update data-raw so further changes use the new base if needed
                        grandTotalElement.dataset.raw = total;
                    }
                    if (preloader) preloader.style.display = 'none';
                }, 300); // smaller delay, adjust as needed
            }

            // attach events
            shippingRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    // dataset.cost should be a number string
                    const costStr = this.dataset.cost || '0';
                    // remove commas, if any
                    const clean = String(costStr).replace(/,/g, '');
                    const shippingCost = parseFloat(clean) || 0;
                    document.getElementById('shipping-cost').innerHTML = formatBDT(shippingCost);
                    updateTotal(shippingCost);
                    //console.log(shippingCost);

                });
            });
        });
    </script>
    <script>
        cartQuantityInitialize();
    </script>
    <script>
        document.getElementById('phone').addEventListener('input', function() {
            const phoneInput = this.value;
            const phoneFeedback = document.getElementById('phoneFeedback');
            const regex = /^(01[3-9]\d{8})$/;

            if (phoneInput === '') {
                phoneFeedback.textContent = '';
            } else if (!regex.test(phoneInput)) {
                phoneFeedback.classList.add('text-danger');
                phoneFeedback.textContent = 'Please enter a valid Bangladeshi phone number (e.g. 0171XXXXXXX)';
            } else {
                phoneFeedback.textContent = 'Valid phone number!';
                phoneFeedback.classList.remove('text-danger');
                phoneFeedback.classList.add('text-success');
            }
        });

        // Also validate when the field loses focus
        document.getElementById('phone').addEventListener('blur', function() {
            const phoneInput = this.value;
            const phoneFeedback = document.getElementById('phoneFeedback');
            const regex = /^(01[3-9]\d{8})$/;

            if (phoneInput === '') {
                phoneFeedback.textContent = 'Phone number is required';
            } else if (!regex.test(phoneInput)) {
                phoneFeedback.textContent = 'Please enter a valid Bangladeshi phone number (e.g. 0171XXXXXXX)';
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            let typingTimer;
            let doneTypingInterval = 1000; // Time in milliseconds (1 second)

            $(".auto-save").on("input", function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(saveUserData, doneTypingInterval);
            });

            function saveUserData() {
                let formData = $("#userInfoForm").serialize();

                $.ajax({
                    url: "{{ route('save.user.info') }}",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            console.log("Data auto-saved successfully!");
                        } else {
                            console.log("Failed to save data.");
                        }
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr.responseText);
                    }
                });
            }
        });
    </script>

    @if (session()->has('cart') && count(session()->get('cart')) > 0)
        <script>
            window.dataLayer = window.dataLayer || [];

            dataLayer.push({
                event: "begin_checkout",
                ecommerce: {
                    currency: "BDT",
                    value: '',
                    items: [
                        @foreach (session('cart') as $item)
                            {
                                item_id: "{{ $item['id'] }}",
                                item_name: "{{ $item['name'] }}",
                                item_brand: "{{ $item['brand'] ?? '' }}",
                                item_category: "{{ $item['category'] ?? '' }}",
                                item_variant: "{{ $item['variant'] ?? '' }}",
                                price: {{ \App\CPU\Helpers::currency_converter($item['price'] - $item['discount']) }},
                                quantity: {{ $item['quantity'] }}
                            }
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    ]
                }
            });
        </script>
    @endif
@endpush
