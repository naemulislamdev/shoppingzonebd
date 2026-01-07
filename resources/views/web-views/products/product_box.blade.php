<div class="{{ $classBox ?? 'col-md-2' }} col-sm-6 product-column" data-category="{{ $dataCategory ?? '' }}">
    <div class="product-box product-box-col-2" data-category="{{ $dataCategory ?? '' }}">
        <input type="hidden" name="quantity" value="{{ $product->minimum_order_qty ?? 1 }}"
            min="{{ $product->minimum_order_qty ?? 1 }}" max="100">
        <div class="product-image2 product-image2-col-2" data-category="{{ $dataCategory ?? '' }}">
            @if ($product->discount > 0)
                <div class="discount-box float-end">
                    <span>
                        @if ($product->discount_type == 'percent')
                            {{ $product->discount }}%
                        @elseif($product->discount_type == 'flat')
                            {{ \App\CPU\Helpers::currency_converter($product->discount) }}৳
                        @endif
                    </span>
                </div>
            @endif
            <a href="{{ route('product', $product->slug) }}">
                <!-- ✅ Lazy Loading Image -->
                <img class="img-fluid lazy-image" loading="lazy"
                    src="data:image/svg+xml,%3Csvg width='300' height='300' xmlns='http://www.w3.org/2000/svg'%3E%3C/svg%3E"
                    data-src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}"
                    alt="{{ $product['name'] }}">
            </a>
            <ul class="social">
                <li><a href="{{ route('product', $product->slug) }}" data-tip="Quick View"><i
                            class="fa fa-eye"></i></a></li>

                <li><a style="cursor: pointer" data-toggle="modal" data-target="#addToCartModal_{{ $product->id }}"
                        data-tip="Add to Cart"><i class="fa fa-shopping-cart"></i></a>
                </li>
            </ul>
        </div>
        <div class="product-content">
            <h3 class="title"><a
                    href="{{ route('product', $product->slug) }}">{{ Str::limit($product['name'], 50) }}</a>
            </h3>
            <div class="price d-flex justify-content-center align-content-center">
                @if ($product->discount > 0)
                    <span
                        class="mr-2">৳{{ \App\CPU\Helpers::currency_converter(
                            $product->unit_price - \App\CPU\Helpers::get_product_discount($product, $product->unit_price),
                        ) }}</span>
                    <del>৳ {{ \App\CPU\Helpers::currency_converter($product->unit_price) }}</del>
                @else
                    <span>৳ {{ \App\CPU\Helpers::currency_converter($product->unit_price) }}</span>
                @endif
            </div>
            <button type="button" style="cursor: pointer;" class="btn btn-primary"
                onclick="buy_now('form-{{ $product->id }}')">অর্ডার করুন</button>
        </div>
    </div>
