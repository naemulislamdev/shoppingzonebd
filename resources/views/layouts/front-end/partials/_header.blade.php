<header id="header p-0 " style="padding: 0 !important; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
    <div class="container">
        <div class="row main_row align-items-lg-center">
            <div class="col-md-3 col-sm-2 d-none d-lg-flex align-items-center flex-row gap-5 pr-0 pe-0">
                <!-- <a class="navbar-brand" href="index.html">Shopping Zone BD</a> -->
                <a href="{{ route('home') }}">
                    <img class="header-logo" src="{{ asset('storage/company') . '/' . $web_config['web_logo']->value }}"
                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                        alt="{{ $web_config['name']->value }}">
                </a>
                <div class="ml-4">
                    <div class="d-flex align-items-center">
                        <img style="height: 40px; width: auto;"
                            src="{{ asset('assets/front-end/images/logo/whatsapp.png') }}" alt="whatsapp icon">
                        <div class="ms-2">
                            <a target="_blank" title="Go Whatsapp" style="font-size: 16px; font-weight: 600; "
                                class="text-success d-flex align-items-center"
                                href="https://wa.me/8801406667669?text=Is%20anyone%20available%20to%20chat%3F">
                                <span class="ml-1">01406-667669</span>
                            </a>

                            <a target="_blank" title="Go Whatsapp" style="font-size: 16px; font-weight: 600; "
                                class="text-success d-flex align-items-center"
                                href="https://wa.me/8801805035050?text=Is%20anyone%20available%20to%20chat%3F">
                                <span class="ml-1">01805-035050</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-sm-7 px-0 pl-2 ps-2">
                @php $categories = \App\CPU\CategoryManager::parents(); @endphp
                @php
                    $discountOffer = \App\Models\DiscountOffer::where('status', 1)->first();
                @endphp
                <nav class="navbar">
                    <div class="menu-area">
                        <ul>
                            @if ($discountOffer != null)
                                <li><a href="{{ route('discount.offers', ['slug' => $discountOffer->slug ?? '']) }}"><img
                                            style="height: 60px; width: auto;"
                                            src="{{ asset('storage/offer') }}/{{ $discountOffer['image'] }}"
                                            alt="offer image"></a>
                                </li>
                            @endif
                            <li><a href="{{ route('home') }}">{{ \App\CPU\translate('Home') }}</a></li>

                            <li class="dd-btn1"><a href="#">{{ \App\CPU\translate('Categories') }} <i
                                        class="fa fa-angle-down"></i></a>
                                <div class="dropdown-menu1">
                                    <div class="row">
                                        @foreach ($categories as $category)
                                            <div class="col-md-4 mb-2">
                                                <div class="m-category-box">
                                                    <a href="{{ route('category.products', $category->slug) }}">
                                                        <img src="{{ asset("storage/category/$category->icon") }}"
                                                            onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'">
                                                        {{ $category['name'] }} <i
                                                            class="fa fa-angle-right float-right mt-1"></i>
                                                    </a>
                                                </div>

                                                @if ($category->subCategory->count() > 0)
                                                    <div class="s-category-box">
                                                        <ul class="w-nav-list level_3 ml-4">
                                                            @foreach ($category->subCategory as $subCat)
                                                                <li class="s-category"><a
                                                                        href="{{ route('category.products', [$category->slug, $subCat->slug]) }}">{{ $subCat['name'] }}
                                                                    </a>

                                                                    @if ($subCat->childes->count() > 0)
                                                                        <div class="dropdown-menuc">
                                                                            <ul class="w-nav-list level_3 ml-3">
                                                                                @foreach ($subCat->childes as $childCat)
                                                                                    <li><a
                                                                                            href="{{ route('category.products', [$category->slug, $subCat->slug, $childCat->slug]) }}">{{ $childCat['name'] }}</a>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                            @if ($category->childes->count() > 0)
                                                <div class="s-category-box">
                                                    <ul class="w-nav-list level_3 ml-4">
                                                        @foreach ($category['childes'] as $subCategory)
                                                            <li class="s-category"><a
                                                                    href="{{ route('products', ['id' => $subCategory['id'], 'data_from' => 'category', 'page' => 1]) }}">{{ $subCategory['name'] }}
                                                                </a>
                                                                @if ($subCategory->childes->count() > 0)
                                                                    <div class="dropdown-menuc">
                                                                        <ul class="w-nav-list level_3 ml-3">
                                                                            @php
                                                                                $specificSlugs = [
                                                                                    'two-piece',
                                                                                    'three-piece',
                                                                                    'unstitched-three-piece',
                                                                                    'ready-three-piece',
                                                                                ];
                                                                            @endphp @foreach ($subCategory['childes'] as $subSubCategory)
                                                                                @if (in_array($subSubCategory['slug'], $specificSlugs))
                                                                                    <li><a
                                                                                            href="{{ route('products', ['id' => $subSubCategory['id'], 'data_from' => 'category', 'page' => 1]) }}">{{ $subSubCategory['name'] }}</a>
                                                                                    </li>
                                                                                @else
                                                                                    <li><a
                                                                                            href="{{ route('products', ['id' => $subSubCategory['id'], 'data_from' => 'category', 'page' => 1]) }}">{{ $subSubCategory['name'] }}</a>
                                                                                    </li>
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                    </div>
                                    @endforeach
                                </div>
                    </div>
                    </li>
                    <li><a href="{{ route('shop') }}">{{ \App\CPU\translate('Shop') }}</a>
                    </li>
                    <li><a href="{{ route('video_shopping') }}">{{ \App\CPU\translate('video shopping') }}</a>
                    </li>

                    <li><a href="{{ route('offers.product') }}">{{ \App\CPU\translate('Special Offers') }}</a>
                    </li>
                    <li><a href="{{ route('outlets') }}">{{ \App\CPU\translate('Our outlets') }}</a></li>
                    <li><a href="{{ route('careers') }}">{{ \App\CPU\translate('Career') }}</a></li>
                    </ul>
            </div>

            <i class="fa fa-bars menu-icon"></i>
            </nav>
        </div>
        <div class="col-md-2 col-sm-2 ps-0 ">
            <div class="header-icon align-items-center pt-0 mt-0">
                @if ($discountOffer != null)
                    <a class="d-block d-lg-none"
                        href="{{ route('discount.offers', ['slug' => $discountOffer->slug ?? '']) }}"><img
                            style="height: 60px; width: auto;"
                            src="{{ asset('storage/offer') }}/{{ $discountOffer['image'] }}" alt="offer image"></a>
                @endif
                <a data-bs-toggle="offcanvas" href="#searchOffcanvas" role="button" aria-controls="searchOffcanvas"><i
                        class="fa fa-search" aria-hidden="true"></i></a>
                <a href="{{ route('wishlists') }}"><i class="fa fa-heart-o" aria-hidden="true"></i>
                    <span
                        class="badge badge-danger countWishlist">{{ session()->has('wish_list') ? count(session('wish_list')) : 0 }}</span></a>
                <a data-bs-toggle="offcanvas" href="#shoppingCartOffcanvas" role="button"
                    aria-controls="shoppingCartOffcanvas"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span
                        class="badge badge-danger" id="total_cart_count">
                        {{ session()->has('cart') ? count(session()->get('cart')) : 0 }}
                    </span></a>
                @if (auth('customer')->check())
                    <a href="{{ route('user-account') }}" class="d-lg-none"><i class="fa fa-user"
                            aria-hidden="true"></i></a>
                @else
                    <a href="{{ route('customer.auth.login') }}" class="d-lg-none"><i class="fa fa-user"
                            aria-hidden="true"></i></a>
                @endif
            </div>
        </div>
    </div>
    </div>
