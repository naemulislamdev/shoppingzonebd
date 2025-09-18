@extends('layouts.front-end.app')
@section('title', \App\CPU\translate('Welcome To') . ' ' . $web_config['name']->value)

@push('css_or_js')
    <meta property="og:image" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="og:title" content="Best Online Marketplace In Bangladesh {{ $web_config['name']->value }} Home" />
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{!! substr($web_config['about']->value, 0, 100) !!}">

    <meta property="twitter:card" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="twitter:title" content="Welcome To {{ $web_config['name']->value }} Home" />
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value, 0, 100) !!}">
    <style>
        .main-banner {
            height: auto;
        }

        .middle-banner {
            height: 300px;
        }

        .middle-banner>img {
            height: 100%;
        }

        .left-banner {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
        }

        .left-banner>img {
            width: 100%;
        }

        .product-item {
            text-align: center;
            border: 1px solid #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
        }

        .product-item img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-item h5 {
            margin-top: 10px;
        }

        .product-box-col-3 {
            height: 640px;
        }

        .product-image2-col-3 {
            height: 526px;
        }

        @media (max-width:768px) {
            .main-banner {
                height: auto;
            }

            .middle-banner {
                height: 70px;
            }
        }
    </style>
@endpush
@section('content')
    <!-- Top Main Banner -->
    <div class="container-fluid">
        <section class="landing-slider-section">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach (json_decode($landing_page->main_banner) as $key => $banner)
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                            class="{{ $key == 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach (json_decode($landing_page->main_banner) as $key => $banner)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="main-banner">
                                <img class="d-block w-100"
                                    onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                    src="{{ asset('storage/deal/main-banner') }}/{{ $banner }}" alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </section>
    </div>

    <!-- Main Section with Left Banner and Product Showcase -->
    <div class="container mt-4">
        <div class="row">
            <!-- Left Side Banner -->
            <div class="col-md-3">
                <div class="left-banner">
                    {{-- <h4>Special Offer</h4>
                    <p>Get up to 50% off on selected items. Hurry, offer ends soon!</p> --}}
                    <img src="{{ asset('storage/deal') }}/{{ $landing_page->left_side_banner }}" alt="">
                </div>
            </div>

            <!-- Product Showcase -->
            <div class="col-md-9">
                @php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
                @if (count($landing_products) > 0)
                    <div class="row">
                        <!-- Your product columns go here -->
                        @foreach ($landing_products as $key => $product)
                            @if ($key < 6)
                                <div class="col-md-4 mb-3">
                                    <div class="product-box product-box-col-3">
                                        <input type="hidden" name="quantity"
                                            value="{{ $product->minimum_order_qty ?? 1 }}"
                                            min="{{ $product->minimum_order_qty ?? 1 }}" max="100">
                                        <div class="product-image2 product-image2-col-3">
                                            @if ($product->discount > 0)
                                                <div class="discount-box float-end">
                                                    <span>
                                                        @if ($product->discount_type == 'percent')
                                                            {{ round($product->discount, $decimal_point_settings) }}%
                                                        @elseif($product->discount_type == 'flat')
                                                            {{ \App\CPU\Helpers::currency_converter($product->discount) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                            <a href="{{ route('product', $product->slug) }}">
                                                <img class="pic-1"
                                                    src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}">
                                                <img class="pic-2"
                                                    src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}">
                                            </a>
                                            <ul class="social">
                                                <li><a href="{{ route('product', $product->slug) }}"
                                                        data-tip="Quick View"><i class="fa fa-eye"></i></a></li>

                                                <li><a style="cursor: pointer" data-toggle="modal"
                                                        data-target="#addToCartModal_{{ $product->id }}"
                                                        data-tip="Add to Cart"><i class="fa fa-shopping-cart"></i></a>
                                                </li>
                                            </ul>
                                            <button type="button" style="cursor: pointer;" class="buy-now"
                                                onclick="buy_now('form-{{ $product->id }}')">Buy Now</button>
                                        </div>
                                        <div class="product-content">
                                            <h3 class="title"><a
                                                    href="{{ route('product', $product->slug) }}">{{ Str::limit($product['name'], 23) }}</a>
                                            </h3>
                                            <div class="price d-flex justify-content-center align-content-center">
                                                @if ($product->discount > 0)
                                                    <span
                                                        class="mr-2">{{ \App\CPU\Helpers::currency_converter(
                                                            $product->unit_price - \App\CPU\Helpers::get_product_discount($product, $product->unit_price),
                                                        ) }}</span>
                                                    <del>{{ \App\CPU\Helpers::currency_converter($product->unit_price) }}</del>
                                                @else
                                                    <span>{{ \App\CPU\Helpers::currency_converter($product->unit_price) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))
                                        <div class="rating-show justify-content-between text-center">
                                            <span class="d-inline-block font-size-sm text-body">
                                                @for ($inc = 0; $inc < 5; $inc++)
                                                    @if ($inc < $overallRating[0])
                                                        <i class="fa fa-star" style="color:#fea569 !important"></i>
                                                    @else
                                                        <i class="fa fa-star-o" style="color:#fea569 !important"></i>
                                                    @endif
                                                @endfor
                                                <label class="badge-style">( {{ $product->reviews_count }} )</label>
                                            </span>
                                        </div>

                                    </div>
                                    <div class="col-md-12 text-center d-md-none">
                                        <div class="mobile-btn justify-content-center align-items-center">
                                            <button type="button" class="btn btn-warning text-white text-bold"
                                                onclick="buy_now('form-{{ $product->id }}')">Order Now</button>
                                            <a href="{{ route('product', $product->slug) }}" class="btn btn-info">View
                                                Details</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- AddToCart Modal -->
                                <div class="modal fade" id="addToCartModal_{{ $product->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form id="form-{{ $product->id }}" class="mb-2">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="product-modal-box d-flex align-items-center mb-3">
                                                        <div class="img mr-3">
                                                            <img src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}"
                                                                alt="" style="width: 80px;">
                                                        </div>
                                                        <div class="p-name">
                                                            <h5 class="title">{{ Str::limit($product['name'], 23) }}</h5>
                                                            <span
                                                                class="mr-2">{{ \App\CPU\Helpers::currency_converter(
                                                                    $product->unit_price - \App\CPU\Helpers::get_product_discount($product, $product->unit_price),
                                                                ) }}</span>
                                                        </div>
                                                    </div>
                                                    @if (count(json_decode($product->colors)) > 0)
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h4>Color</h4>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex">
                                                                    @foreach (json_decode($product->colors) as $key => $color)
                                                                        <div class="v-color-box">
                                                                            <input type="radio"
                                                                                id="{{ $product->id }}-color-{{ $key }}"
                                                                                name="color"
                                                                                value="{{ $color }}"
                                                                                @if ($key == 0) checked @endif>
                                                                            <label style="background: {{ $color }}"
                                                                                for="{{ $product->id }}-color-{{ $key }}"
                                                                                class="color-label"></label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if (count(json_decode($product->choice_options)) > 0)
                                                        @foreach (json_decode($product->choice_options) as $key => $choice)
                                                            <div class="row mb-3">
                                                                <div class="col-12">
                                                                    <h4 style="font-size: 18px; margin:0;">
                                                                        {{ $choice->title }}
                                                                    </h4>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex">
                                                                        @foreach ($choice->options as $key => $option)
                                                                            <div class="v-size-box">
                                                                                <input type="radio"
                                                                                    id="{{ $product->id }}-size-{{ $key }}"
                                                                                    name="{{ $choice->name }}"
                                                                                    value="{{ $option }}"
                                                                                    @if ($key == 0) checked @endif>
                                                                                <label
                                                                                    for="{{ $product->id }}-size-{{ $key }}"
                                                                                    class="size-label">{{ $option }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-md-10 mx-auto">
                                                            <div class="product-quantity d-flex align-items-center">
                                                                <div class="input-group input-group--style-2 pr-3"
                                                                    style="width: 160px;">
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-number" type="button"
                                                                            data-type="minus" data-field="quantity"
                                                                            disabled="disabled" style="padding: 10px">
                                                                            -
                                                                        </button>
                                                                    </span>
                                                                    <input type="text" name="quantity"
                                                                        class="form-control input-number text-center cart-qty-field"
                                                                        placeholder="1" value="1" min="1"
                                                                        max="100">
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-number" type="button"
                                                                            data-type="plus" data-field="quantity"
                                                                            style="padding: 10px">
                                                                            +
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{ route('product', $product->slug) }}"
                                                        class="btn btn-secondary">View Details</a>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="addToCart('form-{{ $product->id }}')">Add To
                                                        Cart</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="row my-3">
            <div class="col">
                <div class="middle-banner">
                    <img style="width: 100%;" src="{{ asset('storage/deal') }}/{{ $landing_page->mid_banner }}"
                        alt="">
                </div>
            </div>
        </div>
        @if (count($landing_products) > 6)
            <div class="row">
                <!-- Product Showcase -->
                <div class="col-md-9">
                    @php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))

                    <div class="row">
                        <!-- Your product columns go here -->
                        @foreach ($landing_products as $key => $product)
                            @if ($key > 5)
                                <div class="col-md-4 mb-3">
                                    <div class="product-box product-box-col-3">
                                        <input type="hidden" name="quantity"
                                            value="{{ $product->minimum_order_qty ?? 1 }}"
                                            min="{{ $product->minimum_order_qty ?? 1 }}" max="100">
                                        <div class="product-image2 product-image2-col-3">
                                            @if ($product->discount > 0)
                                                <div class="discount-box float-end">
                                                    <span>
                                                        @if ($product->discount_type == 'percent')
                                                            {{ round($product->discount, $decimal_point_settings) }}%
                                                        @elseif($product->discount_type == 'flat')
                                                            {{ \App\CPU\Helpers::currency_converter($product->discount) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                            <a href="{{ route('product', $product->slug) }}">
                                                <img class="pic-1"
                                                    src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}">
                                                <img class="pic-2"
                                                    src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}">
                                            </a>
                                            <ul class="social">
                                                <li><a href="{{ route('product', $product->slug) }}"
                                                        data-tip="Quick View"><i class="fa fa-eye"></i></a></li>

                                                <li><a style="cursor: pointer" data-toggle="modal"
                                                        data-target="#addToCartModal_{{ $product->id }}"
                                                        data-tip="Add to Cart"><i class="fa fa-shopping-cart"></i></a>
                                                </li>
                                            </ul>
                                            <button type="button" style="cursor: pointer;" class="buy-now"
                                                onclick="buy_now('form-{{ $product->id }}')">Buy Now</button>
                                        </div>
                                        <div class="product-content">
                                            <h3 class="title"><a
                                                    href="{{ route('product', $product->slug) }}">{{ Str::limit($product['name'], 23) }}</a>
                                            </h3>
                                            <div class="price d-flex justify-content-center align-content-center">
                                                @if ($product->discount > 0)
                                                    <span
                                                        class="mr-2">{{ \App\CPU\Helpers::currency_converter(
                                                            $product->unit_price - \App\CPU\Helpers::get_product_discount($product, $product->unit_price),
                                                        ) }}</span>
                                                    <del>{{ \App\CPU\Helpers::currency_converter($product->unit_price) }}</del>
                                                @else
                                                    <span>{{ \App\CPU\Helpers::currency_converter($product->unit_price) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))
                                        <div class="rating-show justify-content-between text-center">
                                            <span class="d-inline-block font-size-sm text-body">
                                                @for ($inc = 0; $inc < 5; $inc++)
                                                    @if ($inc < $overallRating[0])
                                                        <i class="fa fa-star" style="color:#fea569 !important"></i>
                                                    @else
                                                        <i class="fa fa-star-o" style="color:#fea569 !important"></i>
                                                    @endif
                                                @endfor
                                                <label class="badge-style">( {{ $product->reviews_count }} )</label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-center d-md-none">
                                        <div class="mobile-btn justify-content-center align-items-center">
                                            <button type="button" class="btn btn-warning text-white text-bold"
                                                onclick="buy_now('form-{{ $product->id }}')">Order Now</button>
                                            <a href="{{ route('product', $product->slug) }}" class="btn btn-info">View
                                                Details</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- AddToCart Modal -->
                                <div class="modal fade" id="addToCartModal_{{ $product->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form id="form-{{ $product->id }}" class="mb-2">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="product-modal-box d-flex align-items-center mb-3">
                                                        <div class="img mr-3">
                                                            <img src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $product['thumbnail'] }}"
                                                                alt="" style="width: 80px;">
                                                        </div>
                                                        <div class="p-name">
                                                            <h5 class="title">{{ Str::limit($product['name'], 23) }}</h5>
                                                            <span
                                                                class="mr-2">{{ \App\CPU\Helpers::currency_converter(
                                                                    $product->unit_price - \App\CPU\Helpers::get_product_discount($product, $product->unit_price),
                                                                ) }}</span>
                                                        </div>
                                                    </div>
                                                    @if (count(json_decode($product->colors)) > 0)
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h4>Color</h4>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex">
                                                                    @foreach (json_decode($product->colors) as $key => $color)
                                                                        <div class="v-color-box">
                                                                            <input type="radio"
                                                                                id="{{ $product->id }}-color-{{ $key }}"
                                                                                name="color"
                                                                                value="{{ $color }}"
                                                                                @if ($key == 0) checked @endif>
                                                                            <label style="background: {{ $color }}"
                                                                                for="{{ $product->id }}-color-{{ $key }}"
                                                                                class="color-label"></label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if (count(json_decode($product->choice_options)) > 0)
                                                        @foreach (json_decode($product->choice_options) as $key => $choice)
                                                            <div class="row mb-3">
                                                                <div class="col-12">
                                                                    <h4 style="font-size: 18px; margin:0;">
                                                                        {{ $choice->title }}
                                                                    </h4>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex">
                                                                        @foreach ($choice->options as $key => $option)
                                                                            <div class="v-size-box">
                                                                                <input type="radio"
                                                                                    id="{{ $product->id }}-size-{{ $key }}"
                                                                                    name="{{ $choice->name }}"
                                                                                    value="{{ $option }}"
                                                                                    @if ($key == 0) checked @endif>
                                                                                <label
                                                                                    for="{{ $product->id }}-size-{{ $key }}"
                                                                                    class="size-label">{{ $option }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-md-10 mx-auto">
                                                            <div class="product-quantity d-flex align-items-center">
                                                                <div class="input-group input-group--style-2 pr-3"
                                                                    style="width: 160px;">
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-number" type="button"
                                                                            data-type="minus" data-field="quantity"
                                                                            disabled="disabled" style="padding: 10px">
                                                                            -
                                                                        </button>
                                                                    </span>
                                                                    <input type="text" name="quantity"
                                                                        class="form-control input-number text-center cart-qty-field"
                                                                        placeholder="1" value="1" min="1"
                                                                        max="100">
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-number" type="button"
                                                                            data-type="plus" data-field="quantity"
                                                                            style="padding: 10px">
                                                                            +
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{ route('product', $product->slug) }}"
                                                        class="btn btn-secondary">View Details</a>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="addToCart('form-{{ $product->id }}')">Add To
                                                        Cart</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <!-- Left Side Banner -->
                <div class="col-md-3">
                    <div class="left-banner">
                        {{-- <h4>Special Offer</h4>
                    <p>Get up to 50% off on selected items. Hurry, offer ends soon!</p> --}}
                        <img src="{{ asset('storage/deal') }}/{{ $landing_page->right_side_banner }}" alt="">
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-3 mx-auto">
                <div class="form-group my-3">
                    <a href="{{ route('home') }}" class="btn btn-primary">Get More Products</a>
                </div>
            </div>
        </div>
    </div>
@endsection
