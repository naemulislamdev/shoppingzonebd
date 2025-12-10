@extends('layouts.front-end.app')
<style>
    section.category .owl-nav,
    .new-arrivals-section .owl-nav {
        display: block !important;
    }

    .owl-nav button {
        position: absolute;
        top: 45%;
        transform: translateY(-50%);
        background: rgb(255 94 0 / 54%) !important;
        color: #fff;
        border: none;
        outline: none;
        border-radius: 5px;
        width: 40px;
        height: 50px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;

    }

    .owl-nav button:hover {
        background-color: #ff5d00 !important;
        color: #fff;
    }

    .owl-nav button:focus {
        border: none !important;
        outline: none !important;
        outline: none !important;
    }

    section.category .owl-nav .owl-prev,
    .new-arrivals-section .owl-nav .owl-prev {

        top: 54% !important;
    }

    section.category .owl-nav .owl-next,
    .new-arrivals-section .owl-nav .owl-next {

        top: 54%;
    }

    section.category .owl-nav button,
    .new-arrivals-section .owl-nav button {
        height: 40px;
    }

    .category .card {
        border-radius: 12px;
    }

    .category .card-body {
        padding: 5px 0;
    }

    .category .card img {
        border-radius: 12px 12px 0 0;
    }

    section.category .card-title {
        font-family: jost;
        font-size: 20px;
        font-weight: 600;
    }

    section.category .card-text {
        font-family: jost;
        font-size: 14px;
        margin-top: 5px;
    }

    /* .category .category-carosel img {
        height: 200px;
    } */

    /*new arrival slider */
    .owl-carousel {
        position: relative;
    }

    .owl-nav .owl-prev {
        left: 4px;
    }

    .owl-nav .owl-next {
        right: -4px;
    }

    .owl-nav button.disabled {
        background: #555 !important;
        opacity: 0.5;
        cursor: not-allowed;
    }



    .new-arrivals-section .product-box>.product-image2>a>img {
        width: 100% !important;
        height: auto;
        object-fit: contain;
        display: block;
        margin: 0 auto !important;
    }

    .section__title span {
        position: absolute;
        color: #f0f1f3;
        left: 0;
        right: 0;
        z-index: -1;
        font-weight: 700;
        font-size: 3rem;
        text-transform: uppercase;
        line-height: 0;
        font-family: Open Sans, sans-serif;
    }

    .btn-warning {
        color: #fff !important;
        background-color: #f26d21 !important;
    }

    .btn-warning:hover {
        color: #fff;
        background-color: #f26d21 !important;
    }

    section.category .category-item .image-box {
        height: 200px;
    }

    .owl-carousel .owl-item img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top;
    }

    /* Mobile Category styles*/
    section.mobile-category .category-item img {
        max-width: 100%;
        height: auto;
    }

    section.mobile-category .category-item h5 {
        font-size: 15px;
    }

    section.mobile-category .category-item p {
        font-size: 12px;
        margin-top: 5px;
    }

    section.mobile-category .category-item {
        background: #f26d21;
        height: 170px !important;
    }

    section.mobile-category .category-item .image-box {
        height: 120px;
    }

    section.mobile-category .category-item .image-box img {
        height: 100%;
        width: 100%;

    }

    section.top-banner .big-banner {
        height: 200px;
        width: 100%;
    }

    @media (max-width: 768px) {
        section.top-banner .big-banner {
            height: auto;
            width: 100%;
        }
    }
</style>
@section('title', \App\CPU\translate('Welcome To') . ' ' . $web_config['name']->value)

