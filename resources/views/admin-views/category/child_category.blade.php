@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Sub Sub Category'))

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
                            <div class="col-12 col-md-4">
                                <h5> Child Category <span style="color: red;">({{ $childCategories->total() }})</span></h5>
                            </div>
                            <div class="col-12 col-md-5" style="width: 40vw">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search_by_Sub_Sub_Category') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('search') }}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                            <div class="col-md-3">
                                <a href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#exampleModal">
                                    Add New Child Category
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
                                        <th scope="col" style="width: 120px">SL</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Sub Category</th>
                                        <th scope="col">Child Category</th>
                                        <th scope="col">{{ \App\CPU\translate('slug') }}</th>
                                        <th scope="col">Order Number</th>
                                        <th scope="col" class="text-center" style="width: 120px">
                                            {{ \App\CPU\translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($childCategories as $key => $childCategory)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $childCategory->category->name ?? '' }}</td>
                                            <td class="text-center">{{ $childCategory->subCategory->name ?? '' }}</td>
                                            <td>{{ $childCategory['name'] ?? '' }}</td>
                                            <td>{{ $childCategory['slug'] }}</td>
                                            <td>{{ $childCategory['order_number'] }}</td>
                                            <td>
                                                <a class="btn btn-primary btn-sm edit" style="cursor: pointer;"
                                                    title="{{ \App\CPU\translate('Edit') }}" href="#"
                                                    data-toggle="modal"
                                                    data-target="#childcategoryEdit_{{ $childCategory->id }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-sm delete" style="cursor: pointer;"
                                                    title="{{ \App\CPU\translate('Delete') }}"
                                                    id="{{ $childCategory['id'] }}">
                                                    <i class="tio-add-to-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <!--child Category add Modal -->
                                        <div class="modal fade" id="childcategoryEdit_{{ $childCategory->id }}"
                                            data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{ route('admin.child-category.update', $childCategory->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Child
                                                                Category</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="input-label">Select Category</label>
                                                                        <select name="category_id" class="form-control cat_id"
                                                                            required id="">
                                                                            @foreach (\App\Model\Category::where(['home_status' => 1])->get() as $category)
                                                                                <option {{ $childCategory->category_id == $category['id'] ? 'selected': ''}} value="{{ $category['id'] }}">
                                                                                    {{ $category['name'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="input-label">Select Sub
                                                                            Category</label>
                                                                        <select name="sub_category_id" id=""
                                                                            class="form-control sub-categoryid" required>
                                                                            @foreach (\App\Models\SubCategory::where(['status' => 1])->get() as $subCategory)
                                                                                <option {{ $childCategory->sub_category_id == $subCategory['id'] ? 'selected': ''}} value="{{ $subCategory['id'] }}">
                                                                                    {{ $subCategory['name'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="input-label"> Child Category
                                                                            Name</label>
                                                                        <input type="text" name="name"
                                                                            class="form-control"
                                                                            placeholder="New Category" required value="{{ $childCategory['name'] }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="input-label">Order Number</label>
                                                                        <input type="number" name="order_number"
                                                                            class="form-control"
                                                                            placeholder="Ex: 1,2,3..." required value="{{ $childCategory['order_number']}}">
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
                        {{ $childCategories->links() }}
                    </div>
                    @if (count($childCategories) == 0)
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
    <!--child Category add Modal -->
    <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.child-category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Child Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="input-label">Select Category</label>
                                    <select name="category_id" class="form-control cat_id" required id="">
                                        <option selected disabled>---Select---</option>
                                        @foreach (\App\Model\Category::where(['home_status' => 1])->get() as $category)
                                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="input-label">Select Sub Category</label>
                                    <select name="sub_category_id" id="" class="form-control sub-categoryid" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="input-label"> Child Category Name</label>
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
    <!-- Page level plugins -->
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        $('.cat_id').on('change', function() {
            var id = $(this).val();
            console.log(id);

            if (id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.child-category.getSubCategory') }}',
                    data: {
                        id: id
                    },
                    success: function(result) {
                        $(".sub-categoryid").html(result);
                    }
                });
            }
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{ \App\CPU\translate('Are_you_sure_to_delete_this?') }}',
                text: "{{ \App\CPU\translate('You_wont_be_able_to_revert_this!') }}",
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
                        url: "{{ route('admin.child-category.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('Sub_Sub_Category_Deleted_Successfully') }}.'
                                );
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
