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
        @php
            $gTotal = 0;
        @endphp
        @if (session()->has('cart') && count(session()->get('cart')) > 0)
            @foreach (session()->get('cart') as $key => $cartItem)
                @php
                    $gTotal += ($cartItem['price'] - $cartItem['discount']) * $cartItem['quantity'];
                @endphp
                <tr>
                    <td class="product-col">
                        <div class="checkout-product">
                            <a href="{{ route('product', $cartItem['slug']) }}">
                                @if ($cartItem['color_image'])
                                    <img src="{{ $cartItem['color_image'] }}" alt="Product image">
                                @else
                                    <img src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $cartItem['thumbnail'] }}"
                                        alt="Product image">
                                @endif
                            </a>
                        </div>
                    </td>
                    <td><a href="{{ route('product', $cartItem['slug']) }}">{{ Str::limit($cartItem['name'], 30) }}</a>
                    </td>
                    <td class="price-col">
                        {{ \App\CPU\Helpers::currency_converter($cartItem['price'] - $cartItem['discount']) }}
                    </td>
                    <td class="quantity-col">
                        <div class="product-quantity d-flex align-items-center">
                            <select name="quantity[{{ $key }}]" id="cartQuantity{{ $key }}"
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
                    <td class="remove-col"><a href="javascript:voide(0);" onclick="removeFromCart({{ $key }})"
                            class="btn-remove"><i class="fa fa-trash-o"></i></a></td>
                </tr>
            @endforeach
        @else
            <div class="empty-cart-box my-4">
                <i class="fa fa-shopping-bag"></i>
                <h4>Your cart is empty.</h4>
                <a href="{{ route('home') }}" class="btn btn-dark">Return to shop</a>
            </div>
        @endif
    </tbody>
</table>
