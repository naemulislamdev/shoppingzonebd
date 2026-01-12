@php
    $promoOffers = \App\Model\Banner::where('banner_type', 'promo_offer')
        ->where('published', 1)
        ->orderBy('id', 'desc')
        ->get();

    $total = $promoOffers->count();
    $half = ceil($total / 2);

    $left_promo_offers = $promoOffers->take($half);
    $right_promo_offers = $promoOffers->slice($half);
@endphp
<section class="header-slider-section ">
    @php($main_banner = \App\Model\Banner::where('banner_type', 'Main Banner')->where('published', 1)->orderBy('id', 'desc')->get())

    <div class="container">
        <div class="row justify-content-center">
            <div class="@if ($left_promo_offers->count() > 0) col-2 @endif">
                @if ($left_promo_offers->count() > 0)
                    <div class="left-promo">
                        @foreach ($left_promo_offers as $promo)
                            <a class="text-center" href="{{ $promo['url'] }} ">
                                <img style="max-width: 100%; max-height: 350px;"
                                    src="{{ asset('storage/banner') }}/{{ $promo['photo'] }}" alt="promo image"
                                    class="img-fluid">
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>
            <div class=" px-0 @if ($total > 0) col-8 @else col-12 @endif">
                <div id="carouselExampleIndicators" class="carousel slide position-relative container "
                    data-ride="carousel" data-interval="3000">
                    <ol class="carousel-indicators">
                        @foreach ($main_banner as $key => $banner)
                            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                                class="{{ $key == 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                    <div class="carousel-inner">
                        @foreach ($main_banner as $key => $banner)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <div class="main-slider  ">
                                    <a href="{{ $banner['url'] }}">
                                        <img class="d-block w-100" style="border-radius: 15px ;"
                                            onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                            src="{{ asset('storage/banner') }}/{{ $banner['photo'] }}"
                                            alt="slider image">
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
            </div>
            <div class="@if ($right_promo_offers->count() > 0) col-2 @endif ">
                @if ($right_promo_offers->count() > 0)
                    <div class="right-promo">
                        @foreach ($right_promo_offers as $promo)
                            <a class="text-center" href="{{ $promo['url'] }}">
                                <img style="max-width: 100%; max-height: 350px;"
                                    src="{{ asset('storage/banner') }}/{{ $promo['photo'] }}" alt="promo image"
                                    class="img-fluid">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
