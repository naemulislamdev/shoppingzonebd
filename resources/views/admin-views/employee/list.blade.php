@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Employee List'))

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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Employees') }}</li>
            </ol>
        </nav>
        <!-- Page Header -->
        <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <span class="h1"> <span class="page-header-title"></span> {{ \App\CPU\translate('Employee') }}
                        {{ \App\CPU\translate('List') }}</span>
                    <span class="badge badge-soft-dark mx-2">{{ $em->total() }}</span>
                </div>

            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card p-3">
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
                                        data-action="{{ route('admin.employee.bulk-export') }}">
                                        {{ \App\CPU\translate('export') }}
                                    </button>


                                </div>

                            </div>
                        </form>
                    </div>


                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <table id="example" class="display" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th>{{ \App\CPU\translate('SL') }}#</th>
                        <th>{{ \App\CPU\translate('Name') }}</th>
                        <th>{{ \App\CPU\translate('Email') }}</th>
                        <th>{{ \App\CPU\translate('Phone') }}</th>
                        <th>Branch</th>
                        <th>{{ \App\CPU\translate('Role') }}</th>
                        <th>{{ \App\CPU\translate('Status') }}</th>
                        <th>{{ \App\CPU\translate('action') }}</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($em as $k => $e)
                        @if ($e->role)
                            <tr>
                                <th scope="row">{{ $k + 1 }}</th>
                                <td class="text-capitalize">{{ $e['name'] }}</td>
                                <td>
                                    {{ $e['email'] }}
                                </td>
                                <td>{{ $e['phone'] }}</td>
                                <td>{{ $e->branch->name ?? '' }}</td>
                                <td>{{ $e->role['name'] }}</td>
                                <td>
                                    <label class="toggle-switch toggle-switch-sm">
                                        <input type="checkbox" class="toggle-switch-input"
                                            onclick="location.href='{{ route('admin.employee.status', [$e['id'], $e->status ? 0 : 1]) }}'"
                                            class="toggle-switch-input" {{ $e->status ? 'checked' : '' }}>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ route('admin.employee.update', [$e['id']]) }}"
                                        class="btn btn-primary btn-sm" title="{{ \App\CPU\translate('Edit') }}">
                                        <i class="tio-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
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
                            {!! $em->links() !!}
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
