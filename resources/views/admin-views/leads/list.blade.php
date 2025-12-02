@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('leads List'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
            <h1 class="h3 mb-0 text-black-50">{{ \App\CPU\translate('Customer') }} {{ \App\CPU\translate('leads') }}
                {{ \App\CPU\translate('List') }}</h1>
        </div>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="flex-start col-lg-3 mb-3 mb-lg-0">
                                <h5>{{ \App\CPU\translate('Customer') }} {{ \App\CPU\translate('Leads') }}
                                    {{ \App\CPU\translate('table') }} </h5>
                                <h5
                                    style="color: red; margin-{{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 5px">
                                    ({{ $leads->total() }})</h5>
                            </div>
                            <div class="col-lg-2">
                                Export :
                                <a href="{{ route('admin.leads.bulk-export') }}" class="btn btn-success btn-sm">
                                    <i class="tio-file-text"></i> Excel
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search by Name or Phone') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CPU\translate('search') }}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="datatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-hover table-borderless table-thead-bordered table-align-middle card-table"
                                style="width:100%;">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%">{{ \App\CPU\translate('SL') }}#</th>
                                        <th style="width: 5%">{{ \App\CPU\translate('Date') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Name') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Phone') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Address') }}</th>
                                        <th style="width: 15%">{{ \App\CPU\translate('Division') }}</th>
                                        <th style="width: 40%">{{ \App\CPU\translate('District') }}</th>
                                        <th style="width: 40%">{{ \App\CPU\translate('Status') }}</th>
                                        <th style="width: 10%;">Remark</th>
                                        <th style="width: 10%">{{ \App\CPU\translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $k => $lead)
                                        <tr>
                                            <td style="width: 5%">{{ $loop->iteration }}</td>
                                            <td style="width: 5%">
                                                {{ \Carbon\Carbon::parse($lead->created_at)->format('d M Y') }}</td>
                                            <td style="width: 15%">{{ $lead['name'] }}</td>
                                            <td style="width: 15%">{{ $lead['phone'] }}</td>
                                            <td style="width: 15%">{{ $lead['address'] }}</td>
                                            <td style="width: 15%">{{ $lead['division'] }}</td>
                                            <td style="width: 30%;">{{ $lead['district'] }}</td>
                                            <td style="width: 10%;">{{ $lead['status'] == 0 ? 'Unseen' : 'Seen' }}</td>
                                            <td>
                                                @if ($lead->remark != null)
                                                    {{ $lead->remark }}
                                                @else
                                                    <a href="javascript:;" class="btn btn-sm btn-primary my-2"
                                                        data-toggle="modal"
                                                        data-target="#remarkAddModal_{{ $lead->id }}">Add
                                                    </a>
                                                @endif
                                            </td>
                                            <td style="width: 10%">
                                                <div class="d-flex justify-content-between">
                                                    <a title="{{ \App\CPU\translate('View') }}" class="btn btn-info btn-sm"
                                                        style="cursor: pointer;"
                                                        href="{{ route('admin.leads.view', $lead->id) }}">
                                                        <i class="tio-visible"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm delete" style="cursor: pointer;"
                                                        id="{{ $lead['id'] }}"
                                                        title="{{ \App\CPU\translate('Delete') }}">
                                                        <i class="tio-delete"></i>
                                                    </a>
                                                </div>

                                            </td>
                                        </tr>
                                        <div class="modal fade" id="remarkAddModal_{{ $lead->id }}" tabindex="-1"
                                            data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <form action="{{ route('admin.leads.update_remark', $lead->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Remark
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Remark <span class="text-danger">*</span></label>
                                                                <textarea name="remark" class="form-control" placeholder="Enter your remark"></textarea>
                                                                @error('remark')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $leads->links() }}
                    </div>
                    @if (count($leads) == 0)
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
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
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
                        url: "{{ route('admin.leads.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function() {
                            toastr.success(
                                '{{ \App\CPU\translate('Lead_deleted_successfully') }}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
