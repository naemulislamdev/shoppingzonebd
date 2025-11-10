@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('leads List'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    {{-- <link href="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap4.css">
@endpush

@section('content')
    <div class="content container-fluid">
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
                                <h5
                                    style="color: red;">
                                    ({{ $userInfos->count() }})</h5>
                            </div>
                            <div class="col-lg-2">
                                Export :
                                <a href="{{ route('admin.user-info.bulk-export') }}" class="btn btn-success btn-sm">
                                    <i class="tio-file-text"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th>Date and Time</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Product</th>
                                        <th>Order Process</th>
                                        <th>Type</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userInfos as $k => $userInfo)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($userInfo->created_at)->format('d M Y') }}{{date('h:i A',strtotime($userInfo['created_at']))}}</td>
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
                                            <td>{{ $userInfo->type }}</td>
                                            <td>

                                                <div class="d-flex justify-content-between">
                                                    <a title="{{ \App\CPU\translate('View') }}"
                                                        class="btn btn-info btn-sm mr-2 mb-2" style="cursor: pointer;"
                                                        href="{{ route('admin.user-info.view', $userInfo->id) }}">
                                                        <i class="tio-visible"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete mb-2 mr-2"
                                                        style="cursor: pointer;" id="{{ $userInfo['id'] }}"
                                                        title="{{ \App\CPU\translate('Delete') }}">
                                                        <i class="tio-delete"></i>
                                                    </a>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>SL#</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Product</th>
                                        <th>Order Process</th>
                                        <th>Type</th>
                                        <th>action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    {{-- <script src="{{ asset('assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap4.js"></script>
    <!-- Page level custom scripts -->
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
@endpush
