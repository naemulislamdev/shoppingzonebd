@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Investors List'))
@push('css_or_js')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap4.css">
@endpush

@section('content')
    <div class="content container-fluid p-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Customer Message') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h1 class="h3 mb-0 text-black-50">Investor Information</h1>
        </div>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="flex-start col-lg-3 mb-3 mb-lg-0">
                                <h5>{{ \App\CPU\translate('User') }} {{ \App\CPU\translate('Information') }}
                                    {{ \App\CPU\translate('table') }} </h5>
                                <h5 style="color: red;">
                                    ({{ $investors->count() }})</h5>
                            </div>
                            <div class="col-lg-2">
                                Export :
                                <a href="{{ route('admin.investors.bulk-export') }}" class="btn btn-success btn-sm">
                                    <i class="tio-file-text"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 2%;">SL#</th>
                                        <th style="width: 10%;">Date and Time</th>
                                        <th style="width: 10%;">Name</th>
                                        <th style="width: 10%;">Phone</th>
                                        <th style="width: 15%;">Address</th>
                                        <th style="width: 10%;">Occupation</th>
                                        <th style="width: 10%;">Investment Amount</th>
                                        <th style="width: 5%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($investors as $k => $investor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($investor->created_at)->format('d M Y') }}
                                                <br>
                                                {{ date('h:i A', strtotime($investor['created_at'])) }}
                                            </td>
                                            <td>{{ $investor['name'] }}</td>
                                            <td>{{ $investor['mobile_number'] }}</td>
                                            <td>{{ $investor['address'] }}</td>
                                            <td>{{ $investor['occupation'] }}</td>
                                            <td>{{ $investor['investment_amount'] }}</td>
                                            <td>

                                                <div class="d-flex justify-content-between">
                                                    <a title="{{ \App\CPU\translate('View') }}"
                                                        class="btn btn-info btn-sm mr-2 mb-2" style="cursor: pointer;"
                                                        href="{{ route('admin.investors.view', $investor->id) }}">
                                                        <i class="tio-visible"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete mb-2 mr-2"
                                                        style="cursor: pointer;" id="{{ $investor['id'] }}"
                                                        title="{{ \App\CPU\translate('Delete') }}">
                                                        <i class="tio-delete"></i>
                                                    </a>
                                                </div>
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap4.js"></script>
    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function() {
            new DataTable('#example');
        });
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{ \App\CPU\translate('Are_you_sure_delete_this') }}?',
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('delete_it') }}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('admin.investors.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('User_deleted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
