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
                    <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Batch Discount') }}</li>
                </ol>
            </nav>
            <a href="{{ route('admin.discount.batch.create') }}"
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
                                    <h5>{{ \App\CPU\translate('Batch discount table') }}</h5>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="datatable"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                style="width: 100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ \App\CPU\translate('Title') }}</th>
                                        <th>{{ \App\CPU\translate('Discount Amount') }}</th>
                                        <th>{{ \App\CPU\translate('Discount Type') }}</th>
                                        <th>{{ \App\CPU\translate('Total Products') }}</th>
                                        <th style="width: 50px">{{ \App\CPU\translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($batchDiscounts as $discount)
                                        @php
                                            $productIds = json_decode($discount->product_ids, true);
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $discount->title }}
                                            </td>
                                            <td>{{ $discount->discount_amount }}</td>
                                            <td>{{ $discount->discount_type }}</td>
                                            <td>
                                                <a href="{{ route('admin.discount.batch.product', [$discount->id]) }}"
                                                    class="btn btn-primary">See Products <span
                                                        class="badge badge-light">{{ count($productIds) }}</span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm"
                                                    href="{{ route('admin.discount.batch.edit', [$discount->id]) }}">
                                                    <i class="tio-edit"></i> {{ \App\CPU\translate('Edit') }}
                                                </a>
                                                <a class="btn btn-danger btn-sm"
                                                    href="{{ route('admin.discount.batch.delete', [$discount->id]) }}">
                                                    <i class="tio-delete"></i> {{ \App\CPU\translate('Delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($batchDiscounts) == 0)
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
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });



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
                url: "{{ route('admin.landingpages.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function() {
                    toastr.success('{{ \App\CPU\translate('Status updated successfully') }}');
                    location.reload();
                }
            });
        });
    </script>
@endpush
