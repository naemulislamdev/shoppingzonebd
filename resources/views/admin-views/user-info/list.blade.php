@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('User info List'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('User-info Message') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h1 class="h3 mb-0 text-black-50">{{ \App\CPU\translate('User-info') }}
                {{ \App\CPU\translate('List') }}</h1>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="flex-start col-lg-3 mb-3 mb-lg-0">
                                <h5>{{ \App\CPU\translate('User-info') }} {{ \App\CPU\translate('List') }}
                                    {{ \App\CPU\translate('table') }} </h5>
                                <h5
                                    style="color: red; margin-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 5px">
                                    ({{ $userInfos->total()}})</h5>
                            </div>
                            <div class="col-12 col-md-5 mt-2 mt-sm-0">
                                <form action="{{ url()->current() }}" id="form-data" method="GET">

                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <label for="to_date">From:</label>
                                            <input type="date" name="from"
                                                id="from_date" class="form-control">
                                        </div>
                                        <div  class="col-12 col-sm-4 mt-2 mt-sm-0">
                                            <label for="to_date">To:</label>
                                            <input type="date" name="to"
                                                id="to_date" class="form-control">
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="search_text" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search ....') }}"
                                            aria-label="Search orders" value="" required>
                                        <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('search') }}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <div id="table-container">
                                @include("admin-views.user-info.partial.userinfo_table")
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $userInfos->links() }}
                    </div>
                    @if (count($userInfos) == 0)
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
                        url: "{{ route('admin.user-info.delete') }}",
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
        function order_status(status, id) {

            var orderStatus = status ? status : 'pending';

            if (status === 'confirmed') {
                Swal.fire({
                    title: '{{ \App\CPU\translate('Are you sure Change this?') }}!',
                    text: "{{ \App\CPU\translate('Think before you completed') }}.",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                            <input type="hidden" name="order_status" value="${status}">
                            <input type="hidden" name="id" value="${id}">
                            <input required
                                class="form-control wedding-input-text wizard-input-pad"
                                type="text"
                                name="note"
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
                            url: "{{ route('admin.user-info.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {
                                toastr.success('Status Change successfully');
                                location.reload();

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
                            <input type="hidden" name="order_status" value="canceled">
                            <input type="hidden" name="id" value="${id}">
                            <input required class="form-control wedding-input-text wizard-input-pad" type="text" name="note" id="note" placeholder="For ${status} note">
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
                            url: "{{ route('admin.user-info.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {

                                toastr.success('Status Change successfully');
                                location.reload();
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
                                <input type="hidden" name="order_status" value="${status}">
                                <input type="hidden" name="id" value="${id}">
                                <input
                                    required
                                    class="form-control wedding-input-text wizard-input-pad"
                                    type="text"
                                    name="note"
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
                            url: "{{ route('admin.user-info.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {

                                toastr.success('Status Change successfully');
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
    <script>
        function loadData() {
    let from = $('#from_date').val();
    let to = $('#to_date').val();
    let search = $('#search_text').val();

    $.ajax({
        url: "{{ route('admin.user-info.ajax-search') }}",
        type: "GET",
        data: {from: from, to: to, search: search},
        success: function (data) {
            $('#table-container').html(data);
        }
    });
}

// Search typing করলে 500ms দেরিতে call হবে (Datatable feeling)
$('#search_text').on('keyup', function () {
    clearTimeout(window.delayTimer);
    window.delayTimer = setTimeout(function () {
        loadData();
    }, 200);
});

// Date change করলে auto load হবে
$('#from_date, #to_date').on('change', function () {
    loadData();
});

    </script>
@endpush
