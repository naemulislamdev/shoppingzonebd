@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Jobs'))

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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Jobs') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> {{ \App\CPU\translate('All Job ') }}
                        {{ \App\CPU\translate('Posts') }}</span>
                    <span class="badge badge-soft-dark mx-2">{{ $careers->total() }}</span>
                </div>

            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card p-3">
            <!-- Header -->
            <div class="card-header">
                <div class="row flex-between justify-content-between flex-grow-1">

                    <div class="col-6 col-md-5 mt-2 mt-sm-0">
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
                                        data-action="{{ route('admin.career.bulk-export') }}">
                                        {{ \App\CPU\translate('export') }}
                                    </button>


                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="col-6  text-right">
                         <div id="banner-btn ms-auto">
                                <button data-toggle="modal" data-target="#addJobModal" class="btn btn-primary"><i
                                        class="tio-add-circle"></i>
                                    {{ \App\CPU\translate('Add_Job_Post') }}</button>
                            </div>
                    </div>

                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <table id="example" class="display" style="width:100%">
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
                        <th >{{ \App\CPU\translate('action') }}</th>
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
                            <td><label class="switch"><input type="checkbox" class="status" id="{{ $career->id }}"
                                        <?php if ($career->status == 1) {
                                            echo 'checked';
                                        } ?>><span class="slider round"></span></label>
                            </td>

                            <td>
                                <a class="btn btn-info btn-sm edit" title="{{ \App\CPU\translate('View') }}" href="#"
                                    data-toggle="modal" data-target="#viewCareerModal_{{ $career['id'] }}"
                                    style="cursor: pointer;">
                                    <i class="tio-visible"></i>
                                </a>
                                <a class="btn btn-primary btn-sm edit" title="{{ \App\CPU\translate('Edit') }}"
                                    href="#" data-toggle="modal" data-target="#exampleModal_{{ $career['id'] }}"
                                    style="cursor: pointer;">
                                    <i class="tio-edit"></i>
                                </a>
                                <a class="btn btn-danger btn-sm delete" title="{{ \App\CPU\translate('Delete') }}"
                                    style="cursor: pointer;" id="{{ $career->id }}">
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
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div>
                                            <form action="{{ route('admin.career.update', $career->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                {{-- Job position --}}
                                                <div class="form-group" id="position">
                                                    <label class="input-label" for="position">
                                                        {{ \App\CPU\translate('job_position') }}<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="position" value="{{ $career->position }}"
                                                        id="position"
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
                                                    <label class="input-label" for="exampleFormControlSelect1">
                                                        {{ \App\CPU\translate('change_department') }}
                                                        <span class="text-danger">*</span></label>
                                                    <select required name="department" class="form-control" required>

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
                                                        {{ \App\CPU\translate('Branch location') }}
                                                    </label>
                                                    <input required type="text" name="location" id="location"
                                                        value="{{ $career->location }}"
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
                                                        value="{{ $career->vacancies }}" name="vacancies" id="vacancies"
                                                        class="form-control @error('vacancies') is-invalid @enderror"
                                                        placeholder="Enter vacancies">
                                                    @error('vacancies')
                                                        <div class="text-danger mt-2">{{ ucwords($message) }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                {{-- Job type --}}

                                                <div class="form-group">
                                                    <label class="input-label" for="exampleFormControlSelect1">
                                                        {{ \App\CPU\translate('select_job_type') }}
                                                        <span class="text-danger">*</span></label>
                                                    <select required name="type" class="form-control" required>

                                                        <option {{ $career->type == 'Full-time' ? 'checked' : '' }}
                                                            value="Full-time">Full-time</option>

                                                        <option {{ $career->type == 'Part-time' ? 'checked' : '' }}
                                                            value="Part-time">Part-time</option>

                                                        <option {{ $career->type == 'Internship' ? 'checked' : '' }}
                                                            value="Remote">Remote</option>
                                                        <option {{ $career->type == 'Internship' ? 'checked' : '' }}
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
                                                    <input type="text" value="{{ $career->salary }}" name="salary"
                                                        id="salary"
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
                                                    <input value="{{ $career->opening_date }}" type="date"
                                                        name="opening_date" id="opening_date"
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
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
            <!-- End Table -->

            <!-- Footer -->
            <div class="card-footer">
                <!-- Pagination -->
                <div class="row table-responsive">
                    <div class="">
                        <div class="">
                            <!-- Pagination -->
                            {!! $careers->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
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
                                    {{ \App\CPU\translate('Branch location') }}
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
                                    <option value="Remote">Remote</option>
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