</div>
<!-- AddToCart Modal -->
<div class="modal fade addToCartModalCls" id="addToCartModal_{{ $product->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable" role="document">
        <form id="form-{{ $product->id }}" class="mb-2">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="product-modal-box d-flex align-items-center mb-3">
                        <div class="img mr-3">
                            <img class="rounded main-image"
                                src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}"
                                alt="{{ $product['name'] }}" style="width: 75px;">
                        </div>
                        <div class="p-name">
                            <h5 class="title">{{ Str::limit($product['name'], 50) }}</h5>
                            <span style="color: #ff5d00; font-size: 22px;" class="mr-2"><span
                                    style="font-size: 30px;">৳</span>
                                {{ \App\CPU\Helpers::currency_converter(
                                    $product->unit_price - \App\CPU\Helpers::get_product_discount($product, $product->unit_price),
                                ) }}</span>
                        </div>
                    </div>
                    {{-- @if (count(json_decode($product->colors)) > 0)
                        <div class="row">
                            <div class="col-12">
                                <h4>Color</h4>
                            </div>
                            <div class="col-12">
                                <div class="d-flex">
                                    @foreach (json_decode($product->colors) as $key => $color)
                                        <div class="v-color-box">
                                            <input type="radio" id="{{ $product->id }}-color-{{ $key }}"
                                                name="color" value="{{ $color }}"
                                                @if ($key == 0) checked @endif>
                                            <label style="background: {{ $color }}"
                                                for="{{ $product->id }}-color-{{ $key }}"
                                                class="color-label"></label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif --}}

                    @if (count(json_decode($product->colors)) > 0)
                        <div class="row mb-4 mt-3">
                            <div class="col-12 mb-3">
                                <h4 style="font-size: 18px;">Color</h4>
                            </div>
                            @if ($product->color_variant != null)
                                <div class="col-12 mb-3 mt-4">
                                    <div class="d-flex">
                                        @foreach (json_decode($product->color_variant) as $key => $color)
                                            <div class="v-color-box position-relative">
                                                <input type="radio"
                                                    id="{{ $product->id }}-color-{{ $key }}" name="color"
                                                    value="{{ $color->code }}"
                                                    @if ($key == 0) checked @endif>
                                                <label for="{{ $product->id }}-color-{{ $key }}"
                                                    class="color-label p-0"
                                                    style="background-color: {{ $color->code }}; overflow: hidden; padding: 0 !important;">
                                                    <img src="{{ asset($color->image) }}"
                                                        data-image="{{ asset($color->image) }}"
                                                        alt="{{ $color->color }}" style="max-width:100%; height:auto;">
                                                </label>

                                                <span class="d-inline-block"
                                                    style="height: 20px; width: 20px; border-radius: 50%; position: absolute;
                                                                                            right: -11px;
                                                                                            top: -49px;
                                                                                            background: {{ $color->code }}"></span>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            @endif
                        </div>
                    @endif

                    @if (count(json_decode($product->choice_options)) > 0)
                        @foreach (json_decode($product->choice_options) as $key => $choice)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h4 style="font-size: 18px; margin:0;">{{ $choice->title }}
                                    </h4>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="d-flex">
                                        @foreach ($choice->options as $key => $option)
                                            <div class="v-size-box">
                                                <input type="radio"
                                                    id="{{ $product->id }}-size-{{ $key }}"
                                                    name="{{ $choice->name }}" value="{{ $option }}"
                                                    @if ($key == 0) checked @endif>
                                                <label style="height: 38px !important;"
                                                    for="{{ $product->id }}-size-{{ $key }}"
                                                    class="size-label">{{ $option }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <h5>Quantity:</h5>
                        </div>
                        <div class="col-md-9 pl-4 ps-4">
                            {{-- <div class="product-quantity d-flex align-items-center">
                                <div class="input-group input-group--style-2 pr-3" style="width: 160px;">
                                    <span class="input-group-btn">
                                        <button class="btn btn-number" type="button" data-type="minus"
                                            data-field="quantity" disabled="disabled" style="padding: 10px">
                                            -
                                        </button>
                                    </span>
                                    <input type="text" name="quantity"
                                        class="form-control input-number text-center cart-qty-field" placeholder="1"
                                        value="1" min="1" max="100">
                                    <span class="input-group-btn">
                                        <button class="btn btn-number" type="button" data-type="plus"
                                            data-field="quantity" style="padding: 10px">
                                            +
                                        </button>
                                    </span>
                                </div>

                            </div> --}}
                            <div class="product-quantity d-flex align-items-center">
                                <div class="input-group input-group--style-2 pr-3 d-flex align-items-center"
                                    style="width: 160px; ">

                                    <button class="btn btn-danger btn-number rounded-circle" type="button"
                                        data-type="minus" data-field="quantity" style="padding: 10px">
                                        <i class="fa fa-minus "></i>
                                    </button>

                                    <input style="font-size: 20px; font-weight: 600" type="text" readonly
                                        name="quantity" disabled="disabled"
                                        class="form-control bg-transparent input-number text-center cart-qty-field border-0 p-0"
                                        placeholder="1" value="1" min="1" max="100">

                                    <button class="btn btn-success rounded-circle btn-number" type="button"
                                        data-type="plus" data-field="quantity" style="padding: 10px">
                                        <i class="fa fa-plus "></i>
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-start">
                    <a href="{{ route('product', $product->slug) }}" class="btn btn-secondary btn-sm"> <i
                            class="fa fa-eye    "></i> View Details</a>
                    <button type="button" class="btn btn-danger btn-sm"
                        onclick="addToCart('form-{{ $product->id }}')"> <i class="fa fa-cart-plus"
                            aria-hidden="true"></i> Add
                        To Cart</button>
                </div>
            </div>
        </form>
    </div>
</div>
