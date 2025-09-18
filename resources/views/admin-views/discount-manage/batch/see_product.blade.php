@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Batch Products'))
@push('css_or_js')
    <link href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap4.css" rel="stylesheet">
    @endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between mb-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Batch Product') }}</li>
                </ol>
            </nav>
            <a href="{{ route('admin.discount.batch') }}" class="btn btn-primary">{{ \App\CPU\translate('Back') }}</a>
        </div>

        <!-- Content Row -->
        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('Batch discount product table') }}</h5>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="datatable"
                                class="table table-hover table-thead-bordered"
                                style="width: 100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CPU\translate('SL') }}</th>
                                        <th>{{ \App\CPU\translate('Product name') }}</th>
                                        <th>{{ \App\CPU\translate('Product Code') }}</th>
                                        <th>{{ \App\CPU\translate('Price') }}</th>
                                        <th>{{ \App\CPU\translate('Discount') }}</th>
                                        <th>{{ \App\CPU\translate('Remove') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $product->name }}
                                            </td>
                                            <td>{{ $product->code }}</td>
                                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['unit_price']))}}</td>
                                            <td>
                                                {{ $product->discount }}
                                            </td>
                                            <td>
                                               <a class="btn btn-danger btn-sm"
                                                    href="{{ route('admin.discount.batch.remove.product', $product->id) }}">
                                                    <i class="tio-delete"></i> {{ \App\CPU\translate('Remove') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($products) == 0)
                                        <tr>
                                            <div class="text-center p-4">
                                                <img class="mb-3"
                                                    src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                                                    alt="Image Description" style="width: 7rem;">
                                                <p class="mb-0">{{ \App\CPU\translate('No data to show') }}</p>
                                            </div>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap4.js"></script>
    <script>
        new DataTable('#datatable');
    </script>
    <!-- Page level custom scripts -->
@endpush
