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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Career') }}</li>
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
                                        <h5>{{ \App\CPU\translate('career_table') }}</h5>
                                    </div>
                                    <div class="mx-1">
                                        <h5 style="color: red;">{{ $careers->count() }}</h5>
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
                                                placeholder="{{ \App\CPU\translate('Search_by_job_Type') }}"
                                                aria-label="Search orders" value="" required>
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                        </div>
                                    </form>
                                    <!-- End Search -->
                                </div>
                            </div>
                            <div id="banner-btn">
                                <button data-toggle="modal" data-target="#addJobModal" class="btn btn-primary"><i
                                        class="tio-add-circle"></i>
                                    {{ \App\CPU\translate('Add_Job_Post') }}</button>
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
                                        <th>{{ \App\CPU\translate('sl') }}</th>
                                        <th>{{ \App\CPU\translate('job_position') }}</th>
                                        <th>{{ \App\CPU\translate('job_type') }}</th>
                                        <th>{{ \App\CPU\translate('department') }}</th>
                                        <th>{{ \App\CPU\translate('job_location') }}</th>
                                        <th>{{ \App\CPU\translate('salary') }}</th>
                                        <th>{{ \App\CPU\translate('Deadline') }}</th>
                                        <th>{{ \App\CPU\translate('published') }}</th>
                                        <th style="width: 50px">{{ \App\CPU\translate('action') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($careers as $i => $career)
                                        <tr>
                                            <th scope="row">{{ ++$i }}</th>
                                            <td>{{ $career->position }}</td>
                                            <td>{{ $career->type }}</td>
                                            <td>{{ $career->department }}</td>
                                            <td>{{ $career->location }}</td>
                                            <td>{{ $career->salary }}</td>
                                            <td> {{ \Carbon\Carbon::parse($career->deadline)->format('d-M-Y') }}</td>
                                            <td><label class="switch"><input type="checkbox" class="status"
                                                        id="{{ $career->id }}" <?php if ($career->status == 1) {
                                                            echo 'checked';
                                                        } ?>><span
                                                        class="slider round"></span></label>
                                            </td>

                                            <td>
                                                <a class="btn btn-info btn-sm edit"
                                                    title="{{ \App\CPU\translate('View') }}" href="#"
                                                    data-toggle="modal" data-target="#viewCareerModal_{{ $career['id'] }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}" href="#"
                                                    data-toggle="modal" data-target="#exampleModal_{{ $career['id'] }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm delete"
                                                    title="{{ \App\CPU\translate('Delete') }}" style="cursor: pointer;"
                                                    id="{{ $career->id }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <!-- Edit career Modal start-->
                                        <div class="modal fade" id="exampleModal_{{ $career['id'] }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class=" modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="subcategoryModal">
                                                            {{ \App\CPU\translate('Edit_Job_Post') }}</h3>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div>
                                                            <form action="{{ route('admin.career.update', $career->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                {{-- Job position --}}
                                                                <div class="form-group" id="position">
                                                                    <label class="input-label" for="position">
                                                                        {{ \App\CPU\translate('job_position') }}<span
                                                                            class="text-danger">*</span>
                                                                    </label>
                                                                    <input type="text" name="position"
                                                                        value="{{ $career->position }}" id="position"
                                                                        class="form-control @error('position') is-invalid @enderror"
                                                                        placeholder="Enter Job Position" required>
                                                                    @error('position')
                                                                        <div class="text-danger mt-2">{{ ucwords($message) }}
                                                                        </div>
                                                                    @enderror
                                                                </div>


                                                                {{-- description --}}
                                                                <div class="form-group pt-4">
                                                                    <label class="input-label"
                                                                        for="description">{{ \App\CPU\translate('description') }}<span
                                                                            class="text-danger">*</span></label>
                                                                    <textarea required name="description" class="editor" id="summernote-{{ $career->id }}" cols="30"
                                                                        rows="60">{{ $career->description }}</textarea>
                                                                    @error('description')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                @push('script')
                                                                    <script>
                                                                        $(document).ready(function() {
                                                                            $('#summernote-{{ $career->id }}').summernote();
                                                                        });
                                                                    </script>
                                                                @endpush
                                                                {{-- department --}}
                                                                <div class="form-group">
                                                                    <label class="input-label"
                                                                        for="exampleFormControlSelect1">
                                                                        {{ \App\CPU\translate('change_department') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <select required name="department"
                                                                        class="form-control" required>

                                                                        <option
                                                                            {{ $career->department == 'Digital Merketing' ? 'checked' : '' }}
                                                                            value="Digital Merketing">Digital Merketing
                                                                        </option>
                                                                        <option
                                                                            {{ $career->department == 'Sales (online sales)' ? 'checked' : '' }}
                                                                            value="Sales (online sales)">Sales (online
                                                                            sales)</option>
                                                                    </select>
                                                                </div>
                                                                {{-- Branch location --}}
                                                                <div class="form-group" id="location">
                                                                    <label class="input-label" for="location">
                                                                        {{ \App\CPU\translate('Branch location') }}<span
                                                                            class="text-danger">*</span>
                                                                    </label>
                                                                    <input required type="text" name="location"
                                                                        id="location" value="{{ $career->location }}"
                                                                        class="form-control @error('location') is-invalid @enderror"
                                                                        placeholder="Enter Branch Location">
                                                                    @error('location')
                                                                        <div class="text-danger mt-2">{{ ucwords($message) }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                {{-- Job vacancies --}}
                                                                <div class="form-group" id="vacancies">
                                                                    <label class="input-label" for="title">
                                                                        {{ \App\CPU\translate('Number_of_Vacancies') }}<span
                                                                            class="text-secondary"> (optional
                                                                            default 1)</span>
                                                                    </label>
                                                                    <input min="1" type="number"
                                                                        value="{{ $career->vacancies }}" name="vacancies"
                                                                        id="vacancies"
                                                                        class="form-control @error('vacancies') is-invalid @enderror"
                                                                        placeholder="Enter vacancies">
                                                                    @error('vacancies')
                                                                        <div class="text-danger mt-2">{{ ucwords($message) }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                {{-- Job type --}}

                                                                <div class="form-group">
                                                                    <label class="input-label"
                                                                        for="exampleFormControlSelect1">
                                                                        {{ \App\CPU\translate('select_job_type') }}
                                                                        <span class="text-danger">*</span></label>
                                                                    <select required name="type" class="form-control"
                                                                        required>

                                                                        <option
                                                                            {{ $career->type == 'Full-time' ? 'checked' : '' }}
                                                                            value="Full-time">Full-time</option>

                                                                        <option
                                                                            {{ $career->type == 'Part-time' ? 'checked' : '' }}
                                                                            value="Part-time">Part-time</option>

                                                                        <option
                                                                            {{ $career->type == 'Internship' ? 'checked' : '' }}
                                                                            value="Internship">Internship</option>
                                                                    </select>
                                                                </div>
                                                                {{-- sallery --}}
                                                                <div class="form-group" id="sallery">
                                                                    <label class="input-label" for="sallery">
                                                                        {{ \App\CPU\translate('Salary') }}
                                                                        <span class="text-secondary"> (optional
                                                                            default Negotiable )</span>
                                                                    </label>
                                                                    <input type="text" value="{{ $career->salary }}"
                                                                        name="salary" id="salary"
                                                                        class="form-control @error('salary') is-invalid @enderror"
                                                                        placeholder="Enter salary amount">
                                                                    @error('salary')
                                                                        <div class="text-danger mt-2">{{ ucwords($message) }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                {{-- Job Post start Date --}}
                                                                <div class="form-group" id="opening_date">
                                                                    <label class="input-label" for="opening_date">
                                                                        {{ \App\CPU\translate('job_open_date') }}<span
                                                                            class="text-danger">*</span>
                                                                    </label>
                                                                    <input value="{{ $career->opening_date }}"
                                                                        type="date" name="opening_date"
                                                                        id="opening_date"
                                                                        class="form-control @error('opening_date') is-invalid @enderror"
                                                                        placeholder="Enter opening_date ">
                                                                    @error('opening_date')
                                                                        <div class="text-danger mt-2">{{ ucwords($message) }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                {{-- Job Post deedline --}}
                                                                <div class="form-group" id="deadline">
                                                                    <label class="input-label" for="deadline">
                                                                        {{ \App\CPU\translate('job_deadline') }}<span
                                                                            class="text-danger">*</span>
                                                                    </label>
                                                                    <input value="{{ $career->deadline }}" type="date"
                                                                        name="deadline" id="deadline"
                                                                        class="form-control @error('deadline') is-invalid @enderror"
                                                                        placeholder="Enter deadline ">
                                                                    @error('deadline')
                                                                        <div class="text-danger mt-2">{{ ucwords($message) }}
                                                                        </div>
                                                                    @enderror
                                                                </div>

                                                                <div class="modal-footer border-t-0">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">{{ \App\CPU\translate('close') }}</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">{{ \App\CPU\translate('Update') }}
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- Edit career Modal End-->
                                        <!-- view career Modal start-->
                                        <div class="modal fade" id="viewCareerModal_{{ $career['id'] }}" tabindex="-1"
                                            aria-labelledby="viewCareerModalLabel" aria-hidden="true">
                                            <div class=" modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="subcategoryModal">
                                                            {{ \App\CPU\translate('Job_Post') }}</h3>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div>


                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>Job
                                                                        Postion:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $career->position }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark">
                                                                    <strong>Department:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $career->department }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>Job
                                                                        Description:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ strip_tags($career->description) }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>Job
                                                                        Location:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $career->location }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>Job
                                                                        type:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $career->type }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>Job
                                                                        vacancy:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ $career->vacancies }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Salary:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">

                                                                    {{ $career->salary }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Job Published:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ \Carbon\Carbon::parse($career->opening_date)->format('d-m-Y') }}
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Deadline:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    {{ \Carbon\Carbon::parse($career->deadline)->format('d-m-Y') }}

                                                                </p>
                                                            </div>
                                                            <div>
                                                                <p class="mb-1 fw-bolder text-dark"><strong>
                                                                        Job Status:</strong>
                                                                </p>
                                                                <p class="text-wrap d-inline-block">
                                                                    @if ($career->status == 0)
                                                                        Unpublished
                                                                    @else
                                                                        Published
                                                                    @endif

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
                                        <!-- view career Modal End-->
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $careers->links() }}
                    </div>
                    @if ($careers->count() <= 0)
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

    {{-- Add job Modal Body Start  --}}
    <div class="modal fade w-100" id="addJobModal" tabindex="-1" aria-labelledby="addJobModal" aria-hidden="true"
        static="backdrop">
        <div class=" modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="subcategoryModal">{{ \App\CPU\translate('add_Job_Post') }}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <form action="{{ route('admin.career.store') }}" method="POST">
                            @csrf

                            {{-- Job position --}}
                            <div class="form-group" id="position">
                                <label class="input-label" for="position">
                                    {{ \App\CPU\translate('job_position') }}<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="position" id="position"
                                    class="form-control @error('position') is-invalid @enderror"
                                    placeholder="Enter Job Position" required>
                                @error('position')
                                    <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                @enderror
                            </div>
                            {{-- description --}}
                            <div class="form-group pt-4">
                                <label class="input-label" for="description">{{ \App\CPU\translate('description') }}<span
                                        class="text-danger">*</span></label>
                                <textarea required name="description" class="editor" id="summernote" cols="30" rows="60">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- department --}}
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">
                                    {{ \App\CPU\translate('select_department') }}
                                    <span class="text-danger">*</span></label>
                                <select required name="department" class="form-control" required>
                                    <option selected disabled value="">------</option>
                                    <option value="Digital Merketing">Digital Merketing</option>
                                    <option value="Sales (online sales)">Sales (online sales)</option>
                                </select>
                            </div>
                            {{-- Branch location --}}
                            <div class="form-group" id="location">
                                <label class="input-label" for="location">
                                    {{ \App\CPU\translate('Branch location') }}<span class="text-danger">*</span>
                                </label>
                                <input required type="text" name="location" id="location"
                                    class="form-control @error('location') is-invalid @enderror"
                                    placeholder="Enter Branch Location">
                                @error('location')
                                    <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                @enderror
                            </div>
                            {{-- Job vacancies --}}
                            <div class="form-group" id="vacancies">
                                <label class="input-label" for="title">
                                    {{ \App\CPU\translate('Number_of_Vacancies') }}<span class="text-secondary"> (optional
                                        default 1)</span>
                                </label>
                                <input min="1" type="number" name="vacancies" id="vacancies"
                                    class="form-control @error('vacancies') is-invalid @enderror"
                                    placeholder="Enter vacancies">
                                @error('vacancies')
                                    <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                @enderror
                            </div>
                            {{-- Job type --}}

                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">
                                    {{ \App\CPU\translate('select_job_type') }}
                                    <span class="text-danger">*</span></label>
                                <select required name="type" class="form-control" required>
                                    <option selected disabled value="">------</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Internship">Internship</option>
                                </select>
                            </div>
                            {{-- sallery --}}
                            <div class="form-group" id="sallery">
                                <label class="input-label" for="sallery">
                                    {{ \App\CPU\translate('Salary') }}
                                    <span class="text-secondary"> (optional
                                        default Negotiable )</span>
                                </label>
                                <input type="text" name="salary" id="salary"
                                    class="form-control @error('salary') is-invalid @enderror"
                                    placeholder="Enter salary amount">
                                @error('salary')
                                    <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                @enderror
                            </div>
                            {{-- Job Post start Date --}}
                            <div class="form-group" id="opening_date">
                                <label class="input-label" for="opening_date">
                                    {{ \App\CPU\translate('job_open_date') }}<span class="text-danger">*</span>
                                </label>
                                <input type="date" name="opening_date" id="opening_date"
                                    class="form-control @error('opening_date') is-invalid @enderror"
                                    placeholder="Enter opening_date ">
                                @error('opening_date')
                                    <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                @enderror
                            </div>
                            {{-- Job Post deedline --}}
                            <div class="form-group" id="deadline">
                                <label class="input-label" for="deadline">
                                    {{ \App\CPU\translate('job_deadline') }}<span class="text-danger">*</span>
                                </label>
                                <input type="date" name="deadline" id="deadline"
                                    class="form-control @error('deadline') is-invalid @enderror"
                                    placeholder="Enter deadline ">
                                @error('deadline')
                                    <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                @enderror
                            </div>

                            <div class="modal-footer border-t-0">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{ \App\CPU\translate('close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- Add job Modal Body End  --}}
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
                url: "{{ route('admin.career.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('Career_published_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('Career_unpublished_successfully') }}');
                    }
                }
            });
        });

        // delete job
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_job') }}?",
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
                        url: "{{ route('admin.career.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('job_post_deleted_successfully') }}'
                            );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
