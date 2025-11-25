@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Products'))
<style>
    .avatar-lg {
        transition: all 0.3s ease;
    }
    .avatar-lg:hover {
        transform: scale(4.1);
    }
</style>
@push('css_or_js')
@endpush

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
    <div class="content container-fluid px-0">
        <nav aria-label="breadcrumb pl-3">
            <ol class="breadcrumb pl-3">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Products') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1 pl-3">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> {{ \App\CPU\translate('Products') }}
                        {{ \App\CPU\translate('List') }}</span>
                    <span class="badge badge-soft-dark mx-2">{{ $pro->total() }}</span>
                </div>

            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card p-3">
            <!-- Header -->
            <div class="card-header ">
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
                                        data-action="{{ route('admin.product.bulk-export') }}">
                                        {{ \App\CPU\translate('export') }}
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="col-6">
                        @if (!isset($request_status))
                                <a href="{{route('admin.product.add-new')}}" class="btn btn-primary  float-right">
                                    <i class="tio-add-circle"></i>
                                    <span class="text">{{\App\CPU\translate('Add new product')}}</span>
                                </a>
                            @endif
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
                        <th>{{ \App\CPU\translate('Image') }}</th>
                        <th>{{ \App\CPU\translate('Product Name') }}</th>
                        <th>{{ \App\CPU\translate('Code') }}</th>
                        <th>{{ \App\CPU\translate('purchase_price') }}</th>
                        <th>{{ \App\CPU\translate('selling_price') }}</th>
                        <th>{{ \App\CPU\translate('featured') }}</th>
                        <th>{{ \App\CPU\translate('New Arrival') }}</th>
                        <th>{{ \App\CPU\translate('Active') }} {{ \App\CPU\translate('status') }}</th>
                        <th style="width: 5px" class="text-center">{{ \App\CPU\translate('Action') }}</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($pro as $k => $p)
                        <tr>
                            <th scope="row">{{ $pro->firstItem() + $k }}</th>
                            <td>
                                <img onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                    src="{{ asset('storage/product/thumbnail') }}/{{ $p['thumbnail'] }}"
                                    class="avatar avatar-lg product-list-img">
                            </td>
                            <td>
                                <a href="{{ route('admin.product.view', [$p['id']]) }}">
                                    {{ \Illuminate\Support\Str::limit($p['name'], 20) }}
                                </a>
                            </td>
                            <td>
                                {{ $p->code }}
                            </td>
                            <td>
                                {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['purchase_price'])) }}
                            </td>
                            <td>
                                {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['unit_price'])) }}
                            </td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" onclick="featured_status('{{ $p['id'] }}')"
                                        {{ $p->featured == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" onclick="arrival_status('{{ $p['id'] }}')"
                                        {{ $p->arrival == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <label class="switch switch-status">
                                    <input type="checkbox" class="status" id="{{ $p['id'] }}"
                                        {{ $p->status == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td style="display: flex; flex-direction: column; gap: 5px; align-items: center; justify-content: center">
                                <a class="btn btn-warning btn-sm" title="{{ \App\CPU\translate('barcode') }}"
                                    href="{{ route('admin.product.barcode', [$p['id']]) }}">
                                    <i class="tio-barcode"></i>
                                </a>

                                <a class="btn btn-info btn-sm" title="{{ \App\CPU\translate('view') }}"
                                    href="{{ route('admin.product.view', [$p['id']]) }}">
                                    <i class="tio-visible"></i>
                                </a>
                                <a class="btn btn-primary btn-sm" title="{{ \App\CPU\translate('Edit') }}"
                                    href="{{ route('admin.product.edit', [$p['id']]) }}">
                                    <i class="tio-edit"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" href="javascript:"
                                    title="{{ \App\CPU\translate('Delete') }}"
                                    onclick="form_alert('product-{{ $p['id'] }}','Want to delete this item ?')">
                                    <i class="tio-add-to-trash"></i>
                                </a>
                                <form action="{{ route('admin.product.delete', [$p['id']]) }}" method="post"
                                    id="product-{{ $p['id'] }}">
                                    @csrf @method('delete')
                                </form>
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
@endpush
