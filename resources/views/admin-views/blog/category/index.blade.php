@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Blog Category'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Blog Category') }}</li>
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
                                        <h5>{{ \App\CPU\translate('blog_category_table') }}</h5>
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
                                                placeholder="{{ \App\CPU\translate('Search_by_category_name_or_status') }}"
                                                aria-label="Search orders" value="" required>
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CPU\translate('Search') }}</button>
                                        </div>
                                    </form>
                                    <!-- End Search -->
                                </div>
                            </div>
                            <div id="banner-btn">
                                <button data-toggle="modal" data-target="#addBlogCateogoryModal" class="btn btn-primary"><i
                                        class="tio-add-circle"></i>
                                    {{ \App\CPU\translate('Add_Blog_Category') }}</button>
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
                                        <th>{{ \App\CPU\translate('published') }}</th>
                                        <th style="width: 50px">{{ \App\CPU\translate('actions') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($blogsCatgories as $i => $category)
                                        <tr>
                                            <th scope="row">{{++$i}}</th>
                                            <td>{{$category->name}}</td>
                                            <td><label class="switch"><input
                                                name="status"  type="checkbox" class="status" id="{{$category->id}}"
                                                        <?php if ($category->status == 1) {
                                                            echo 'checked';
                                                        } ?>><span class="slider round"></span></label>
                                            </td>
                                            <td>

                                                <a class="btn btn-primary btn-sm edit"
                                                    title="{{ \App\CPU\translate('Edit') }}" href="#"
                                                    data-toggle="modal" data-target="#editBlogCategory_{{ $category->id }}"
                                                    style="cursor: pointer;">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm delete"
                                                    title="{{ \App\CPU\translate('Delete') }}" style="cursor: pointer;"
                                                    id="{{ $category->id }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <!-- Edit category Modal start-->
                                    <div class="modal fade" id="editBlogCategory_{{ $category->id }}" tabindex="-1"
                                        aria-labelledby="editBlogCategoryLabel" aria-hidden="true">
                                        <div class=" modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="editBlogCategoryModal">
                                                        {{ \App\CPU\translate('Edit_Job_Post') }}</h3>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div>
                                                        <form action="{{ route('admin.business-settings.blogCategory.update', $category->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            {{-- name--}}
                                                            <div class="form-group" id="position">
                                                                <label class="input-label" for="position">
                                                                    {{ \App\CPU\translate('category_name') }}<span
                                                                        class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" name="name"
                                                                    value="{{ $category->name }}" id="name"
                                                                    class="form-control @error('name') is-invalid @enderror"
                                                                    required>
                                                                @error('name')
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
                                    <!-- Edit category Modal End-->
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

    {{-- Add job Modal Body Start  --}}
    <div class="modal fade w-100" id="addBlogCateogoryModal" tabindex="-1" aria-labelledby="addBlogCateogoryModal"
        aria-hidden="true" static="backdrop">
        <div class=" modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="addBlogCategoryModal">{{ \App\CPU\translate('add_Blog_Category') }}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>
                        <form action="{{ route('admin.business-settings.blogCategory.store') }}" method="POST">
                            @csrf
                            <div class="form-group" id="name">
                                <label class="input-label" for="name">
                                    {{ \App\CPU\translate('category_name') }}<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter Category Name" required>
                                @error('name')
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
                url: "{{ route('admin.business-settings.blogCategory.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('Category_published_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('Category_unpublished_successfully') }}');
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
                        url: "{{ route('admin.business-settings.blogCategory.delete') }}",
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
