@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('User Info'))

@push('css_or_js')
<<<<<<< HEAD
=======
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
>>>>>>> 08b253713f0288bba1d245f57280bfcf797b356b
@endpush

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('User Info') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> {{ \App\CPU\translate('User ') }}
                        {{ \App\CPU\translate('Info') }}</span>
                    <span class="badge badge-soft-dark mx-2">{{ $userInfos->total() }}</span>
                </div>

            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <div class="row flex-between justify-content-between flex-grow-1">

                    <div class="col-12 col-md-5 mt-2 mt-sm-0">
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
                                        data-action="{{ route('admin.user-info.bulk-export') }}">
                                        {{ \App\CPU\translate('export') }}
                                    </button>


                                </div>

                            </div>
                        </form>
                    </div>
<<<<<<< HEAD
=======
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 2%;">SL#</th>
                                        <th style="width: 10%;">Date and Time</th>
                                        <th style="width: 10%;">Name</th>
                                        <th style="width: 10%;">Phone</th>
                                        <th style="width: 15%;">Address</th>
                                        <th style="width: 5%;">Status</th>
                                        <th style="width: 10%;">Product</th>
                                        <th style="width: 3%;">Order Process</th>
                                        <th style="width: 12%;">Order Status</th>
                                        <th style="width: 10%;">Status Note</th>
                                        <th style="width: 8%;">Type</th>
                                        <th style="width: 5%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($userInfos as $k => $userInfo)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($userInfo->created_at)->format('d M Y') }}
                                                <br>
                                                {{ date('h:i A', strtotime($userInfo['created_at'])) }}
                                            </td>
                                            <td>{{ $userInfo['name'] }}</td>
                                            <td>{{ $userInfo['phone'] }}</td>
                                            <td>{{ $userInfo['address'] }}</td>
                                            <td>{{ $userInfo['status'] == 0 ? 'Unseen' : 'Seen' }}</td>
                                            <td>
                                                @php
                                                    $product_details = json_decode($userInfo->product_details, true);
                                                @endphp
