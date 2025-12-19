@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Wholesale List'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Wholesale Message') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h1 class="h3 mb-0 text-black-50">{{ \App\CPU\translate('Wholesale') }}
                {{ \App\CPU\translate('List') }}</h1>
        </div>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="flex-start col-lg-3 mb-3 mb-lg-0">
                                <h5>{{ \App\CPU\translate('Wholesale') }} {{ \App\CPU\translate('List') }}
                                    {{ \App\CPU\translate('table') }} </h5>
                                <h5
                                    style="color: red; margin-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 5px">
                                    ({{ $wholesaleList->total() }})</h5>
                            </div>
                            <div class="col-lg-2">
                                Export :
                                <a href="{{ route('admin.wholesale.bulk-export') }}" class="btn btn-success btn-sm">
                                    <i class="tio-file-text"></i> Excel
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search by Name, Phone or address') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('search') }}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="datatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-hover table-borderless table-thead-bordered table-align-middle card-table"
                                style="width:100%;">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%">{{ \App\CPU\translate('SL') }}#</th>
                                        <th style="width: 5%">{{ \App\CPU\translate('Date') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Name') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Phone') }}</th>
                                        <th style="width: 40%">{{ \App\CPU\translate('Address') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Product Quantity') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Status') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Wholesale_status') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Wholesale_note') }}</th>
                                        <th style="width: 10%">{{ \App\CPU\translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wholesaleList as $k => $wholesale)
                                        <tr>
                                            <td style="width: 5%">{{ $loop->iteration }}</td>

                                            <td style="width: 10%">
                                                {{ \Carbon\Carbon::parse($wholesale->created_at)->format('d M Y') }}
                                            </td>

                                            <td style="width: 15%">{{ $wholesale['name'] }}</td>

                                            <td style="width: 15%">{{ $wholesale['phone'] }}</td>

                                            <td style="width: 25%">{{ $wholesale['address'] }}</td>

                                            <td style="width: 10%">{{ round($wholesale['product_quantity']) }}</td>
                                            <td class="status_{{$wholesale->id}}" style="width: 10%;">
                                                {{ $wholesale['status'] == 0 ? 'Unseen' : 'Seen' }}
                                            </td>
                                             <td class="m-0 p-0">
                                                <div class="form-group">
                                                    <div class="hs-unfold float-right">
                                                        <div class="dropdown">
                                                            <select name="order_status"
                                                                onchange="order_status(this.value, {{ $wholesale['id'] }})"
                                                                class="status form-control status_select_{{$wholesale->id}}"
                                                                data-id="{{ $wholesale['id'] }}">

                                                                <option value="pending"
                                                                    {{ $wholesale->wholesale_status == 'pending' ? 'selected' : '' }}>
                                                                    {{ \App\CPU\translate('Pending') }}</option>
                                                                <option value="confirmed"
                                                                    {{ $wholesale->wholesale_status == 'confirmed' ? 'selected' : '' }}>
                                                                    {{ \App\CPU\translate('Confirmed') }}</option>
                                                                <option value="canceled"
                                                                    {{ $wholesale->wholesale_status == 'canceled' ? 'selected' : '' }}>
                                                                    {{ \App\CPU\translate('Canceled') }} </option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="note_{{$wholesale->id}}">
                                                {{ $wholesale->wholesale_note }}
                                            </td>

                                            <td style="width: 10%">
                                                <div class="d-flex justify-content-between">
                                                    <a title="{{ \App\CPU\translate('View') }}"
                                                        class="btn btn-info btn-sm {{ !$wholesale->status ? 'viewBtn' : '' }} visiable_{{ $wholesale->id }}"
                                                        style="cursor: pointer;" data-id="{{ $wholesale->id }}"
                                                        data-toggle="modal"
                                                        data-target="#viewWholesaleModal_{{ $wholesale['id'] }}"
                                                        href="#">
                                                        <i class="tio-visible"></i>
                                                    </a>

                                                    <a class="btn btn-danger btn-sm delete" style="cursor: pointer;"
                                                        id="{{ $wholesale['id'] }}"
                                                        title="{{ \App\CPU\translate('Delete') }}">
                                                        <i class="tio-delete"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- view wholesale Modal start-->
                                        <div class="modal fade" id="viewWholesaleModal_{{ $wholesale['id'] }}"
                                            tabindex="-1" aria-labelledby="viewCareerModalLabel" aria-hidden="true">
                                            <div class=" modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="subcategoryModal">
                                                            {{ \App\CPU\translate('Wholesale_info') }}</h3>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div>

                                                            <div class="row">
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3"> Name</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $wholesale->name }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Phone</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $wholesale->phone }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Date & Time</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ \Carbon\Carbon::parse($wholesale->created_at)->format('d M Y') }}

                                                                                {{ date('h:i A', strtotime($wholesale['created_at'])) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Address</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $wholesale->address }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Product Quantity</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $wholesale->product_quantity }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Status</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $wholesale['status'] == 0 ? 'Unseen' : 'Seen' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-t-0">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{ \App\CPU\translate('close') }}</button>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- view wholesale Modal End-->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $wholesaleList->links() }}
                    </div>
                    @if (count($wholesaleList) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show') }}</p>
                        </div>
                    @endif
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
        $(document).ready(function() {
            $('#dataTable').DataTable();
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
                        url: "{{ route('admin.wholesale.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('wholesale_deleted_successfully') }}'
                                );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
    <script>
        // 1. ajax for view status change
        $(document).on('click', '.viewBtn', function() {
            let id = $(this).data('id');
            console.log('clicked');

            $.ajax({
                url: "{{ route('admin.wholesale.view') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    $(`.status_${id}`).html('Seen');
                    $(`.visiable_${id}`).removeClass('viewBtn');
                }
            });
        });
    </script>
    <script>
        function order_status(status, id) {
            var orderStatus = status ? status : 'pending';

            if (status === 'confirmed') {
                Swal.fire({
                    title: '{{ \App\CPU\translate('Are you sure Change this?') }}!',
                    text: "{{ \App\CPU\translate('Think before you completed') }}.",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                            <input type="hidden" name="wholesale_status" value="${status}">
                            <input type="hidden" name="id" value="${id}">
                            <input required
                                class="form-control wedding-input-text wizard-input-pad"
                                type="text"
                                name="wholesale_note"
                                id="note"
                                placeholder="For ${status} note">
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
                            url: "{{ route('admin.wholesale.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {
                                toastr.success('Status Change successfully');
                                $(`.note_${id}`).html(data.note);

                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            } else if (status === 'canceled') {
                Swal.fire({
                    title: 'Are you sure Change this?',
                    text: "You won't be able to revert this!",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                            <input type="hidden" name="wholesale_status" value="canceled">
                            <input type="hidden" name="id" value="${id}">
                            <input required class="form-control wedding-input-text wizard-input-pad" type="text" name="wholesale_note" id="note" placeholder="For ${status} note">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: 'Yes, Change it!',
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.wholesale.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {

                                toastr.success('Status Change successfully');
                                $(`.note_${id}`).html(data.note);
                                console.log(data.note);

                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: '{{ \App\CPU\translate('Are you sure Change this') }}?',
                    text: "You won't be able to revert this!",
                    html: `
                            <br />
                            <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                                <input type="hidden" name="wholesale_status" value="${status}">
                                <input type="hidden" name="id" value="${id}">
                                <input
                                    required
                                    class="form-control wedding-input-text wizard-input-pad"
                                    type="text"
                                    name="wholesale_note"
                                    id="note"
                                    placeholder="For ${status} note"
                                >
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
                            url: "{{ route('admin.wholesale.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {

                                toastr.success('Status Change successfully');
                                $(`.note_${id}`).html(data.note);
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
