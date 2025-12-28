@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('User info List'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/back-end/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/back-end/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <style>
        td.order_status {
            display: inline-block;
            width: 120px;
        }
        td.cName {
            display: inline-block;
            width: 120px;
        }
    </style>
@endpush


@section('content')
    <div class="content container">
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
                {{ \App\CPU\translate('List') }} <span
                    class="badge badge-soft-info">{{ \App\Models\UserInfo::all()->count() }}</span> </h1>

        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3 w-100">
                            <div class="col-md-3">
                                <label for="from_date">From:</label>
                                <input type="date" id="from_date" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label for="to_date">To:</label>
                                <input type="date" id="to_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <button id="filterBtn" class="btn btn-primary">Filter</button>
                                <button id="clearBtn" class="btn btn-secondary ms-3">Clear</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.user-info.bulk-export') }}" class="btn btn-success text-white">
                                    Export</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <div id="table-container">
                                @include('admin-views.user-info.partial.userinfo_table')
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        $(document).on('click', '.viewBtn', function() {
            let id = $(this).data('id');


            $.ajax({
                url: "{{ route('admin.user-info.view') }}", // POST route
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    // Table–এ status update
                    if (response.status == 1 && response.seen_by !== null) {
                        $(`.status_${id}`).replaceWith(
                            `
                            <span class="badge badge-success status_${id}">Seen</span>
                            <div><small>Seen by: <br/> ${response.seen_by}</small></div>
                            `
                        );
                        $(`.visiable_${id}`).removeClass("viewBtn");
                    }

                    Swal.fire({
                        title: 'User Info',
                        html: response.html,
                        width: '800px',
                        showCloseButton: true,
                        showConfirmButton: false,
                    });

                },
                error: function() {
                    toastr.error('Something went wrong!');
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
                                $(`.note_${id}`).html(data.note);
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
    <script>
        // Datatable ajax query
        $(function() {
            let usertable = $('#userinfos-table').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    processing: '<div class="d-flex flex-column justify-content-center align-items-center"> <div  class="spinner-border text-primary" role="status"></div>Loading...</div>'
                },
                ajax: {
                    url: '{{ route('admin.user-info.list') }}',
                    data: function(d) {
                        d.from = $('#from_date').val();
                        d.to = $('#to_date').val();
                    }

                }, // e.g., 'userinfos.datatable'
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        title: 'SL#',
                        orderable: false,
                        searchable: false,

                        className: 'text-center p-1',
                        width: 'auto'
                    },
                    {
                        data: 'created_at',
                        title: 'DATE AND TIME',
                        width: 'auto',
                        className: 'p-1',

                    },
                    {
                        data: 'name',
                        title: 'NAME',
                        width: 'auto',
                        searchable: true,
                        className: 'cName',
                    },
                    {
                        data: 'phone',
                        title: 'PHONE',
                        width: 'auto',
                        searchable: true,
                        className: 'p-1',
                    },
                    {
                        data: 'address',
                        title: 'ADDRESS',
                        width: '200px',
                        searchable: true,
                        className: 'p-1',
                    },
                    {
                        data: 'status',
                        title: 'STATUS',
                        width: 'auto',
                        searchable: true,
                        className: 'p-1',
                    },
                    {
                        title: 'PRODUCT',
                        data: 'product_details',
                        name: 'product_details',
                        orderable: true,
                        searchable: true,
                        width: 'auto',
                        className: 'p-1',
                    },
                    {
                        data: 'type',
                        title: 'TYPE',
                        width: 'auto',
                        searchable: true,
                    },
                    {
                        data: 'order_process',
                        title: 'ORDER PROCESS',
                        width: 'auto',
                        searchable: true,
                        className: 'p-0 text-center',
                    },
                    {
                        data: 'order_status',
                        title: 'ORDER STATUS',
                        className: 'order_status p-1',
                        width: '120px',
                        searchable: true,

                    },
                    {
                        data: 'order_note',
                        title: 'ORDER NOTE',
                        width: 'auto',
                        searchable: true,
                        className: 'p-1',
                    },
                    // Add more columns as needed
                    {
                        data: 'action',
                        title: 'ACTION',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ] // Default order by a real column (e.g., phone or created_at)
            });

            // ✅ Filter button
            $('#filterBtn').click(function() {
                usertable.ajax.reload();
            });

            // ✅ Clear button
            $('#clearBtn').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                usertable.ajax.reload();
            });

            // 2️⃣ Delete button without reload
            $(document).on('click', '.delete', function() {
                var id = $(this).attr('id');

                Swal.fire({
                    title: 'Are you sure to delete?',
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('admin.user-info.delete') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                toastr.success('User deleted successfully');
                                // remove row from DataTable
                                usertable.row($(`#userinfos-table button#${id}`)
                                    .parents('tr')).remove().draw();
                            },
                            error: function() {
                                toastr.error('Something went wrong!');
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).on('submit', '#orderNoteForm', function(e) {
            console.log('submit');

            e.preventDefault();

            let form = $(this);

            // trim input
            let noteInput = form.find('input[name="multiple_note[]"]');
            let trimmedValue = noteInput.val().trim();

            if (trimmedValue === '') {
                toastr.warning("Note cannot be empty !");
                return;
            }

            noteInput.val(trimmedValue);

            $.ajax({
                url: "{{ route('admin.user-info.multiple_note') }}",
                type: "POST",
                data: form.serialize(),
                success: function(res) {

                    if (res.status) {

                        $('#noteList').append(`
                    <li style="text-align:left; line-height:20px"
                        class="badge badge-soft-primary d-inline-block mb-2 py-2">
                        ${res.note.note}
                        <span class="text-muted">
                            (${res.note.time} - Note by: ${res.note.user})
                        </span>
                    </li>
                `);

                        form[0].reset();
                        toastr.success('Note added Successfully !');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        });
    </script>
@endpush
