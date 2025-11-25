@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Investors'))

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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Investors') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> {{ \App\CPU\translate('Customer') }}
                        {{ \App\CPU\translate('Message') }}</span>
                    <span class="badge badge-soft-dark mx-2">{{ $investors->total() }}</span>
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
                                        data-action="{{ route('admin.investors.bulk-export') }}">
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
                        <th style="width: 2%;">SL#</th>
                        <th style="width: 10%;">Date and Time</th>
                        <th style="width: 10%;">Name</th>
                        <th style="width: 10%;">Phone</th>
                        <th style="width: 15%;">Address</th>
                        <th style="width: 10%;">Occupation</th>
                        <th style="width: 10%;">Remark</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 8%;">Investment Amount</th>
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
                            <td>
                                @if ($investor['remark'] == '' || $investor['remark'] == null)
                                    <button onclick="remark_status('{{ $investor['remark'] }}', {{ $investor['id'] }})"
                                        class="btn btn-sm btn-secondary">
                                        <i class="tio-comment"></i>
                                    </button>
                                @else
                                    {{ $investor['remark'] }}
                                @endif
                            </td>
                            <td>
                                {{ $investor['status'] == 1 ? 'Unseen' : 'Seen' }}
                            </td>
                            <td>{{ round($investor['investment_amount']) }}</td>
                            <td>

                                <div class="d-flex justify-content-between">
                                    <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm mr-2 mb-2"
                                        style="cursor: pointer;" href="{{ route('admin.investors.view', $investor->id) }}">
                                        <i class="tio-visible"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm delete mb-2 mr-2" style="cursor: pointer;"
                                        id="{{ $investor['id'] }}" title="{{ \App\CPU\translate('Delete') }}">
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
                            {!! $investors->links() !!}
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
    <script>
        function remark_status(remark, id) {

            console.log(id);

            var remark = remark ? remark : '';

            if (status === '') {
                Swal.fire({
                    title: '{{ \App\CPU\translate('Are you sure Change this?') }}!',
                    text: "{{ \App\CPU\translate('Think before you completed') }}.",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.investors.remarkstatus') }}" method="post">
                            <input type="hidden" name="id" value="${id}">
                            <div>
                                <label class="form-label text-start">Remark By: {{ auth('admin')->name }}</label>
                            <input required
                                class="form-control wedding-input-text wizard-input-pad"
                                type="text"
                                name="remark"
                                id="remark"
                                placeholder="Enter remark">
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{ \App\CPU\translate('Yes, Change it') }}!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.investors.remarkstatus') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {
                                toastr.success('Remark status added successfully');
                                location.reload();

                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            }
        }
    </script>

@endpush
