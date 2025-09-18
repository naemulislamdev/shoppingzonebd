@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Landing Pages'))

@push('css_or_js')
    <link href="{{ asset('assets/back-end/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .upload-container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .custom-file-input {
            display: none;
            /* Hide the actual file input */
        }

        .custom-file-label {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .custom-file-label:hover {
            background-color: #0056b3;
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
            justify-content: center;
        }

        .preview-item {
            position: relative;
            margin: 10px;
            width: 120px;
            height: 120px;
        }

        .preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .remove-icon {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.8);
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 14px;
            line-height: 22px;
            text-align: center;
            transition: background 0.3s ease;
        }

        .remove-icon:hover {
            background: rgba(255, 0, 0, 1);
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex justify-content-between mb-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Landing Pages Add') }}</li>
                </ol>
            </nav>
            <a href="{{ route('admin.landingpages.index') }}" class="btn btn-primary">{{ \App\CPU\translate('Back') }}</a>
        </div>

        <!-- Content Row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ \App\CPU\translate('Landing-pages_form') }}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.landingpages.single.update', $productLandingpage->id) }}"
                            method="post"
                            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" value="{{ $productLandingpage->title }}"
                                                class="form-control">
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" style="padding-top: 20px;">
                                        <label for="name">{{ \App\CPU\translate('Slider') }}
                                            {{ \App\CPU\translate('Banner') }}</label><span
                                            class="badge badge-soft-danger"> * ( {{ \App\CPU\translate('ratio') }} 1900x500
                                            )</span>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ \App\CPU\translate('Upload product images') }}</label><small
                                                style="color: red">* ( {{ \App\CPU\translate('ratio') }} 1:1 )</small>
                                        </div>
                                        <div class="upload-container">
                                            <input type="file" id="image-upload" name="images[]" multiple
                                                accept="image/*" class="custom-file-input">
                                            <label for="image-upload" class="custom-file-label">Select Product
                                                Images</label>
                                            <div id="image-preview" class="image-preview-container">
                                            </div>
                                        </div>
                                        <div class="exsit-image-container">
                                            <div class="row">
                                                @if ($productLandingpage->slider_img)
                                                    @foreach (json_decode($productLandingpage->slider_img) as $key => $photo)
                                                        <div class="col-md-6 mb-2">
                                                            <div class="card">
                                                                <div class="card-body">

                                                                    <img style="width: 100%" height="auto"
                                                                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                                        src="{{ asset("storage/landingpage/slider/$photo") }}"
                                                                        alt="Product image">
                                                                    <input type="text" disabled
                                                                        value="{{ asset("storage/landingpage/slider/$photo") }}"
                                                                        id="image-{{ $key }}">

                                                                    <div class="d-flex">
                                                                        <a href="{{ route('admin.landingpages.remove_image', ['id' => $productLandingpage->id, 'name' => $photo]) }}"
                                                                            class="btn btn-danger btn-xs m-1">{{ \App\CPU\translate('Remove') }}</a>

                                                                        <a class="btn btn-info btn-xs m-1"
                                                                            href="javascript:void(0);"
                                                                            onclick="copyImageUrl({{ $key }});">Copy
                                                                            URL</a>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group pt-4">
                                            <label class="input-label">{{ \App\CPU\translate('description') }} <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="description" class="editor" id="summernote" cols="5" rows="10">{{ $productLandingpage->description }}</textarea>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="name">{{ \App\CPU\translate('Add new product') }} <span
                                                            class="text-danger">*</span></label>

                                                    <select id="example-getting-started"
                                                        class=" js-example-responsive form-control" name="product_id">
                                                        <option selected disabled>Select a product</option>
                                                        @foreach (\App\Model\Product::active()->orderBy('id', 'DESC')->get() as $key => $product)
                                                            <option
                                                                {{ $productLandingpage->product_id == $product->id ? 'selected' : '' }}
                                                                value="{{ $product->id }}">
                                                                {{ $product['name'] }} || {{ $product['code'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('product_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h3>Feature of this product</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" style="padding-top: 20px;">
                                        <label>Feature title <span class="text-danger">*</span></label>
                                        <div id="input-container">
                                            @foreach (json_decode($productLandingpage->feature_list) as $list)
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control"
                                                        value="{{ $list }}" name="feature_title[]"
                                                        placeholder="Enter value">
                                                    <a href="javascript:void(0);" class="btn btn-danger delete-existing"
                                                        data-value="{{ $list }}">Delete</a>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="button" class="btn btn-success add-new">Add New</button>
                                        @error('feature_title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 pt-3">
                                        <label for="name">{{ \App\CPU\translate('Left Side') }}
                                            {{ \App\CPU\translate('Banner') }}</label><span
                                            class="badge badge-soft-danger">( {{ \App\CPU\translate('ratio') }} 400x650
                                            )</span>
                                        <div class="custom-file" style="text-align: left">
                                            <input type="file" name="feature_image" id="customFileUpload3"
                                                class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label"
                                                for="customFileUpload3">{{ \App\CPU\translate('choose') }}
                                                {{ \App\CPU\translate('file') }}</label>
                                        </div>

                                        <center>
                                            <img style="width:70%;border: 1px solid; border-radius: 10px; max-height:200px;"
                                                id="viewer3"
                                                onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                src="{{ asset('storage/landingpage') }}/{{ $productLandingpage->feature_img }}"
                                                alt="banner image" />
                                        </center>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h3>Video of this product</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Product Video <span class="text-danger">*</span></label>
                                            <input type="text" name="video_url" class="form-control"
                                                value="{{ $productLandingpage->video_url }}">
                                            @error('video_url')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h3>Add more product related content</h3>
                                </div>
                            </div>
                            <div class="more_sections">
                                @if ($productLandingpage->landingPageSection)
                                    @foreach ($productLandingpage->landingPageSection as $key => $section)
                                        <div class="row mb-3">
                                            <input type="hidden" name="existing_section_id[]"
                                                value="{{ $section->id }}">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Section title {{ $key }}</label>
                                                    <input type="text" name="section_title[{{ $section->id }}]"
                                                        value="{{ $section->section_title }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-check-label">Order button</label>
                                                <div class="d-flex">
                                                    <div class="form-check mr-2">
                                                        <input class="form-check-input" type="radio"
                                                            name="order_button[{{ $section->id }}]"
                                                            id="yes_{{ $section->id }}" value="1"
                                                            {{ $section->order_button == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="yes_{{ $section->id }}">Yes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="order_button[{{ $section->id }}]"
                                                            id="no_{{ $section->id }}" value="0"
                                                            {{ $section->order_button == 0 ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="no_{{ $section->id }}">No</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Section Description</label>
                                                    <textarea name="section_description[{{ $section->id }}]" class="form-control">{{ $section->section_description }}</textarea>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="form-check mr-2">
                                                        <input class="form-check-input" type="radio"
                                                            name="section_direction[{{ $section->id }}]"
                                                            id="descLeft_{{ $section->id }}" value="left"
                                                            {{ $section->section_direction == 'left' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="descLeft_{{ $section->id }}">Left</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="section_direction[{{ $section->id }}]"
                                                            id="descRight_{{ $section->id }}" value="right"
                                                            {{ $section->section_direction == 'right' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="descRight_{{ $section->id }}">Right</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group">
                                                    <label>Section Image</label>
                                                    <input type="file" name="section_img[{{ $section->id }}]"
                                                        class="form-control">
                                                </div>
                                                <img src="{{ asset('storage/landingpage/' . $section->section_img) }}"
                                                    alt="section image" class="img-thumbnail" style="max-height:200px;">
                                            </div>
                                            <div class="col-md-3 mx-auto">
                                                <div class="delete-button">
                                                    <a href="{{ route('admin.landingpages.remove_page_section', ['id' => $section->id]) }}"
                                                        class="btn btn-danger section_delete">Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                            <div class="row">
                                <div class="col">
                                    <button type="button" class="btn btn-primary" id="add_new_section">Add more</button>
                                </div>
                            </div>
                            <div class=" pl-0">
                                <button type="submit"
                                    class="btn btn-primary float-right">{{ \App\CPU\translate('save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/back-end/js/spartan-multi-image-picker.js') }}"></script>

    <script src="{{ asset('assets/back-end') }}/js/select2.min.js"></script>
    <script>
        function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer3').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload3").change(function() {
            readURL3(this);
        });


        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });



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
                url: "{{ route('admin.landingpages.status-update') }}",
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
        $(document).ready(function() {
            const previewContainer = $("#image-preview");
            $("#image-upload").on("change", function(event) {
                previewContainer.empty(); // Clear existing previews
                const files = event.target.files;

                if (files) {
                    $.each(files, function(index, file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewItem = $(`
                             <div class="preview-item">
                                 <img src="${e.target.result}" class="preview-image">
                                 <button type="button" class="remove-icon" data-index="${index}">&#10005;</button>
                             </div>
                         `);
                            previewContainer.append(previewItem);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });

            // Handle image removal
            previewContainer.on("click", ".remove-icon", function() {
                const indexToRemove = $(this).data("index");
                $(this).parent().remove();
                // Remove the corresponding file from the input (file list cannot be modified directly, so create a new list)
                const input = document.getElementById("image-upload");
                const dataTransfer = new DataTransfer();
                const files = input.files;

                // Add all files except the one to be removed
                for (let i = 0; i < files.length; i++) {
                    if (i !== indexToRemove) {
                        dataTransfer.items.add(files[i]);
                    }
                }

                // Update the input files
                input.files = dataTransfer.files;
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.add-new').click(function() {
                let inputCount = $('#input-container .input-group').length;
                let newInput = `
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="feature_title[]" placeholder="Enter value">
                        <button type="button" class="btn btn-danger delete">Delete</button>
                    </div>`;
                $('#input-container').append(newInput);
            });
            $(document).on('click', '.delete', function() {
                $(this).closest('.input-group').remove();
            });

            // Handle existing feature deletion
            $(document).on('click', '.delete-existing', function() {
                let value = $(this).data('value');
                $(this).closest('.input-group').remove();
                // Add hidden input to remove the value from the feature list
                $('<input>').attr({
                    type: 'hidden',
                    name: 'deleted_features[]',
                    value: value
                }).appendTo('#input-container');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let sectionCounter = 0; // Initialize a counter

            // Add new section when clicking 'add_new_section'
            $('#add_new_section').click(function() {
                let newInput = `
            <div class="row mb-3">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Section title (New)</label>
                    <input type="text" name="new_section_title[${sectionCounter}]" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-check-label">Order button</label>
                <div class="d-flex">
                    <div class="form-check mr-2">
                        <input class="form-check-input" type="radio" name="new_order_button[${sectionCounter}]" id="yes_${sectionCounter}" value="1" checked>
                        <label class="form-check-label" for="yes_${sectionCounter}">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="new_order_button[${sectionCounter}]" id="no_${sectionCounter}" value="0">
                        <label class="form-check-label" for="no_${sectionCounter}">No</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Section Description</label>
                    <textarea name="new_section_description[${sectionCounter}]" class="form-control" placeholder="Enter description"></textarea>
                </div>
                <div class="d-flex">
                    <div class="form-check mr-2">
                        <input class="form-check-input" type="radio" name="new_section_direction[${sectionCounter}]" id="descLeft_${sectionCounter}" value="left" checked>
                        <label class="form-check-label" for="descLeft_${sectionCounter}">Left</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="new_section_direction[${sectionCounter}]" id="descRight_${sectionCounter}" value="right">
                        <label class="form-check-label" for="descRight_${sectionCounter}">Right</label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Section Image</label>
                    <input type="file" name="new_section_img[${sectionCounter}]" class="form-control">
                </div>
            </div>
            <div class="col-md-3 mx-auto">
                <div class="delete-button">
                    <button type="button" class="btn btn-danger section_delete">Remove</button>
                </div>
            </div>
        </div>`;

                $('.more_sections').append(newInput);
                sectionCounter++; // Increment the counter for the next section
            });

            // Remove section when clicking 'section_delete'
            $(document).on('click', '.section_delete', function() {
                $(this).closest('.row').remove();
            });
            //section deleteing
        });
    </script>
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
    </script>
@endpush
