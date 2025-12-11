@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Blog Edit'))
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
                                <a href="{{route('admin.blog.index')}}" class="btn btn-primary"><i
                                        class="tio-arrow-backward"></i>
                                    {{ \App\CPU\translate('Blog_list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <form action="{{route('admin.blog.update', $blog->id)}}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Title -->
                                <div class="form-group">
                                    <label>{{ \App\CPU\translate('blog_title') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ $blog->title }}" class="form-control"
                                        >
                                </div>

                                <!-- Category -->
                                <div class="form-group">
                                    <label>{{ \App\CPU\translate('select_blog_category') }}
                                        <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control" >
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ $cat->id == $blog->blogCategory->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="form-group pt-4">
                                    <label>{{ \App\CPU\translate('description') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea name="description" id="summernote_{{ $blog->id }}" class="editor" cols="30" rows="10">{{ $blog->description }}</textarea>
                                </div>
                                <div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" id="meta_title">
                                                <label class="input-label" for="meta_title">
                                                    {{ \App\CPU\translate('meta_title') }}<span class="text-secondary">
                                                        (Optional)</span>
                                                </label>
                                                <input type="text" name="meta_title" id="meta_title"
                                                    value="{{$blog->meta_title}}"
                                                    class="form-control @error('meta_title') is-invalid @enderror"
                                                    placeholder="Enter Meta Title">
                                                @error('meta_title')
                                                    <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="meta_keywords">
                                                <label class="input-label" for="meta_keywords">
                                                    {{ \App\CPU\translate('meta_keywords') }}<span class="text-secondary">
                                                        (optional)</span>
                                                </label>
                                                <input type="text" name="meta_keywords" id="meta_keywords"
                                                    value="{{ $blog->meta_keywords }}"
                                                    class="form-control @error('meta_keywords') is-invalid @enderror"
                                                    placeholder="Enter Meta Keywors">
                                                @error('meta_keywords')
                                                    <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="meta_description">{{ \App\CPU\translate('meta_description') }}<span
                                                class="text-secondary"> (optional)</span></label>
                                        <textarea name="meta_description" class="form-control" style="resize: none" cols="6" rows="3">{{ $blog->meta_description }}</textarea>
                                        @error('meta_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Image -->
                                <div class="form-group">
                                    <label>{{ \App\CPU\translate('image') }} (1:1)</label>

                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg_{{ $blog->id }}"
                                            class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label" for="customFileEg_{{ $blog->id }}">
                                            {{ \App\CPU\translate('choose') }}
                                            {{ \App\CPU\translate('file') }}
                                        </label>
                                    </div>
                                </div>

                                <!-- Preview -->
                                <div class="form-group text-center">
                                    <hr>
                                    <img id="viewer_{{ $blog->id }}"
                                        src="{{ asset('storage/blogs') }}/{{ $blog->image }}"
                                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                        style="width: 20%; border: 1px solid #ddd; border-radius: 10px;">
                                </div>

                                <div class="modal-footer flex-start">

                                    <button class="btn btn-primary">{{ \App\CPU\translate('update') }}</button>
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
                // Summernote Initialize
                $('#summernote_{{ $blog->id }}').summernote({
                    height: 150
                });
                // Image Preview
                $("#customFileEg_{{ $blog->id }}").on('change', function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $("#viewer_{{ $blog->id }}").attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                });


        });
    </script>

@endpush
