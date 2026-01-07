@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Category'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('category') }}</li>
            </ol>
        </nav>


        <div class="row" style="margin-top: 20px" id="cate-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row flex-between justify-content-between align-items-center flex-grow-1">
                            <div class="col-12 col-sm-6 col-md-6">
                                <h5>{{ \App\CPU\translate('category_table') }} <span
                                        style="color: red;">({{ $categories->total() }})</span></h5>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4" style="width: 30vw">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('search_here') }}"
                                            value="{{ $search }}" required>
                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('search') }}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                            <div class="col-md-2">
                                <a href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#exampleModal">
                                    Add New Category
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 100px">SL</th>
                                        <th>{{ \App\CPU\translate('name') }}</th>
                                        <th>{{ \App\CPU\translate('slug') }}</th>
                                        <th>{{ \App\CPU\translate('icon') }}</th>
                                        <th>{{ \App\CPU\translate('Order Number') }}</th>
                                        <th>{{ \App\CPU\translate('home_status') }}</th>
                                        <th style="width:15%;">{{ \App\CPU\translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $key => $category)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->slug }}</td>
                                            <td>
                                                <img class="rounded" width="64"
                                                    onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                    src="{{ asset('storage/category') }}/{{ $category['icon'] }}">
                                            </td>
                                            <td>
                                                {{ $category['order_number'] }}
                                            </td>
                                            <td>
                                                <label class="switch switch-status">
                                                    <input type="checkbox" class="category-status"
                                                        id="{{ $category['id'] }}"
                                                        {{ $category->home_status == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary btn-sm edit" style="cursor: pointer;"
                                                    title="{{ \App\CPU\translate('Edit') }}" href="#"
                                                    data-toggle="modal" data-target="#categoryEdit_{{$category->id}}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm delete" style="cursor: pointer;"
                                                    title="{{ \App\CPU\translate('Delete') }}" id="{{ $category['id'] }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <!--Category Edit Modal -->
                                        <div class="modal fade" id="categoryEdit_{{$category->id}}" data-backdrop="static" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{route('admin.category.update',[$category['id']])}}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Category
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="input-label"> Category Name</label>
                                                                        <input type="text" name="name"
                                                                            class="form-control" placeholder="New Category"
                                                                            value="{{ $category->name }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="input-label">Order Number</label>
                                                                        <input type="number" name="order_number"
                                                                            class="form-control"
                                                                            value="{{ $category->order_number }}"
                                                                            placeholder="Ex: 1,2,3..." required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 from_part_2">
                                                                    <label>{{ \App\CPU\translate('image') }}</label><small
                                                                        style="color: red">*
                                                                        ({{ \App\CPU\translate('ratio') }} 1:1)
                                                                    </small>
                                                                    <div class="custom-file" style="text-align: left">
                                                                        <input type="file" name="image"
                                                                            id="customFileEg1" class="custom-file-input"
                                                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                                        <label class="custom-file-label"
                                                                            for="customFileEg1">{{ \App\CPU\translate('choose') }}
                                                                            {{ \App\CPU\translate('file') }}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 from_part_2">
                                                                    <div class="form-group">
                                                                        <hr>
                                                                        <center>
                                                                            <img style="width: 200px;height:200px;border: 1px solid; border-radius: 10px;"
                                                                                id="viewer"
                                                                                src="{{ asset('storage/category') }}/{{ $category['icon'] }}"
                                                                                alt="image" />
                                                                        </center>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        {{ $categories->links() }}
                    </div>
                    @if (count($categories) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ \App\CPU\translate('no_data_found') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->


    <!--Category add Modal -->
    <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="input-label"> Category Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="New Category"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label">Order Number</label>
                                    <input type="number" name="order_number" class="form-control"
                                        placeholder="Ex: 1,2,3..." required>
                                </div>
                            </div>
                            <div class="col-md-6 from_part_2">
                                <label>{{ \App\CPU\translate('image') }}</label><small style="color: red">*
                                    ( {{ \App\CPU\translate('ratio') }} 1:1 )</small>
                                <div class="custom-file" style="text-align: left">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    <label class="custom-file-label"
                                        for="customFileEg1">{{ \App\CPU\translate('choose') }}
                                        {{ \App\CPU\translate('file') }}</label>
                                </div>
                            </div>
                            <div class="col-12 from_part_2">
                                <div class="form-group">
                                    <hr>
                                    <center>
                                        <img style="width: 200px;height:200px;border: 1px solid; border-radius: 10px;"
                                            id="viewer" src="{{ asset('assets/back-end/img/900x400/img1.jpg') }}"
                                            alt="image" />
                                    </center>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>


    <script>
        $(document).on('change', '.category-status', function() {
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
                url: "{{ route('admin.category.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    home_status: status
                },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('{{ \App\CPU\translate('Status updated successfully') }}');
                    }
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{ \App\CPU\translate('Are_you_sure') }}?',
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                type: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('delete_it') }}!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('admin.category.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('Category_deleted_Successfully.') }}'
                            );
                            location.reload();
                        }
                    });
                }
            })
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

        $("#customFileEg1").change(function() {
            readURL(this);
        });
    </script>
@endpush
