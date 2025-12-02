@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Blog Category'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .avatar-lg {
            transition: all 0.3s ease;
        }

        .avatar-lg:hover {
            transform: scale(2.5);
        }
        .modal-body p {
            font-size: 17px;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Blogs') }}</li>
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
                                        <h5>{{ \App\CPU\translate('blogs_table') }}</h5>
                                    </div>
                                    <div class="mx-1">
                                        <h5 style="color: red;">{{ $blogsCatgories->count() }}</h5>
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
                                                placeholder="{{ \App\CPU\translate('Search_blogs') }}"
                                                aria-label="Search orders" value="" required>
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                        </div>
                                    </form>
                                    <!-- End Search -->
                                </div>
                            </div>
                            <div id="banner-btn">
                                <a href="{{ route('admin.business-settings.blog.create') }}" class="btn btn-primary"><i
                                        class="tio-add-circle"></i>
                                    {{ \App\CPU\translate('Add_Blog') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="columnSearchDatatable" class="table table-striped table-bordered">
                                <thead class="thead-light">

                                    <tr>
                                        <th style="width: 5%">{{ \App\CPU\translate('sl#') }}</th>
                                        <th style="width: 10%">{{ \App\CPU\translate('image') }}</th>
                                        <th style="width: 10%">{{ \App\CPU\translate('Category') }}</th>
                                        <th style="width: 20%">{{ \App\CPU\translate('Title') }}</th>
                                        <th style="width: 10%">{{ \App\CPU\translate('views') }}</th>
                                        <th style="width: 10%">{{ \App\CPU\translate('Upload Date') }}</th>
                                        <th style="width: 10%">{{ \App\CPU\translate('published') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('actions') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($blogsCatgories as $i => $blogCat)
                                        <tr>
                                            <td style="width: 5%">{{ ++$i }}</td>

                                            <td style="width: 10%">
                                                <img onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                    src="{{ asset('storage/blogs') }}/{{ $blogCat['image'] }}"
                                                    class="avatar avatar-lg product-list-img">
                                            </td>

                                            <td style="width: 15%">{{ $blogCat->blogCategory->name }}</td>

                                            <td style="width: 10%; ">{{ $blogCat->title }}</td>

                                            <td style="width: 10%">{{ $blogCat->views }}</td>

                                            <td style="width: 15%">{{ $blogCat->created_at->format('d-M-Y') }}</td>

                                            <td style="width: 10%">
                                                <label class="switch">
                                                    <input name="status" type="checkbox" class="status"
                                                        id="{{ $blogCat->id }}"
                                                        {{ $blogCat->status == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td style="width: 15%">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#viewBlogModal_{{ $blogCat->id }}"
                                                    class="btn btn-info btn-sm edit">
                                                    <i class="tio-visible"></i>
                                                </a>
                                                <a class="btn btn-primary btn-sm edit"
                                                    href="{{ route('admin.business-settings.blog.edit', $blogCat->slug) }}">
                                                    <i class="tio-edit"></i>
                                                </a>

                                                <a class="btn btn-danger btn-sm delete" id="{{ $blogCat->id }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        {{-- View blog Modal Body Start  --}}
                                        <div class="modal fade w-100" id="viewBlogModal_{{ $blogCat->id }}" tabindex="-1"
                                            aria-labelledby="viewBlogModal_{{ $blogCat->id }}" aria-hidden="true"
                                            static="backdrop">
                                            <div class=" modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title text-info" id="viewBlogModal_{{ $blogCat->id }}">
                                                            {{ \App\CPU\translate('View_Blog') }}</h3>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="p-3 ">



                                                            <p><strong>Title:</strong> {{ $blogCat->title }}</p>

                                                            <p><strong>Category:</strong>
                                                                {{ $blogCat->blogCategory->name }}</p>

                                                            <p><strong>Description:</strong></p>
                                                            <div class="border p-2 rounded bg-white"
                                                                style="max-height: 200px; overflow-y: auto;">
                                                                {!! $blogCat->description !!}
                                                            </div>

                                                            <p class="mt-3">
                                                                <strong>Total Views:</strong> {{ $blogCat->views }}
                                                            </p>

                                                            <p>
                                                                <strong>Upload Date:</strong>
                                                                {{ $blogCat->created_at->format('d M, Y h:i A') }}
                                                            </p>
                                                            <div class="modal-footer border-t-5 p-0 m-0 mt-4">
                                                                <button type="button" class="btn btn-primary mt-3 px-5"
                                                                    data-dismiss="modal">{{ \App\CPU\translate('Ok') }}</button>

                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        {{-- AView blog Modal Body End  --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $blogsCatgories->links() }}
                    </div>
                    @if ($blogsCatgories->count() <= 0)
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
            $('#summernote').summernote({
                height: 200
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            @foreach ($blogsCatgories as $item)

                // Summernote Initialize
                $('#summernote_{{ $item->id }}').summernote({
                    height: 150
                });

                // Image Preview
                $("#customFileEg_{{ $item->id }}").on('change', function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $("#viewer_{{ $item->id }}").attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                });
            @endforeach

        });
    </script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg").change(function() {
            readURL(this);
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
                url: "{{ route('admin.business-settings.blog.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('Blog_published_successfully') }}');
                    } else {
                        toastr.success(
                            '{{ \App\CPU\translate('Blog_unpublished_successfully') }}');
                    }
                }
            });
        });

        // delete job
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_Category') }}?",
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
                        url: "{{ route('admin.business-settings.blog.delete') }}",
                        method: 'DELETE',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('Category_deleted_successfully') }}'
                            );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
