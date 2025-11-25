@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('stock limit products'))

@push('css_or_js')
@endpush

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Stock Limit products') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> <i
                            class="tio-files"></i>{{ \App\CPU\translate(' Stock limit products ') }}
                        {{ \App\CPU\translate('list') }}</span>
                    {{-- <span class="badge badge-soft-dark mx-2">{{ $investors->total() }}</span> --}}
                </div>

            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card p-3">
            <!-- Header -->
            <div class="card-header">
                <div class="row flex-between justify-content-between flex-grow-1">

                    <div class="col-6 col-md-5 mt-2 mt-sm-0">
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
                                        data-action="{{ route('admin.product.bulk-export-stock-limit') }}">
                                        {{ \App\CPU\translate('export') }}
                                    </button>


                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="col-6 mt-1 col-md-6 col-lg-3">
                        <select name="qty_ordr_sort" class="form-control"
                            onchange="location.href='{{ route('admin.product.stock-limit-list', ['in_house', '']) }}/?sort_oqrderQty='+this.value">
                            <option value="default" {{ $sort_oqrderQty == 'default' ? 'selected' : '' }}>
                                {{ \App\CPU\translate('default_sort') }}</option>
                            <option value="quantity_asc" {{ $sort_oqrderQty == 'quantity_asc' ? 'selected' : '' }}>
                                {{ \App\CPU\translate('quantity_sort_by_(low_to_high)') }}</option>
                            <option value="quantity_desc" {{ $sort_oqrderQty == 'quantity_desc' ? 'selected' : '' }}>
                                {{ \App\CPU\translate('quantity_sort_by_(high_to_low)') }}</option>
                            <option value="order_asc" {{ $sort_oqrderQty == 'order_asc' ? 'selected' : '' }}>
                                {{ \App\CPU\translate('order_sort_by_(low_to_high)') }}</option>
                            <option value="order_desc" {{ $sort_oqrderQty == 'order_desc' ? 'selected' : '' }}>
                                {{ \App\CPU\translate('order_sort_by_(high_to_low)') }}</option>
                        </select>
                    </div>

                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <table id="example" class="display" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th>{{ \App\CPU\translate('SL#') }}</th>
                        <th>{{ \App\CPU\translate('Product Name') }}</th>
                        <th>{{ \App\CPU\translate('purchase_price') }}</th>
                        <th>{{ \App\CPU\translate('selling_price') }}</th>
                        {{-- <th>{{\App\CPU\translate('verify_status')}}</th> --}}
                        <th>{{ \App\CPU\translate('Active') }} {{ \App\CPU\translate('status') }}</th>
                        <th>{{ \App\CPU\translate('quantity') }}</th>
                        <th>{{ \App\CPU\translate('orders') }}</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($pro as $k => $p)
                        <tr>
                            <th scope="row">{{ $pro->firstItem() + $k }}</th>
                            <td>
                                <a href="{{ route('admin.product.view', [$p['id']]) }}">
                                    {{ \Illuminate\Support\Str::limit($p['name'], 20) }}
                                </a>
                            </td>
                            <td>
                                {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['purchase_price'])) }}
                            </td>
                            <td>
                                {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['unit_price'])) }}
                            </td>
                            {{-- <td>
                                            @if ($p->request_status == 0)
                                                <label class="badge badge-warning">{{\App\CPU\translate('New Request')}}</label>
                                            @elseif($p->request_status == 1)
                                                <label class="badge badge-success">{{\App\CPU\translate('Approved')}}</label>
                                            @elseif($p->request_status == 2)
                                                <label class="badge badge-danger">{{\App\CPU\translate('Denied')}}</label>
                                            @endif
                                        </td> --}}
                            @if ($p->request_status != 2)
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" class="status" id="{{ $p['id'] }}"
                                            {{ $p->status == 1 ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            @endif
                            <td>
                                {{ $p['current_stock'] }}
                                <button class="btn btn-sm" id="{{ $p->id }}"
                                    onclick="update_quantity({{ $p->id }})" type="button" data-toggle="modal"
                                    data-target="#update-quantity" title="{{ \App\CPU\translate('update_quantity') }}">
                                    <i class="tio-add-circle"></i>

                                </button>
                            </td>
                            <td>
                                {{ $p['order_details_count'] }}
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
                            {{-- {!! $investors->links() !!} --}}
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
    <div class="modal fade" id="update-quantity" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('admin.product.update-quantity') }}" method="post" class="row">
                        @csrf
                        <div class="card mt-2 rest-part" style="width: 100%"></div>
                        <div class="form-group col-sm-12 card card-footer">
                            <button class="btn btn-primary" class="btn btn-primary"
                                type="submit">{{ \App\CPU\translate('submit') }}</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                {{ \App\CPU\translate('close') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
        function update_quantity(val) {
            $.get({
                url: '{{ url('/') }}/admin/product/get-variations?id=' + val,
                dataType: 'json',
                success: function(data) {
                    console.log(data)
                    $('.rest-part').empty().html(data.view);
                },
            });
        }

        function update_qty() {
            var total_qty = 0;
            var qty_elements = $('input[name^="qty_"]');
            for (var i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if (qty_elements.length > 0) {

                $('input[name="current_stock"]').attr("readonly", true);
                $('input[name="current_stock"]').val(total_qty);
            } else {
                $('input[name="current_stock"]').attr("readonly", false);
            }
        }
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
                url: "{{ route('admin.product.status-update') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('{{ \App\CPU\translate('Status updated successfully') }}');
                    } else if (data.success == false) {
                        toastr.error(
                            '{{ \App\CPU\translate('Status updated failed. Product must be approved') }}'
                            );
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush
