@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Discount Offers'))

@push('css_or_js')
    <link href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap4.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between mb-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Discount Offers') }}</li>
                </ol>
            </nav>
            <a href="{{ route('admin.discount.discount-offers.create') }}"
                class="btn btn-primary">{{ \App\CPU\translate('Add new') }}</a>
        </div>

        <!-- Content Row -->
        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div class="flex-between">
                                <div>
                                    <h5>{{ \App\CPU\translate('Discount Offers Table') }}</h5>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover table-borderless table-thead-bordered"
                                style="width: 100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CPU\translate('SL') }}</th>
                                        <th>{{ \App\CPU\translate('Image') }}</th>
                                        <th>{{ \App\CPU\translate('Title') }}</th>
                                        <th>{{ \App\CPU\translate('slug') }}</th>
                                        <th>{{ \App\CPU\translate('Total Products') }}</th>
                                        <th>{{ \App\CPU\translate('Status') }}</th>
                                        <th style="width: 50px">{{ \App\CPU\translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($discountOffers as $discount)
                                        @php
                                            $productIds = json_decode($discount->product_ids, true);
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                 <img onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" src="{{ asset('storage/offer') }}/{{ $discount['image'] }}"
                                             class="avatar avatar-lg product-list-img">
                                            </td>
                                            <td>
                                                {{ $discount->title }}
                                            </td>
                                            <td>
                                                {{ $discount->slug }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.discount.discount-offers.product', [$discount->id]) }}"
                                                    class="btn btn-primary">See Products <span
                                                        class="badge badge-light">{{ count($productIds) }}</span>
                                                </a>
                                            </td>
                                            <td>
                                                <label class="switch">
                                                    <input type="checkbox" class="status" id="{{ $discount->id }}"
                                                        {{ $discount->status == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm mb-2"
                                                    href="{{ route('admin.discount.discount-offers.edit', [$discount->id]) }}">
                                                    <i class="tio-edit"></i> {{ \App\CPU\translate('Edit') }}
                                                </a>
                                                <a class="btn btn-danger btn-sm"
                                                    href="{{ route('admin.discount.discount-offers.delete', [$discount->id]) }}">
                                                    <i class="tio-delete"></i> {{ \App\CPU\translate('Delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
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
    <script>
        $(document).on('change', '.status', function() {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.discount.discount-offers.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function() {
                    toastr.success('{{ \App\CPU\translate('Status updated successfully') }}');

                }
            });
        });
    </script>
@endpush