</header>


<?php
$company_mobile_logo = \App\Model\BusinessSetting::where('type', 'company_mobile_logo')->first()->value;
?>

<!--end header-->
<!--start mobile menu-->
<div class="mobile-menu">
    <div class="mm-logo" style="background: #fff; padding: 11px 18px;">
        <a href="{{ route('home') }}">
            <img style="width:220px;" src="{{ asset("storage/company/$company_mobile_logo") }}"
                onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'" alt="">
        </a>
        <div class="mm-cross-icon">
            <i class="fa fa-times mm-ci"></i>
        </div>
    </div>
    <div class="mm-menu">
        <div class="accordion" id="accordionExample">
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('home') }}"><i
                            class="fa fa-ptab3 mr-2"></i>{{ \App\CPU\translate('Home') }}</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link" id="headingOne">
                    <a class="mmenu-btn menu-link-active" type="button" data-toggle="collapse"
                        data-target="#categories" aria-expanded="true"><i
                            class="fa fa-ptab3 mr-2"></i>{{ \App\CPU\translate('Categories') }}<i
                            class="fa fa-plus"></i></a>
                </div>
                <div id="categories" class="menu-body collapse" aria-labelledby="headingOne"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <ul>
                            @foreach ($categories as $category)
                                <li class="mega-dd-btn-2">
                                    <div class="menu-link d-flex justify-content-between">
                                        <a
                                            href="{{ route('category.products', $category->slug) }}">{{ $category['name'] }}</a>
                                        <a data-toggle="collapse" type="button"
                                            data-target="#category__{{ $category['id'] }}" aria-expanded="true"><i
                                                class="fa fa-plus"></i></a>
                                    </div>
                                    @if ($category->subCategory->count() > 0)
                                        <div class="collapse" id="category__{{ $category['id'] }}">
                                            <div class="card card-body">
                                                <ul class="mega-item">
                                                    @foreach ($category->subCategory as $subCat)
                                                        <li class="mega-dd-btn-2">
                                                            <div class="menu-link d-flex justify-content-between">
                                                                <a
                                                                    href="{{ route('category.products', [$category->slug, $subCat->slug]) }}">{{ $subCat['name'] }}</a>
                                                                <a type="button" data-toggle="collapse"
                                                                    data-target="#subCategory__{{ $subCat['id'] }}"
                                                                    aria-expanded="true"><i
                                                                        class="fa fa-plus"></i></a>
                                                            </div>
                                                            @if ($subCat->childes->count() > 0)
                                                                <div class="collapse"
                                                                    id="subCategory__{{ $subCat['id'] }}">
                                                                    <div class="card card-body">
                                                                        <ul class="mega-item">
                                                                            @foreach ($subCat->childes as $childCat)
                                                                                <li><a
                                                                                        href="{{ route('category.products', [$category->slug, $subCat->slug, $childCat->slug]) }}">{{ $childCat['name'] }}</a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('shop') }}"><i class="fa fa-ptab3 mr-2"></i>
                        {{ \App\CPU\translate('Shop') }}</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('video_shopping') }}"><i class="fa fa-ptab3 mr-2"></i>
                        {{ \App\CPU\translate('Video Shopping') }}</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('offers.product') }}"><i
                            class="fa fa-ptab3 mr-2"></i>{{ \App\CPU\translate('Special Offers') }}</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('outlets') }}"><i
                            class="fa fa-ptab3 mr-2"></i>{{ \App\CPU\translate('Our outlets') }}</a>
                </div>
            </div>
            <div class="menu-box">
                <div class="menu-link">
                    <a href="{{ route('careers') }}"><i
                            class="fa fa-ptab3 mr-2"></i>{{ \App\CPU\translate('Career') }}</a>
                </div>
            </div>
            <div class="menu-box mt-2 text-white">
                <div class="menu-link">
                    @if (auth('customer')->check())
                        <a href="{{ route('user-account') }}" class="btn btn-primary"><i
                                class="fa fa-ptab3 mr-2"></i>
                            {{ \App\CPU\translate('User Dashboard') }}</a>
                    @else
                        <a href="{{ route('customer.auth.login') }}" class="btn btn-primary"><i
                                class="fa fa-ptab3 mr-2"></i>{{ \App\CPU\translate('Login') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
