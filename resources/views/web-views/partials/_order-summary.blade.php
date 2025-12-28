<h3 class="summary-title">Cart Total</h3><!-- End .summary-title -->
<table class="table table-summary">

    <tbody>
        @php
            $sub_total = 0;
            $total_tax = 0;
            $total_discount_on_product = 0;
            $g_total = 0;
        @endphp
        @if (session()->has('cart') && count(session()->get('cart')) > 0)
            @foreach (session('cart') as $key => $cartItem)
                {{-- @dd(session('cart')) --}}
                @php
                    $sub_total +=
                        $cartItem['price'] * $cartItem['quantity'] - $cartItem['quantity'] * $cartItem['discount'];
                    $total_tax += $cartItem['tax'] * $cartItem['quantity'];
                    $total_discount_on_product += $cartItem['discount'] * $cartItem['quantity'];
                @endphp
            @endforeach
        @else
            <span>Empty Cart</span>
        @endif
        <tr class="summary-subtotal">
            <td>Subtotal:</td>
            <td>{{ \App\CPU\Helpers::currency_converter($sub_total) }}</td>
        </tr>
        <tr class="summary-shipping">
            <td>Shipping:</td>
            <td id="shipping-cost"></td>
        </tr>
        <tr class="summary-subtotal">
            @if (session()->has('coupon_discount'))
                <td class="d-flex">Coupon Discount</td>
                <td id="coupon-discount-amount">
                    -
                    {{ session()->has('coupon_discount') ? \App\CPU\Helpers::currency_converter(session('coupon_discount')) : 0 }}
                </td>

                @php
                    $coupon_dis = session('coupon_discount');
                @endphp
        </tr>
    @else
        <div class="mt-2">
            <form class="needs-validation" method="post" novalidate id="coupon-code-ajax">
                <div class="form-group">
                    <input class="form-control input_code" type="text" name="code" id="couponcod"
                        placeholder="Coupon code (Optional)" required>
                    <div class="invalid-feedback">Please provide coupon code.</div>
                </div>
                <button class="btn btn-primary btn-block" type="button" onclick="couponCode()">Apply Code
                </button>
            </form>
        </div>

        @php
            $coupon_dis = 0;
        @endphp
        @endif

        <tr class="summary-total">
            @php
                $raw_total = $sub_total + $total_tax - $coupon_dis;
                $g_total = \App\CPU\Helpers::currency_converter($raw_total);
            @endphp
            <td>Total:</td>
            <td id="grand-total" data-raw="{{ $g_total }}">{{ $g_total }}</td>
        </tr>
        <div id="preloader" style="display: none;">
            <img src="{{ asset('assets/front-end/img/loader_.gif') }}" alt="Loading..." width="20">
        </div>
    </tbody>
</table><!-- End .table table-summary -->
