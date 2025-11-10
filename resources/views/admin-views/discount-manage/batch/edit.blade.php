@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Batch Discount'))

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
                    <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Batch Discount Create') }}</li>
                </ol>
            </nav>
            <a href="{{ route('admin.discount.batch') }}" class="btn btn-primary">{{ \App\CPU\translate('Back') }}</a>
        </div>

        <!-- Content Row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('Batch Discount Form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.discount.batch.update', $batchDiscount->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $batchDiscount->title }}">
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Product <span class="text-danger">*</span></label>
                                            <select name="product_ids[]" id="productSelect"
                                                class="js-example-responsive form-control" multiple>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                        data-code="{{ $product->code }}" data-price="{{\App\CPU\BackEndHelper::usd_to_currency($product['unit_price'])}}"
                                                        {{ in_array($product->id, json_decode($batchDiscount->product_ids, true)) ? 'selected' : '' }}>
                                                        {{ $product->code }}</option>
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
                                    @php
                                        $productIds = json_decode($batchDiscount->product_ids, true) ?? [];
                                        $discountAmounts = json_decode($batchDiscount->discount_amount, true) ?? [];
                                        $discountTypes = json_decode($batchDiscount->discount_type, true) ?? [];
                                    @endphp
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
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/back-end') }}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

    </script>
    <script>
        $(document).ready(function() {
            const $select = $("#productSelect");
            const $tableBody = $("#product-table-body");

            const existingData = @json($discountAmounts);
            const existingTypes = @json($discountTypes);

            // Initialize Select2
            $select.select2({
                width: "resolve",
                placeholder: "Select products",
            });

            // Function to render table rows based on selected options
            function renderTable() {
                const selectedOptions = $select.find("option:selected");
                $tableBody.empty();

                selectedOptions.each(function(index) {
                    const id = $(this).val();
                    const name = $(this).data("name");
                    const code = $(this).data("code");
                    const price = $(this).data("price");
                    const discountVal = existingData[id] ?? "";
                    const discountType = existingTypes[id] ?? "flat";

                    const row = `
                <tr data-id="${id}">
                    <td>${index + 1}</td>
                    <td>${name}</td>
                    <td>${code}</td>
                    <td>${price}</td>
                    <td>
                        <input type="number" name="discount_amounts[${id}]"
                            class="form-control"
                            value="${discountVal}" placeholder="Enter amount">
                    </td>
                    <td>
                        <select name="discount_types[${id}]" class="form-control">
                            <option value="flat" ${discountType === 'flat' ? 'selected' : ''}>Flat</option>
                            <option value="percent" ${discountType === 'percent' ? 'selected' : ''}>Percentage</option>
                        </select>
                    </td>
                </tr>`;
                    $tableBody.append(row);
                });
            }

            // Run once on page load (for existing selections)
            renderTable();

            // Run again whenever selection changes
            $select.on("change", renderTable);
        });
    </script>
@endpush
