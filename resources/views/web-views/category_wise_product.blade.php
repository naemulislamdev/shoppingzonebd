@extends('layouts.front-end.app')
@section('title', ucfirst($data['data_from']) . ' products')
@push('css_or_js')
    <meta property="og:image" content="{{ asset('storage/company') }}/{{ $web_config['web_logo'] }}" />
    <meta property="og:title" content="Products of {{ $web_config['name'] }} " />
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

    <meta property="twitter:card" content="{{ asset('storage/company') }}/{{ $web_config['web_logo'] }}" />
    <meta property="twitter:title" content="Products of {{ $web_config['name'] }}" />
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">
@endpush
@section('content')
    <section class="py-3">
        <div class="container">
            {{-- Product Filter section --}}
            @include('layouts.front-end.partials.product_filter')
            {{-- Product grid system section --}}
            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h3>{{str_replace("_"," ",$data['data_from'])}} {{\App\CPU\translate('products')}} {{ isset($brand_name) ? '('.$brand_name.')' : ''}}</h3>
                        <div class="heading-border"></div>
                    </div>
                    <div class="grid-controls">
                        <button class="grid-btn" data-columns="6" data-category="category">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="4" data-category="category">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="3" data-category="category">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn" data-columns="2" data-category="category">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                    </div>
                    <div class="grid-controls mobile-grid-controls">
                        <button class="grid-btn grid-btn-mobile" data-columns="12" data-category="category">
                            <div class="grid-icon"></div>
                        </button>
                        <button class="grid-btn grid-btn-mobile" data-columns="6" data-category="category">
                            <div class="grid-icon"></div>
                            <div class="grid-icon"></div>
                        </button>
                    </div>
                </div>
            </div>

            @if (count($products) > 0)
                <div class="row product-grid" id="ajax-products">
                    <!-- Your product columns go here -->
                        @include('web-views.products._ajax-products',['products'=>$products])
                </div>
                <hr class="my-3">
                <!-- Pagination-->
                {{-- {{ count($products) }} --}}
                <div class="row">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <nav class="d-flex justify-content-between pt-2" aria-label="Page navigation">
                            {!! $products->links() !!}
                        </nav>
                    </div>
                </div>
            @else
                <div class="text-center pt-5">
                    <h2>Product Coming Soon!</h2>
                </div>
            @endif

            {{-- <div class="row my-3">
                <div class="col-md-12">
                    <div class="big-banner">
                        <img src="{{ asset('asstes/front-end') }}/images/product-banner/main-banner3.jpg"
                            alt="">
                    </div>
                </div>
            </div> --}}
        </div>
    </section>
@endsection
@push('scripts')
<script>
window.dataLayer = window.dataLayer || [];

/* Product List View (Category Page) */
dataLayer.push({
    event: "view_item_list",
    ecommerce: {
        item_list_id: "category_{{ $category->slug ?? 'default' }}",
        item_list_name: "{{$data['data_from'] ?? 'Product List' }}",
        items: [
            @foreach ($products as $index => $product)
            {
                item_id: "{{ $product->id }}",
                item_name: "{{ $product->name }}",
                item_brand: "{{$brand_name ?? 'Unknown' }}",
                item_category: "{{ $brand_name ?? 'General' }}",
                item_variant: "{{ $product->variant ?? 'Default' }}",
                price: "{{ \App\CPU\Helpers::currency_converter($product->unit_price) }}",
                currency: "BDT",
                index: {{ $loop->iteration }}
            }@if (!$loop->last),@endif
            @endforeach
        ]
    }
});
</script>
    <script>

        function filter(value) {
            $.get({
                url: '{{ url('/') }}/products',
                data: {
                    id: '{{ $data['id'] }}',
                    name: '{{ $data['name'] }}',
                    data_from: '{{ $data['data_from'] }}',
                    min_price: '{{ $data['min_price'] }}',
                    max_price: '{{ $data['max_price'] }}',
                    sort_by: value
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    $('#ajax-products').html(response.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function searchByPrice() {
            let min = $('#min_price').val();
            let max = $('#max_price').val();
            $.get({
                url: '{{url('/')}}/products',
                data: {
                    id: '{{$data['id']}}',
                    name: '{{$data['name']}}',
                    data_from: '{{$data['data_from']}}',
                    sort_by: '{{$data['sort_by']}}',
                    min_price: min,
                    max_price: max,
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    $('#ajax-products').html(response.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        $("#search-brand").on("keyup", function() {
            var value = this.value.toLowerCase().trim();
            $("#lista1 div>li").show().filter(function() {
                return $(this).text().toLowerCase().trim().indexOf(value) == -1;
            }).hide();
        });
        $("#search-brand-m").on("keyup", function() {
            var value = this.value.toLowerCase().trim();
            $("#lista1 div>li").show().filter(function() {
                return $(this).text().toLowerCase().trim().indexOf(value) == -1;
            }).hide();
        });
    </script>
    <script>
        cartQuantityInitialize();
    </script>
@endpush
