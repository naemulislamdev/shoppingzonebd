@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Single Landing Pages'))

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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Single Landing Pages') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> {{ \App\CPU\translate('Total ') }}
                        {{ \App\CPU\translate('Pages') }}</span>
                    <span class="badge badge-soft-dark mx-2">{{ count($productLandingpage) }}</span>
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


                            </div>
                        </form>
                    </div>
                    <div class="col-6 text-right">
                         <a href="{{route('admin.landingpages.create')}}" class="btn btn-primary">{{ \App\CPU\translate('Add new') }}</a>
                    </div>


                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <table id="example" style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                class="display table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                style="width: 100%">
                <thead class="thead-light">
                    <tr>
                        <th>{{ \App\CPU\translate('Product Code') }}</th>
                        <th>{{ \App\CPU\translate('Slug') }}</th>
                        <th>{{ \App\CPU\translate('status') }}</th>
                        <th>{{ \App\CPU\translate('Product Name') }}</th>
                        <th style="width: 50px">{{ \App\CPU\translate('action') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($productLandingpage as $k => $lPage)
                        <tr>

                            <td>{{ $lPage->product->code }}</td>
                            <td>
                                <a href="https://shoppingzonebd.com.bd/page/{{ $lPage->slug }}"
                                    target="_blank">page/{{ Str::limit($lPage->slug, 30, '...') }}</a>
                            </td>

                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="status" id="{{ $lPage->id }}"
                                        {{ $lPage->status == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                {{ Str::limit($lPage->product->name ?? '', 30, '...') }}
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
                            {{-- {!! $investors->links() !!} --}}
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
            // override global search to trim input
            $.fn.dataTable.ext.type.search.string = function(data) {
                return !data ?
                    '' :
                    data
                    .trim();
            };

            var table = $('#example').DataTable({


            });
            // Trim search box input
            $('#example_filter input').on('input', function() {
                this.value = this.value.replace(/\s+/g, ' ').trimStart();
                // trimStart = front theke space delete
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
@endpush
