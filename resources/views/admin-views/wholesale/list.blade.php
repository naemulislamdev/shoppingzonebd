@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Wholesale List'))

@push('css_or_js')
@endpush

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Wholesales') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> {{ \App\CPU\translate('Wholesale') }}
                        {{ \App\CPU\translate('Message') }}</span>
                    <span class="badge badge-soft-dark mx-2">{{ $wholesaleList->total() }}</span>
                </div>

            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card p-3">
            <!-- Header -->
            <div class="card-header">
                <div class="row flex-between justify-content-between flex-grow-1">

                    <div class="col-12 col-md-5 mt-2 mt-sm-0">
                        <form action="{{ url()->current() }}" id="form-data" method="GET">

                            <div class="row">
                                <div class="col-12 col-sm-4">
                                    <input type="date" name="from" value="{{ $from ?? date('Y-m-d') }}" id="from_date"
                                        class="form-control">
                                </div>
                                <div class="col-12 col-sm-4 mt-2 mt-sm-0">
                                    <input type="date" value="{{ $to ?? date('Y-m-d') }}" name="to" id="to_date"
                                        class="form-control">
                                </div>
                                <div class="col-12 col-sm-2 mt-2 mt-sm-0  ">
                                    <button type="submit" class="btn btn-primary float-right float-sm-none"
                                        onclick="formUrlChange(this)" data-action="{{ url()->current() }}">
                                        {{ \App\CPU\translate('filter') }}
                                    </button>
                                </div>
                                <div class="col-12 col-sm-2 mt-2 mt-sm-0  ">
                                    <button type="submit" class="btn btn-success float-right float-sm-none"
                                        onclick="formUrlChange(this)"
                                        data-action="{{ route('admin.wholesale.bulk-export') }}">
                                        {{ \App\CPU\translate('export') }}
                                    </button>


                                </div>

                            </div>
                        </form>
                    </div>


                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <table id="example" class="display" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 5%">{{ \App\CPU\translate('SL') }}#</th>
                        <th style="width: 5%">{{ \App\CPU\translate('Date') }}</th>
                        <th style="width: 15%">{{ \App\CPU\translate('Name') }}</th>
                        <th style="width: 15%">{{ \App\CPU\translate('Phone') }}</th>
                        <th style="width: 40%">{{ \App\CPU\translate('Address') }}</th>
                        <th style="width: 15%">{{ \App\CPU\translate('Product Quantity') }}</th>
                        <th style="width: 15%">{{ \App\CPU\translate('Status') }}</th>
                        <th style="width: 10%">{{ \App\CPU\translate('action') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($wholesaleList as $k => $lead)
                        <tr>
                            <td style="width: 5%">{{ $loop->iteration }}</td>

                            <td style="width: 10%">
                                {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}
                            </td>

                            <td style="width: 15%">{{ $lead['name'] }}</td>

                            <td style="width: 15%">{{ $lead['phone'] }}</td>

                            <td style="width: 25%">{{ $lead['address'] }}</td>

                            <td style="width: 10%">{{ round($lead['product_quantity']) }}</td>

                            <td style="width: 10%;">
                                {{ $lead['status'] == 1 ? 'Unseen' : 'Seen' }}
                            </td>

                            <td style="width: 10%">
                                <div class="d-flex justify-content-between">
                                    <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                        style="cursor: pointer;" href="{{ route('admin.wholesale.view', $lead->id) }}">
                                        <i class="tio-visible"></i>
                                    </a>

                                    <a class="btn btn-danger btn-sm delete" style="cursor: pointer;"
                                        id="{{ $lead['id'] }}" title="{{ \App\CPU\translate('Delete') }}">
                                        <i class="tio-delete"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- End Table -->

            <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                <div class="row table-responsive">
                    <div class="">
                        <div class="">
                            <!-- Pagination -->
                            {!! $wholesaleList->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>

@endsection

@push('script_2')
    <script>
        $('#from_date,#to_date').change(function() {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '') {
                $('#to_date').attr('required', 'required');
            }
            if (to != '') {
                $('#from_date').attr('required', 'required');
            }
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('{{ \App\CPU\translate('Invalid date range') }}!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
    </script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        });
    </script>
@endpush
