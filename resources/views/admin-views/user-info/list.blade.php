@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Users List'))
@push('css_or_js')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <div class="content container-fluid p-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Customer Message') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h1 class="h3 mb-0 text-black-50">User Information</h1>
        </div>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="flex-start col-lg-3 mb-3 mb-lg-0">
                                <h5>{{ \App\CPU\translate('User') }} {{ \App\CPU\translate('Information') }}
                                    {{ \App\CPU\translate('table') }} </h5>
                                <h5 style="color: red;">
                                    ({{ $userInfos->count() }})</h5>
                            </div>
                            <div class="col-12 col-md-5 mt-2 mt-sm-0">
                                <form action="{{ url()->current() }}" id="form-data" method="GET">

                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <input type="date" name="from" value="{{ $from ?? date('Y-m-d') }}"
                                                id="from_date" class="form-control">
                                        </div>
                                        <div class="col-12 col-sm-4 mt-2 mt-sm-0">
                                            <input type="date" value="{{ $to ?? date('Y-m-d') }}" name="to"
                                                id="to_date" class="form-control">
                                        </div>
                                        <div class="col-12 col-sm-2 mt-2 mt-sm-0 ">
                                            <button type="submit" class="btn btn-primary float-right float-sm-none"
                                                onclick="formUrlChange(this)" data-action="{{ url()->current() }}">
                                                {{ \App\CPU\translate('filter') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-2">
                                Export :
                                <a href="{{ route('admin.user-info.bulk-export') }}" class="btn btn-success btn-sm">
                                    <i class="tio-file-text"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
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
                                                                        $variationParts[] =
                                                                            ucfirst($key) . ': ' . ucfirst($value);
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
                                                                ? \App\Model\Color::where(
                                                                    'code',
                                                                    $product_details['color'],
                                                                )->value('name')
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
                                                    <span class="badge badge-danger">Pending</span>
                                                @elseif($userInfo['order_process'] == 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @endif
                                            </td>
                                            <td class="m-0 p-0">
                                                <div class="form-group">
                                                    <div class="hs-unfold float-right">
                                                        <div class="dropdown">
                                                            <select name="order_status"
                                                                onchange="order_status(this.value, {{ $userInfo['id'] }})"
                                                                class="status form-control"
                                                                data-id="{{ $userInfo['id'] }}">

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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            new DataTable('#example');
        });
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
