@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Discount Offers Create'))

@push('css_or_js')
    <link href="{{ asset('assets/back-end/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between mb-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Discount Offers Create') }}</li>
                </ol>
            </nav>
            <a href="{{ route('admin.discount.discount-offers') }}"
                class="btn btn-primary">{{ \App\CPU\translate('Back') }}</a>
        </div>

        <!-- Content Row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('Batch Discount Form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.discount.discount-offers.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control">
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ \App\CPU\translate('Upload Offer Image') }}</label><small
                                                        style="color: red">*
                                                        ( {{ \App\CPU\translate('ratio') }} 1:1 )</small>
                                                    <div class="custom-file" style="text-align: left">
                                                        <input type="file" name="image" id="customFileEg1"
                                                            class="custom-file-input" accept="image/*"
                                                            onchange="previewImage(event)">

                                                        <label class="custom-file-label"
                                                            for="customFileEg1">{{ \App\CPU\translate('choose') }}
                                                            {{ \App\CPU\translate('file') }}</label>
                                                    </div>
                                                    @error('image')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">

                                                        <img onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                            style="width: 100px;height:auto;border: .0625rem solid; border-radius: .625rem;"
                                                            id="preview" src="" alt="image" />

                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Product <span class="text-danger">*</span></label>
                                            <select name="product_ids[]" id="productSelect"
                                                class="js-example-responsive form-control" multiple>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                        data-code="{{ $product->code }}"
                                                        data-price="{{ \App\CPU\BackEndHelper::usd_to_currency($product['unit_price']) }}">
                                                        {{ $product->code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('product_ids.*')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ \App\CPU\translate('SL') }}</th>
                                            <th>{{ \App\CPU\translate('Product Name') }}</th>
                                            <th>{{ \App\CPU\translate('Product Code') }}</th>
                                            <th>{{ \App\CPU\translate('Product Price') }}</th>
                                            <th>{{ \App\CPU\translate('Discount') }}</th>
                                            <th>{{ \App\CPU\translate('Discount Type') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-table-body"></tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class=" pl-0">
                                <button type="submit"
                                    class="btn btn-primary float-right">{{ \App\CPU\translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/back-end') }}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            const $select = $("#productSelect");
            const $tableBody = $("#product-table-body");

            // Initialize Select2
            $select.select2({
                width: "resolve",
                placeholder: "Select products",
            });

            // When selection changes
            $select.on("change", function() {
                const selectedOptions = $(this).find("option:selected");
                const selectedIds = selectedOptions.map((_, opt) => $(opt).val()).get();

                // Clear current table rows
                $tableBody.empty();

                // Add rows for selected products
                selectedOptions.each(function(index) {
                    const id = $(this).val();
                    const name = $(this).data("name");
                    const code = $(this).data("code");
                    const price = $(this).data("price");

                    const row = `
                <tr data-id="${id}">
                    <td>${index + 1}</td>
                    <td>${name}</td>
                    <td>${code}</td>
                    <td>${price}</td>
                    <td>
                        <input type="number" name="discount_amounts[${id}]" class="form-control" placeholder="Enter amount">
                    </td>
                    <td>
                        <select name="discount_types[${id}]" class="form-control">
                            <option value="flat">Flat</option>
                            <option value="percent">Percentage</option>
                        </select>
                    </td>
                </tr>
            `;
                    $tableBody.append(row);
                });
            });
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
        });
    </script>
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const preview = document.getElementById('preview');

            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush
