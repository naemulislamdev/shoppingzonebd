@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Career'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Applications') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->

        <!-- Content Row -->

        <div class="row" style="margin-top: 20px" id="banner-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-between row justify-content-between align-items-center flex-grow-1 mx-1">
                            <div>
                                <div class="d-flex">
                                    <div>
                                        <h5>{{ \App\CPU\translate('Applications_table') }}</h5>
                                    </div>
                                    <div class="mx-1">
                                        <h5 style="color: red;">{{ $applications->count() }}</h5>
                                    </div>
                                </div>
                                <div style="width: 30vw" class="mt-4">
                                    <!-- Search -->
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{ \App\CPU\translate('Search_Application_by_Status') }}"
                                                aria-label="Search orders" value="" required>
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                        </div>
                                    </form>
                                    <!-- End Search -->
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="columnSearchDatatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
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
                                            <td>{{ $application->name }} </td>
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
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $applications->links() }}
                    </div>
                    @if ($applications->count() <= 0)
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
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
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