>>>>>>> 08b253713f0288bba1d245f57280bfcf797b356b

                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <table id="example" class="display" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 2%;">SL#</th>
                        <th style="width: 10%;">Date and Time</th>
                        <th style="width: 10%;">Name</th>
                        <th style="width: 10%;">Phone</th>
                        <th style="width: 15%;">Address</th>
                        <th style="width: 5%;">Status</th>
                        <th style="width: 10%;">Product</th>
                        <th style="width: 3%;">Order Process</th>
                        <th style="width: 12%;">Order Status</th>
                        <th style="width: 10%;">Status Note</th>
                        <th style="width: 8%;">Type</th>
                        <th style="width: 5%;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($userInfos as $key => $userInfo)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($userInfo->created_at)->format('d M Y') }}
                                <br>
                                {{ date('h:i A', strtotime($userInfo['created_at'])) }}
                            </td>
                            <td>{{ $userInfo['name'] }}</td>
                            <td>{{ $userInfo['phone'] }}</td>
                            <td>{{ $userInfo['address'] }}</td>

                            <td>
                                @if ($userInfo['status'] == 0)
                                    <span class="badge badge-soft-success">
                                        <span class="legend-indicator bg-success"></span>{{ \App\CPU\translate('Unseen') }}
                                    </span>
                                @else
                                    <span class="badge badge-soft-secondary">
                                        <span class="legend-indicator bg-secondary"></span>{{ \App\CPU\translate('Seen') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $product_details = json_decode($userInfo->product_details, true);
                                @endphp

                                @if (is_array($product_details) && count($product_details) > 0)
                                    {{-- CASE 1: multiple cart items (array of items) --}}
                                    @if (isset($product_details[0]) && is_array($product_details[0]))
                                        @foreach ($product_details as $item)
                                            @php
                                                $productId = $item['id'] ?? null;
                                                $product = \App\Model\Product::find($productId);

                                                // Detect variation data
                                                $variationText = null;
                                                if (!empty($item['variant'])) {
                                                    $variationText = $item['variant'];
                                                } elseif (
                                                    !empty($item['variations']) &&
                                                    is_array($item['variations'])
                                                ) {
                                                    $variationParts = [];
                                                    foreach ($item['variations'] as $key => $value) {
                                                        $variationParts[] = ucfirst($key) . ': ' . ucfirst($value);
                                                    }
                                                    $variationText = implode(', ', $variationParts);
                                                }
                                            @endphp

                                            <div class="mb-2">
                                                <strong>Product Code:</strong>
                                                {{ $product->code ?? 'N/A' }}<br>

                                                @if ($variationText)
                                                    <strong>Variation:</strong> {{ $variationText }}<br>
                                                @endif
                                            </div>
                                        @endforeach

                                        {{-- CASE 2: single product data (object-like array) --}}
                                    @else
                                        @php
                                            $productId = $product_details['product_id'] ?? null;
                                            $product = \App\Model\Product::find($productId);
                                            $color_name = isset($product_details['color'])
                                                ? \App\Model\Color::where('code', $product_details['color'])->value(
                                                    'name',
                                                )
                                                : null;
                                        @endphp

                                        <div class="mb-2">
                                            <strong>Product Code:</strong>
                                            {{ $product->code ?? 'N/A' }}<br>

                                            @if ($color_name)
                                                <strong>Color:</strong> {{ $color_name ?? 'N/A' }}<br>
                                            @endif

                                            @if (!empty($product_details['choice_8']))
                                                <strong>Size:</strong>
                                                {{ $product_details['choice_8'] }}<br>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <p>No product details available.</p>
                                @endif
                            </td>


                            <td>
                                @if ($userInfo['order_process'] == 'pending')
                                    <span class="badge badge-soft-danger">
                                        <span class="legend-indicator bg-danger"
                                            style="{{ Session::get('direction') === 'rtl' ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;' }}"></span>{{ \App\CPU\translate('Pending') }}
                                    </span>
                                @elseif($userInfo['order_process'] == 'completed')
                                    <span class="badge badge-soft-success">
                                        <span class="legend-indicator bg-success"
                                            style="{{ Session::get('direction') === 'rtl' ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;' }}"></span>{{ \App\CPU\translate('Completed') }}
                                    </span>
                                @endif
                            </td>
                            <td class="m-0 p-0">
                                <div class="form-group">
                                    <div class="hs-unfold float-right ">
                                        <div class="dropdown">
                                            <select name="order_status"
                                                onchange="order_status(this.value, {{ $userInfo['id'] }})"
                                                class="status form-control" data-id="{{ $userInfo['id'] }}">

                                                <option value="pending"
                                                    {{ $userInfo->order_status == 'pending' ? 'selected' : '' }}>
                                                    {{ \App\CPU\translate('Pending') }}</option>
                                                <option value="confirmed"
                                                    {{ $userInfo->order_status == 'confirmed' ? 'selected' : '' }}>
                                                    {{ \App\CPU\translate('Confirmed') }}</option>
                                                <option value="canceled"
                                                    {{ $userInfo->order_status == 'canceled' ? 'selected' : '' }}>
                                                    {{ \App\CPU\translate('Canceled') }} </option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $userInfo->order_note }}
                            </td>
                            <td>{{ $userInfo->type }}</td>
                            <td>

<<<<<<< HEAD
                                <div class="d-flex justify-content-between">
                                    <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm mr-2 mb-2"
                                        style="cursor: pointer;" href="{{ route('admin.user-info.view', $userInfo->id) }}">
                                        <i class="tio-visible"></i>
                                    </a>
                                    <a class="btn btn-danger btn-sm delete mb-2 mr-2" style="cursor: pointer;"
                                        id="{{ $userInfo['id'] }}" title="{{ \App\CPU\translate('Delete') }}">
                                        <i class="tio-delete"></i>
                                    </a>
                                </div>

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
                            {!! $userInfos->links() !!}
=======
                                                <div class="d-flex justify-content-between">
                                                    <a title="{{ \App\CPU\translate('View') }}"
                                                        class="btn btn-info btn-sm mr-2 mb-2" style="cursor: pointer;"
                                                        href="{{ route('admin.user-info.view', $userInfo->id) }}">
                                                        <i class="tio-visible"></i>
                                                    </a>
                                                    @if (auth('admin')->user()->admin_role_id == 3)
                                                        <a class="btn btn-danger btn-sm delete mb-2 mr-2"
                                                            style="cursor: pointer;" id="{{ $userInfo['id'] }}"
                                                            title="{{ \App\CPU\translate('Delete') }}">
                                                            <i class="tio-delete"></i>
                                                        </a>
                                                    @endif
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
>>>>>>> 08b253713f0288bba1d245f57280bfcf797b356b
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
<<<<<<< HEAD
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
=======
        </div>
    </div>
