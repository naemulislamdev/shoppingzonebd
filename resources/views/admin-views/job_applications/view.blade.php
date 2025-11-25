@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Job Applications'))

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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Jobs Applications') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> {{ \App\CPU\translate('Jobs') }}
                        {{ \App\CPU\translate('Application') }}</span>
                    <span class="badge badge-soft-dark mx-2">{{ $applications->total() }}</span>
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
                                        data-action="{{ route('admin.application.bulk-export') }}">
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
                        <th>{{ \App\CPU\translate('sl#') }}</th>
                        <th>{{ \App\CPU\translate('Name') }}</th>
                        <th>{{ \App\CPU\translate('Email') }}</th>
                        <th>{{ \App\CPU\translate('Phone') }}</th>

                        <th>{{ \App\CPU\translate('Applyed Job Position') }}</th>
                        <th>{{ \App\CPU\translate('CV') }}</th>
                        <th>{{ \App\CPU\translate('Applyed Date') }}</th>
                        <th>{{ \App\CPU\translate('Apply Status') }}</th>
                        <th>{{ \App\CPU\translate('action') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($applications as $i => $application)
                                        <tr>
                                            <th scope="row">{{ ++$i }}</th>
                                            <td>{{ $application->name }}  </td>
                                            <td>{{ $application->email }}</td>
                                            <td>{{ $application->phone }}</td>

                                            <td>{{ $application->career->position }}</td>
                                            <td>
                                                <a href="{{ asset('storage/files/job_resume/' . $application->resume) }}"
                                                    class="btn btn-primary btn-sm edit" target="_blank"
                                                    title="{{ \App\CPU\translate('view resume or CV') }}"
                                                    style="cursor: pointer;">
                                                    <img width="20px" src="{{ asset('assets/back-end/svg/cv.svg') }}"
                                                        alt="">
                                                </a>
                                            </td>
                                            <td> {{ \Carbon\Carbon::parse($application->created_at)->format('d-M-Y') }}
                                            </td>
                                            <td class="p-0">
                                                <div class="form-group">

                                                    <select class="form-control status m-0 p-0 w-100 border-0 shadow-none"
                                                        name="status" id="{{ $application->id }}">
                                                        <option {{ $application['status'] == 'pending' ? 'selected' : '' }}
                                                            value="Pending">Pending</option>
                                                        <option
                                                            {{ $application['status'] == 'shortlisted' ? 'selected' : '' }}
                                                            value="shortlisted">Shortlisted
                                                        </option>
                                                        <option
                                                            {{ $application['status'] == 'rejected' ? 'selected' : '' }}
                                                            value="rejected">Rejected</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-info btn-sm edit"
                                                    title="{{ \App\CPU\translate('View') }}" href="#"
                                                    data-toggle="modal"
                                                    data-target="#viewApplicationModal_{{ $application['id'] }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-visible"></i>
                                                </a>

                                                <a class="btn btn-danger btn-sm delete"
                                                    title="{{ \App\CPU\translate('Delete') }}" style="cursor: pointer;"
                                                    id="{{ $application->id }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- view Application Modal start-->
                                        <div class="modal fade" id="viewApplicationModal_{{ $application['id'] }}"
                                            tabindex="-1" aria-labelledby="viewApplicationModalLabel" aria-hidden="true">
                                            <div class=" modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="subcategoryModal">
                                                            {{ \App\CPU\translate('Candidate_Info') }}</h3>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div>


                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Full Name:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $application->name }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Email:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $application->email }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Phone:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $application->phone }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        District:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $application->district }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark">
                                                                    <strong>Applyed Job Position:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $application->career->position }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Current Job Position :</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $application->current_position }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Experiences :</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $application->experience_level }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Expected salary:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    @if ($application->expected_salary)
                                                                        {{ $application->expected_salary }}
                                                                    @else
                                                                        No
                                                                    @endif

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>Portfolio Link
                                                                        :</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $application->portfolio_link }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>Candidate
                                                                        Status:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block text-success">
                                                                    {{ ucfirst($application->status) }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>Apply
                                                                        Date:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y') }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Cover Letter:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">

                                                                    {{ strip_tags($application->message) }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Resume/CV:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    <a class="btn btn-secondary"
                                                                        href="{{ asset('storage/files/job_resume/' . $application->resume) }}"
                                                                        target="_blank" rel="noopener noreferrer">View
                                                                        Resume or CV</a>
                                                                </p>
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
                                        <!-- view Application Modal End-->
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
                            {!! $applications->links() !!}
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
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
    </script>

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
        $(document).on('change', '.status', function() {
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
                    toastr.success(
                        '{{ \App\CPU\translate('Application_status_update_successfully') }}');

                },
                error: function(xhr) {
                    toastr.error('Something went wrong!');
                }
            });
        });


        // delete job
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_application') }}?",
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
                        url: "{{ route('admin.application.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('Appplication_deleted_successfully') }}'
                            );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
