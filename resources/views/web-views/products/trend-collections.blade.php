@extends('layouts.front-end.app')
@section('title', 'Trend-collections')

<style>
    @import url('https://fonts.maateen.me/solaiman-lipi/font.css');

    .trending a,
    .btn,
    span.tk {
        font-family: 'SolaimanLipi', sans-serif;
    }

    .btn-orange {
        background: #ff5d00 !important;
        color: #fff !important;
    }

    .btn.btn-orange:focus {
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(255, 93, 0, .25);
    }

    .product-img-container {
        height: 390px;
        overflow: hidden;
        position: relative;
        /* box-sizing: content-box */
    }

    .product-img-container img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        position: absolute;
        border-radius: 10px 10px 0 0;
        transition: transform 0.4s ease;
    }

    .product-card {
        border: none;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        border-radius: 10px !important;
        transition: 0.5s ease;
    }

    .product-card.card {
        border: none !important;
    }

    .product-title {
        font-size: 18px;
        font-weight: 600;
        line-height: 1.3;
        margin-bottom: 10px;
        color: #1a1a1a;
    }

    .product-text {
        color: #ff5d00;
        font-size: 18px;
        font-weight: 600;
        line-height: 1.5;
        text-align: center;
        overflow: hidden;
    }

    .product-img-container .add-to-cart {
        position: absolute;
        left: 0;
        bottom: -32px;
        width: 100%;
        border: none;
        padding: 4px;
        background: #ff5d00;
        color: #fff;
        cursor: pointer;
        transition: all 0.4s ease;
    }

    .product-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .product-card:hover .product-img-container img {
        transform: scale(1.1);
    }

    .product-card:hover .product-img-container .add-to-cart {
        bottom: 0;
    }

    .product-card:hover .product-title {
        color: #ff5d00;
    }

    .owl-nav button:focus {
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(255, 93, 0, .25);
    }

    .owl-nav button {
        position: absolute;
        top: 45%;
        transform: translateY(-50%);
        background: #ff5d00 !important;
        color: #fff !important;
        border: none;
        outline: none;
        border-radius: 5px;
        width: 40px;
        height: 40px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .owl-carousel .owl-nav button.owl-next {
        right: 0;
        top: 50%;
    }

    .owl-carousel .owl-nav button.owl-prev {
        left: 0;
        top: 50%;
    }


    @media (max-width: 768px) {
        .product-title {
            color: #ff5d00;
            font-size: 15px;
        }

        .product-img-container img {
            object-fit: cover;
        }

        .product-img-container {
            height: 280px;
            overflow: hidden;
            position: relative;
        }

        .product-text {
            font-size: 15px;
        }
    }
</style>

@section('content')
    {{-- =========================== Banner Section Start ===================== --}}
    @if ($main_banners)
        <section class="header-slider-section mt-1 mt-lg-3">
            <div id="carouselExampleIndicators" class="carousel slide position-relative container " data-ride="carousel"
                data-interval="3000">
                <ol class="carousel-indicators">
                    @foreach ($main_banners as $key => $banner)
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                            class="{{ $key == 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($main_banners as $key => $banner)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="main-slider  ">
                                <a href="">
                                    <img class="d-block w-100 rounded-0 rounded-lg"
                                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                        src="{{ asset('storage/deal/main-banner') }}/{{ $banner }}" alt="">
                                </a>
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
    @endif

    {{-- =========================== Banner Section End ===================== --}}
    <section class="my-3 trending career">
        <div class="container " style="max-width: 1200px; ">
            <div class="row ">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h3>New Trending Collections</h3>
                        <div class="heading-border"></div>
                    </div>
                </div>
            </div>
            <div class="d-none d-lg-block pt-4">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-12 mb-4 mx-auto">
                        <div class="card shadow-lg product-card">
                            <div class="product-img-container">
                                <a href="#">
                                    <img class="card-img-top"
                                        src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $first_product['thumbnail'] }}"
                                        alt="Product Image">
                                </a>
                                <div class="text-center">
                                    <button onclick="click()" class="btn add-to-cart"><i
                                            class="fa fa-cart-plus mr-3"></i>কার্টে যোগ করুন</button>
                                </div>
                            </div>

                            <div class="card-body">
                                <a href="{{ route('product', $first_product->slug) }}">
                                    <h4 class="product-title">
                                        {{ Str::limit($first_product->name, 50) }}
                                    </h4>
                                </a>
                                @if ($first_product->discount > 0)
                                    <span
                                        class="product-text">৳ {{ \App\CPU\Helpers::currency_converter(
                                            $first_product->unit_price - \App\CPU\Helpers::get_product_discount($first_product, $first_product->unit_price),
                                        ) }}</span>
                                    <del>৳ {{ \App\CPU\Helpers::currency_converter($first_product->unit_price) }}</del>
                                @else
                                    <span class="product-text">৳ {{ \App\CPU\Helpers::currency_converter($first_product->unit_price) }}</span>
                                @endif

                            </div>
                            <div style="gap: 10px"
                                class="sm-button d-flex justify-content-center justify-content-lg-between p-3 ">
                                <a href="{{ route('product', $first_product->slug) }}" class="btn btn-sm btn-info text-white"><i class="fa fa-eye mr-2"></i>বিস্তারিত দেখুন</a>
                                <button class="btn btn-sm btn-orange text-white" onclick="buy_now('form-{{ $first_product->id }}')"><i class="fa fa-cart-plus mr-2"></i>অর্ডার
                                    করুন</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Mobile trending  --}}
            <div class="d-block d-lg-none pt-4">
                <div class="owl-carousel trending-carousel mt-4 mt-lg-4">
                    @foreach ([4, 3, 4, 2, 6, 6] as $i)
                        <div class="card shadow-lg product-card">

                            <div class="product-img-container">
                                <a href="#">
                                    <img class="card-img-top"
                                        src="{{ asset('assets/front-end/images/product/2025-05-22-682f30619bd5a.png') }}"
                                        alt="Product Image">
                                </a>

                                <div class="text-center">
                                    <button onclick="click()" class="btn add-to-cart">
                                        <i class="fa fa-cart-plus mr-3"></i> কার্টে যোগ করুন
                                    </button>
                                </div>
                            </div>

                            <div class="card-body pb-0">
                                <a href="#">
                                    <h4 class="product-title">
                                        {{ Str::limit('Ready Three Piece – Luxury Cotton Collection | Shopping Zone BD | Style #G1899LF', 50) }}
                                    </h4>
                                </a>

                                <p class="product-text">
                                    2,650.00 <span class="fw-bold">৳</span>
                                </p>
                            </div>

                            <div class="sm-button text-center d-flex flex-column gap-2 mx-4 pb-3">

                                <a class="btn btn-sm btn-info text-white w-100 mr-2">
                                    <i class="fa fa-eye"></i> বিস্তারিত দেখুন
                                </a>

                                <button class="btn btn-sm btn-orange text-white w-100 mt-2 mr-3">
                                    <i class="fa fa-cart-plus"></i> অর্ডার করুন
                                </button>

                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row">
                @foreach ($subProducts as $key => $item)
                    <div class="col-lg-3 col-md-4 col-sm-12 mb-4">
                        <div class="card shadow-lg product-card">
                            <div class="product-img-container">
                                <a href="#">
                                    <img class="card-img-top"
                                        src="{{ asset('assets/front-end/images/product/2025-05-22-682f30619bd5a.png') }}"
                                        alt="Product Image">
                                </a>
                                <div class="text-center">
                                    <button onclick="click()" class="btn add-to-cart"><i
                                            class="fa fa-cart-plus mr-3"></i>কার্টে
                                        যোগ করুন</button>
                                </div>
                            </div>

                            <div class="card-body">
                                <a href="">
                                    <h4 class="product-title">
                                        {{-- {{ Str::limit($item->name, 50) }} --}}
                                    </h4>
                                </a>
                                <p class="product-text">
                                    2,650.00 <span class="fw-bold tk">৳</span>
                                </p>

                            </div>
                            <div style="gap: 10px"
                                class="sm-button d-flex justify-content-center justify-content-lg-between p-3 ">
                                <a class="btn btn-sm btn-info text-white"><i class="fa fa-eye mr-2"></i>বিস্তারিত
                                    দেখুন</a>
                                <button class="btn btn-sm btn-orange text-white"><i
                                        class="fa fa-cart-plus mr-2"></i>অর্ডার
                                    করুন</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