@endsection

@push('script')
>>>>>>> 08b253713f0288bba1d245f57280bfcf797b356b
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
        function order_status(status, id) {

            var orderStatus = status ? status : 'pending';

            if (status === 'confirmed') {
                Swal.fire({
                    title: '{{ \App\CPU\translate('Are you sure Change this?') }}!',
                    text: "{{ \App\CPU\translate('Think before you completed') }}.",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                            <input type="hidden" name="order_status" value="${status}">
                            <input type="hidden" name="id" value="${id}">
                            <input required
                                class="form-control wedding-input-text wizard-input-pad"
                                type="text"
                                name="note"
                                id="note"
                                placeholder="For ${status} note">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{ \App\CPU\translate('Yes, Change it') }}!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.user-info.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {
                                toastr.success('Status Change successfully');
                                location.reload();

                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            } else if (status === 'canceled') {
                Swal.fire({
                    title: 'Are you sure Change this?',
                    text: "You won't be able to revert this!",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                            <input type="hidden" name="order_status" value="canceled">
                            <input type="hidden" name="id" value="${id}">
                            <input required class="form-control wedding-input-text wizard-input-pad" type="text" name="note" id="note" placeholder="For ${status} note">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: 'Yes, Change it!',
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.user-info.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {

                                toastr.success('Status Change successfully');
                                location.reload();
                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: '{{ \App\CPU\translate('Are you sure Change this') }}?',
                    text: "You won't be able to revert this!",
                    html: `
                            <br />
                            <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                                <input type="hidden" name="order_status" value="${status}">
                                <input type="hidden" name="id" value="${id}">
                                <input
                                    required
                                    class="form-control wedding-input-text wizard-input-pad"
                                    type="text"
                                    name="note"
                                    id="note"
                                    placeholder="For ${status} note"
                                >
                            </form>
                        `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{ \App\CPU\translate('Yes, Change it') }}!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.user-info.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {

                                toastr.success('Status Change successfully');
                                location.reload();
                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            }

        }
    </script>
    <script>
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{ \App\CPU\translate('Are_you_sure_delete_this') }}?',
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
                        url: "{{ route('admin.user-info.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('User_deleted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
    <script>
        function order_status(status, id) {

            var orderStatus = status ? status : 'pending';

            if (status === 'confirmed') {
                Swal.fire({
                    title: '{{ \App\CPU\translate('Are you sure Change this?') }}!',
                    text: "{{ \App\CPU\translate('Think before you completed') }}.",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                            <input type="hidden" name="order_status" value="${status}">
                            <input type="hidden" name="id" value="${id}">
                            <input required
                                class="form-control wedding-input-text wizard-input-pad"
                                type="text"
                                name="note"
                                id="note"
                                placeholder="For ${status} note">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{ \App\CPU\translate('Yes, Change it') }}!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.user-info.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {
                                toastr.success('Status Change successfully');
                                location.reload();

                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            } else if (status === 'canceled') {
                Swal.fire({
                    title: 'Are you sure Change this?',
                    text: "You won't be able to revert this!",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                            <input type="hidden" name="order_status" value="canceled">
                            <input type="hidden" name="id" value="${id}">
                            <input required class="form-control wedding-input-text wizard-input-pad" type="text" name="note" id="note" placeholder="For ${status} note">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: 'Yes, Change it!',
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.user-info.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {

                                toastr.success('Status Change successfully');
                                location.reload();
                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: '{{ \App\CPU\translate('Are you sure Change this') }}?',
                    text: "You won't be able to revert this!",
                    html: `
                            <br />
                            <form class="form-horizontal" action="{{ route('admin.user-info.status') }}" method="post">
                                <input type="hidden" name="order_status" value="${status}">
                                <input type="hidden" name="id" value="${id}">
                                <input
                                    required
                                    class="form-control wedding-input-text wizard-input-pad"
                                    type="text"
                                    name="note"
                                    id="note"
                                    placeholder="For ${status} note"
                                >
                            </form>
                        `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{ \App\CPU\translate('Yes, Change it') }}!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.user-info.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {

                                toastr.success('Status Change successfully');
                                location.reload();
                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            }

        }
    </script>
@endpush