@push('css_or_js')
    <meta property="og:image" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="og:title" content="Best Online Marketplace In Bangladesh {{ $web_config['name']->value }} Home" />
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

    <meta property="twitter:card" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="twitter:title" content="Welcome To {{ $web_config['name']->value }} Home" />
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">
@endpush
@section('content')


    @include('layouts.front-end.partials._modals')
    <!------start  header main slider-->
    @include('layouts.front-end.partials.slider')
    {{-- New Arrivals Section Start --}}
    <section class="new-arrivals-section my-2 my-lg-5">
        <div class="container">
            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title position-relative z-30">
                        <h3>NEW ARRIVALS</h3>
                        <div class="heading-border"></div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel related-products product-carosel mt-4 mt-lg-4" data-delay="3000">
                @php $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'); @endphp


                @if ($arrival_products->count() > 0)
                    <!-- Your product columns go here -->
                    @foreach ($arrival_products as $product)
                        <div class="product-column"
                            data-category="{{ $dataCategory ?? '' }}">
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
                                   
                                </div>
                                <div class="product-content">
                                    <h3 class="title"><a
                                            href="{{ route('product', $product->slug) }}">{{ Str::limit($product['name'], 50) }}</a>
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
                                    <button type="button" style="cursor: pointer;" class="btn btn-primary"
                                        onclick="buy_now('form-{{ $product->id }}')">অর্ডার করুন</button>
                                </div>
                            </div>
                        </div>
                        <!-- AddToCart Modal -->

                    @endforeach

                @endif

            </div>
            <div class="d-flex justify-content-center">
                <a href="" class="btn btn-outline-warning  text-center  d-block d-lg-none">Shop More</a>
            </div>
    </section>



    {{-- Category Section Start --}}
    <section class="category my-5 d-none d-lg-block">
        <div class="container px-0">
            <div class="row mb-5">
                <div class="col text-center">
                    <div class="section-heading-title position-relative z-30">
                        <h3>Shop By Category</h3>
                        <div class="heading-border"></div>

                    </div>
                </div>
            </div>
            <div class="owl-carousel category-carosel mt-4 mt-lg-4 ">
                @foreach ($categories as $category)
                    @php
                        $productCount = $productCounts[$category->id] ?? 0;
                    @endphp
                    <div style="background: #f26d21" class="category-item card">
                        <a href="{{ route('products', ['id' => $category['id'], 'data_from' => 'category', 'page' => 1]) }}"
                            class="">
                            <img src='{{ asset("storage/category/$category->icon") }}' alt="{{ $category->name }}">
                            <div class="card-body text-center">
                                <h5 class="card-title text-white mb-0">{{ $category['name'] }}</h5>
                                <p class="card-text text-white p-0 m-0">{{ $productCount }} Products</p>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    {{-- mobile category --}}
    <section class="mobile-category mt-4 d-block d-lg-none">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="section-heading-title position-relative z-30">
                        <h3>Shop By Category</h3>
                        <div class="heading-border"></div>

                    </div>
                </div>
            </div>
            <div class="row gap-0">
                @foreach ($categories as $category)
                    @php
                        $productCount = $productCounts[$category->id] ?? 0;
                    @endphp
                    <div class="col-4 px-2 pb-3">
                        <div style="background: #f26d21" class="category-item card">
                            <a href="{{ route('products', ['id' => $category['id'], 'data_from' => 'category', 'page' => 1]) }}"
                                class="stretched-link">
                                <div class="image-box">
                                    <img src="{{ asset("storage/category/$category->icon") }}"
                                        alt="{{ $category->name }}">
                                </div>
                                <div class="card-body text-center p-1">
                                    <h5 class="card-title text-white mb-0">{{ $category['name'] }}</h5>
                                    <p class="card-text text-white p-0 m-0 text-dark">{{ $productCount }} Products</p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>

    {{-- To Banner Section Start --}}
    <section class="top-banner">
        <div class="container-fluid">
            @foreach (\App\Model\Banner::where('banner_type', 'Main Section Banner')->where('published', 1)->orderBy('id', 'desc')->take(3)->get() as $banner)
                <div class="row my-3">
                    <div class="col-md-12">
                        <div class="big-banner">
                            <a href="{{ $banner['url'] }}">
                                <img style="max-height: 100%; width: 100%;"
                                    onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                    src="{{ asset('storage/banner') }}/{{ $banner['photo'] }}"
                                    alt="{{ @$banner['photo'] }}" width="100%;">
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    {{-- Top Banner Section End --}}
    {{-- mobile category --}}
    {{-- Category Section End --}}
    <!------Start Product section----->
    <section class="py-3">
        <div class="container">
            {{-- @include('layouts.front-end.partials.product_filter') --}}

            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h3>Our Latest Product</h3>
                        <div class="heading-border"></div>
                    </div>
                    <div class="grid-controls">
                        <button class="grid-btn" data-columns="6" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="4" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="3" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="2" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                    </div>
                    <div class="grid-controls mobile-grid-controls">
                        <button class="grid-btn grid-btn-mobile" data-columns="12" data-category="category1">
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn grid-btn-mobile" data-columns="6" data-category="category1">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                    </div>
                </div>
            </div>
            @php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
            @if ($featured_products->count() > 0)
                <div class="row product-grid">
                    <!-- Your product columns go here -->
                    @foreach ($featured_products as $product)
                        @include('web-views.products.product_box', ['dataCategory' => 'category1'])
                    @endforeach
                </div>
            @endif

            @foreach ($home_categories as $category)
                <div class="row mb-3">
                    <div class="col text-center">
                        <div class="section-heading-title">
                            <h3>{{ Str::limit($category['name'], 18) }}</h3>
                            <div class="heading-border"></div>
                        </div>
                        <div class="grid-controls">
                            <button class="grid-btn" data-columns="6" data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                            <button class="grid-btn" data-columns="4" data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                            <button class="grid-btn" data-columns="3" data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                            <button class="grid-btn" data-columns="2" data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                        </div>
                        <div class="grid-controls mobile-grid-controls">
                            <button class="grid-btn grid-btn-mobile" data-columns="12"
                                data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                            </button>
                            <button class="grid-btn grid-btn-mobile" data-columns="6"
                                data-category="category_{{ $category->id }}">
                                <div class="grid-icon"></div>
                                <div class="grid-icon"></div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row product-grid">
                    <!-- Your product columns go here -->
                    @foreach ($category['products'] as $key => $product)
                        @if ($key < 12)
                            @include('web-views.products.product_box', [
                                'dataCategory' => "category_$category->id",
                            ])
                        @endif
                    @endforeach
                </div>
            @endforeach
            @foreach (\App\Model\Banner::where('banner_type', 'Footer Banner')->where('published', 1)->orderBy('id', 'desc')->take(3)->get() as $banner)
                <div class="row my-3">
                    <div class="col-md-12">
                        <div class="big-banner">
                            <a href="{{ $banner['url'] }}">
                                <img onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                    src="{{ asset('storage/banner') }}/{{ $banner['photo'] }}"
                                    alt="{{ @$banner['photo'] }}" width="100%;">
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Start customer review Section -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="row my-3">
                        <div class="col text-center">
                            <div class="section-heading-title mb-4 mb-lg-5">
                                <h5>Customers Review</h5>
                                <h3>What our Clients say</h3>
                                <div class="heading-border"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <a data-bs-toggle="offcanvas" href="#clientReviewAdd" role="button"
                                aria-controls="clientReviewAdd" class="btn btn-sm btn-primary float-right mr-1">Write a
                                review <i class="fa fa-pencil-square-o"></i> </a>
                        </div>
                        @include('layouts.front-end.partials.client_review_canvas')
                    </div>
                    <div class="c-review-slider owl-carousel owl-theme">
                        <?php $clientReviews = \App\ClientReview::where('status', true)->get(); ?>

                        @foreach ($clientReviews as $review)
                            <div class="item">
                                <div class="customer-review-box text-center">
                                    @if ($review->gender == 'male')
                                        <img src="{{ asset('assets/front-end') }}/images/slider/customer-review/smale.png"
                                            alt="">
                                    @elseif($review->gender == 'female')
                                        <img src="{{ asset('assets/front-end') }}/images/slider/customer-review/sfemale.png"
                                            alt="">
                                    @else
                                        <img src="{{ asset('assets/front-end') }}/images/slider/customer-review/img1.jpg"
                                            alt="">
                                    @endif
                                    <div class="customer-name mt-2">
                                        <h3>{{ htmlspecialchars($review['name']) }}</h3>
                                    </div>
                                    <div class="customer-sms">
                                        <p>{{ htmlspecialchars($review['comment']) }}</p>
                                    </div>
                                    <div class="customer-review">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="fa fa-star{{ $i < $review->ratings ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Start Newslater Section -->
    <section class="newslater-section">
        <div class="container">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="newslater-text">
                                <h4>SALE 20% OFF ALL STORE</h4>
                                <h2>Subscribe our Newsletter</h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="newslater-input">
                                <form action="{{ route('subscription') }}" method="post">
                                    @csrf
                                    <div class="input-group mb-3 w-100">
                                        <input type="text" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Your Email Address') }}"
                                            name="subscription_email" required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-dark"
                                                id="basic-addon2">{{ \App\CPU\translate('subscribe') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        cartQuantityInitialize();
    </script>
    <script>
        $(document).ready(function() {
            $('#close-pModal').on('click', function() {
                $('#popup-modal').modal('hide');
            });
        });
    </script>
@endpush
