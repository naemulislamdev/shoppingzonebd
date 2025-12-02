@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Blog Create'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .avatar-lg {
            transition: all 0.3s ease;
        }

        .avatar-lg:hover {
            transform: scale(2.5);
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Add Blog') }}</li>
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
                                        <h5>{{ \App\CPU\translate('blogs_form') }}</h5>
                                    </div>

                                </div>

                            </div>
                            <div id="banner-btn">
                                <a href="{{route('admin.business-settings.blog.index')}}" class="btn btn-primary"><i
                                        class="tio-arrow-backward"></i>
                                    {{ \App\CPU\translate('Blog_list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <form action="{{ route('admin.business-settings.blog.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group" id="title">
                                <label class="input-label" for="title">
                                    {{ \App\CPU\translate('blog_title') }}<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Enter Blog Title" >
                                @error('title')
                                    <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">
                                    {{ \App\CPU\translate('select_blog_category') }}
                                    <span class="text-danger">*</span></label>
                                <select  name="category_id" class="form-control" >
                                    <option selected value="" disabled>---select---</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- description --}}
                            <div class="form-group pt-4">
                                <label class="input-label" for="description">{{ \App\CPU\translate('description') }}<span
                                        class="text-danger">*</span></label>
                                <textarea  name="description" class="editor" id="summernote" cols="30" rows="80">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label>{{ \App\CPU\translate('image') }}</label><small style="color: red">
                                    ( {{ \App\CPU\translate('ratio') }} 1:1 )</small>
                                <div class="custom-file" style="text-align: left">
                                    <input type="file" name="image" id="customFileEg" class="custom-file-input"
                                        accept="image/*,.jpg,.jpeg,.png,.gif,.bmp,.tif,.tiff, webp">

                                    <label class="custom-file-label"
                                        for="customFileEg1">{{ \App\CPU\translate('choose') }}
                                        {{ \App\CPU\translate('file') }}</label>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <hr>
                                    <center>
                                        <img style="width: 20%;border: 1px solid; border-radius: 10px;" id="viewer"
                                            src="" alt="" />
                                    </center>
                                </div>
                            </div>
                            <div class="modal-footer border-t-0 flex-start">

                                <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('submit') }}
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>


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
@endpush
