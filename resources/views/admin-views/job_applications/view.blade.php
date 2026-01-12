@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Application List'))
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
    <div class="content container ">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Job_Applications') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h1 class="h3 mb-0 text-black-50">{{ \App\CPU\translate('Application') }}
                {{ \App\CPU\translate('List') }}
                {{-- <span class="badge badge-soft-info">{{ \App\Models\UserInfo::all()->count() }}</span> </h1> --}}

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
                                <a href="{{ route('admin.application.bulk-export') }}" class="btn btn-success text-white">
                                    Export</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <div id="table-container ">
                                <table id="application-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ \App\CPU\translate('sl#') }}</th>
                                            <th>{{ \App\CPU\translate('Name') }}</th>
                                            <th>{{ \App\CPU\translate('Email') }}</th>
                                            <th>{{ \App\CPU\translate('Phone') }}</th>

                                            <th>{{ \App\CPU\translate('Applyed Job Position') }}</th>
                                            <th>{{ \App\CPU\translate('CV') }}</th>
                                            <th>{{ \App\CPU\translate('Applyed Date') }}</th>
                                            <th>{{ \App\CPU\translate('Apply Status') }}</th>
                                            <th>{{ \App\CPU\translate('Change Status') }}</th>
                                            <th>{{ \App\CPU\translate('action') }}</th>
                                        </tr>
                                    </thead>
                                </table>

                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- view modal --}}


    <!-- view Application Modal start-->
    <div class="modal fade" id="viewApplicationModal" tabindex="-1" aria-labelledby="viewApplicationModalLabel"
        aria-hidden="true">
        <div class=" modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="subcategoryModal">
                        {{ \App\CPU\translate('Candidate_Info') }}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>

            </div>
        </div>
    </div>
    <!-- view Application Modal End-->
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page level custom scripts -->


    <script>
        // Datatable ajax query
        $(function() {
            let usertable = $('#application-table').DataTable({
                processing: true,
                serverSide: true,
                scrollX: false,
                autoWidth: false,
                language: {
                    processing: '<div class="d-flex flex-column justify-content-center align-items-center"> <div  class="spinner-border text-primary" role="status"></div>Loading...</div>'
                },
                ajax: {
                    url: '{{ route('admin.application.datatables', 'all') }}',
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
                        width: '3%'
                    },
                    {
                        data: 'created_at',
                        title: 'Applyed Date',
                        width: '10%',


                    },
                    {
                        data: 'name',
                        title: 'NAME',
                        width: '10%',
                        searchable: true,
                        className: 'cName',
                    },
                    {
                        data: 'email',
                        title: 'EMAIL',
                        width: '10%',
                        searchable: true,

                    },
                    {
                        data: 'phone',
                        title: 'PHONE',
                        width: '15%',
                        searchable: true,
                        className: 'p-1',
                    },
                    {
                        data: 'career_id',
                        title: 'Applyed Job Position',
                        width: '13%',
                        searchable: true,
                        className: 'p-1',
                    },
                    {
                        title: 'CV',
                        data: 'resume',
                        name: 'resume',
                        orderable: true,
                        searchable: true,
                        width: '5%',

                    },
                    {
                        data: 'status',
                        title: 'STATUS',
                        width: '10%',
                        searchable: true,
                    },
                    {
                        data: 'change_status',
                        title: 'Change Status',
                        width: '15%',
                        searchable: false,
                    },

                    // Add more columns as needed
                    {
                        data: 'action',
                        title: '10%',
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
                            url: "{{ route('admin.application.delete') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
                            success: function(response) {
                                toastr.success('Application deleted successfully');
                                // remove row from DataTable
                                usertable.row($(`#application-table button#${id}`)
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

        // change

        $(document).on('change', '.changeStatus', function() {
            var id = $(this).attr("id");
            var status = $(this).val(); // <--- selected option এর value নাও

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('admin.application.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {


                    if (data.status === 'pending') {
                        $(`.statusBadge_${data.id}`).html(
                            `<span class="badge badge-warning">${data.status.toUpperCase()}</span>`
                        );
                    }

                    if (data.status === 'shortlisted') {
                        $(`.statusBadge_${data.id}`).html(
                            `<span class="badge badge-success">${data.status.toUpperCase()}</span>`
                        );
                    }

                    if (data.status === 'rejected') {
                        $(`.statusBadge_${data.id}`).html(
                            `<span class="badge badge-danger">${data.status.toUpperCase()}</span>`
                        );
                    }



                    toastr.success(
                        '{{ \App\CPU\translate('Application_status_update_successfully') }}'
                    );

                },
                error: function(xhr) {
                    toastr.error('Something went wrong!');
                }
            });
        });

        // view ajax

        $(document).on('click', '.viewBtn', function() {
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.application.viewApplication') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(data) {

                    $('.modal-body').html(`
                <div>
                    <p><strong>Full Name:</strong> ${data.name}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Phone:</strong> ${data.phone}</p>
                    <p><strong>District:</strong> ${data.district}</p>
                    <p><strong>Applyed Job Position:</strong> ${data.career}</p>
                    <p><strong>Current Job Position:</strong> ${data.current_position}</p>
                    <p><strong>Experiences:</strong> ${data.experience_level}</p>
                    <p><strong>Expected Salary:</strong> ${data.expected_salary}</p>
                    <p><strong>Portfolio Link:</strong>
                        <a href="${data.portfolio_link}" target="_blank">${data.portfolio_link}</a>
                    </p>
                    <p><strong>Candidate Status:</strong>
                        <span class="text-success">${data.status}</span>
                    </p>
                    <p><strong>Apply Date:</strong> ${data.created_at}</p>
                    <p><strong>Cover Letter:</strong> ${data.message}</p>
                    <p>
                        <strong>Resume/CV:</strong>
                        <a class="btn btn-secondary btn-sm"
                           href="${data.resume_url}" target="_blank">
                           View Resume or CV
                        </a>
                    </p>
                </div>
            `);

                    $('#viewApplicationModal').modal('show');
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });
        });
    </script>
@endpush
