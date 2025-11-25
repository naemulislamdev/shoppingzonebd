@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Landing Pages'))

@push('css_or_js')
    <link href="{{ asset('assets/back-end/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.min.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Single Landing Page') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> {{ \App\CPU\translate('Single Landing Page ') }}
                        {{ \App\CPU\translate('Info') }}</span>
                    <span class="badge badge-soft-dark mx-2">{{ $productLandingpage->total() }}</span>
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
                                    <input type="date" name="from" value="{{ $from ?? date('Y-m-d') }}"
                                        id="from_date" class="form-control">
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
                    <tr>
                        <th style="width: 10%">{{ \App\CPU\translate('Sl#') }}</th>
                        <th style="width: 10%">{{ \App\CPU\translate('Title') }}</th>
                        <th style="width: 40%">{{ \App\CPU\translate('Slug') }}</th>
                        <th style="width: 10%">{{ \App\CPU\translate('status') }}</th>
                        <th style="width: 20%">{{ \App\CPU\translate('Product Name') }}</th>
                        <th style="width: 10%" >{{ \App\CPU\translate('action') }}</th>
                    </tr>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($productLandingpage as $k => $lPage)
                        <tr>
                            <td>{{++$k}}</td>
                            <td>{{ $lPage->title }}</td>
                            <td>
                                <a href="https://shoppingzonebd.com.bd/page/{{ $lPage->slug }}"
                                    target="_blank">page/{{ $lPage->slug }}</a>
                            </td>

                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="status" id="{{ $lPage->id }}"
                                        {{ $lPage->status == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                {{-- {{ $lPage->product[$lPage->product_id]['name']}} --}}
                            </td>
                            <td>
                                <a title="{{ \App\CPU\translate('Edit') }}"
                                    href="{{ route('admin.landingpages.edit', $lPage->id) }}"
                                    class="btn btn-primary btn-sm edit">
                                    <i class="tio-edit"></i>
                                </a>
                                <a title="{{ \App\CPU\translate('Delete') }}"
                                    href="{{ route('admin.landingpages.remove_landing_page', $lPage->id) }}"
                                    class="btn btn-danger btn-sm delete">
                                    <i class="tio-add-to-trash"></i>
                                </a>
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
                            {!! $productLandingpage->links() !!}
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

@push('script')
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
                url: "{{ route('admin.landingpages.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function() {
                    toastr.success('{{ \App\CPU\translate('Status updated successfully') }}');
                    location.reload();
                }
            });
        });
    </script>
    <script>
        $("#multiBannerImage").spartanMultiImagePicker({
            fieldName: 'images[]',
            maxCount: 10,
            rowHeight: 'auto',
            groupClassName: 'col-6',
            maxFileSize: '',
            placeholderImage: {
                image: '{{ asset('assets/back-end/img/400x400/img2.jpg') }}',
                width: '100%',
            },
            dropFileLabel: "Drop Here",
            onAddRow: function(index, file) {

            },
            onRenderedPreview: function(index) {

            },
            onRemoveRow: function(index) {

            },
            onExtensionErr: function(index, file) {
                toastr.error(
                    '{{ \App\CPU\translate('Please only input png or jpg type file') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
            },
            onSizeErr: function(index, file) {
                toastr.error('{{ \App\CPU\translate('File size too big') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
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
@endpush
