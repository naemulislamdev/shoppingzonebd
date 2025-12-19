@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Investors List'))
@push('css_or_js')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap4.css">
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
            <h1 class="h3 mb-0 text-black-50">Investor Information</h1>
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
                                    ({{ $investors->count() }})</h5>
                            </div>
                            <div class="col-lg-2">
                                Export :
                                <a href="{{ route('admin.investors.bulk-export') }}" class="btn btn-success btn-sm">
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
                                        <th style="width: 10%;">Occupation</th>
                                        <th style="width: 10%;">Investment Amount</th>
                                        <th style="width: 10%;">Status</th>
                                        <th style="width: 10%;">Remark</th>
                                        <th style="width: 5%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($investors as $k => $investor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($investor->created_at)->format('d M Y') }}
                                                <br>
                                                {{ date('h:i A', strtotime($investor['created_at'])) }}
                                            </td>
                                            <td>{{ $investor['name'] }}</td>
                                            <td>{{ $investor['mobile_number'] }}</td>
                                            <td>{{ $investor['address'] }}</td>
                                            <td>{{ $investor['occupation'] }}</td>
                                            <td>{{ $investor['investment_amount'] }}</td>
                                            <td class="investStatus_{{ $investor->id }}">
                                                {{ $investor['status'] == 0 ? 'Unseen' : 'Seen' }}</td>
                                            <td id="remark_td_{{ $investor->id }}" class="text-center">
                                                @if ($investor->remark)
                                                    <span class="remark_text">{{ $investor->remark }}</span>
                                                @else
                                                    <a href="#" class="btn btn-sm text-center btn-primary my-2 addRemarkBtn"
                                                        data-toggle="modal"
                                                        data-target="#remarkAddModal_{{ $investor->id }}">
                                                        Add
                                                    </a>
                                                @endif
                                            </td>

                                            <td>

                                                <div class="d-flex justify-content-between">
                                                    <a title="{{ \App\CPU\translate('View') }}"
                                                        class="btn btn-info btn-sm mr-2 mb-2 {{ !$investor->status ? 'viewBtn' : '' }} visiable_{{$investor->id}}"
                                                        style="cursor: pointer;" href="#"
                                                        data-id="{{ $investor->id }}" data-toggle="modal"
                                                        data-target="#viewInvestorModal_{{ $investor['id'] }}">
                                                        <i class="tio-visible"></i>
                                                    </a>
                                                    @if (auth('admin')->user()->admin_role_id == 1)
                                                        <a class="btn btn-danger btn-sm delete mb-2 mr-2"
                                                            style="cursor: pointer;" id="{{ $investor['id'] }}"
                                                            title="{{ \App\CPU\translate('Delete') }}">
                                                            <i class="tio-delete"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- Remark modal --}}
                                        <div class="modal fade" id="remarkAddModal_{{ $investor->id }}" tabindex="-1"
                                            data-backdrop="static">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form class="remarkForm">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $investor->id }}">

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Update Remark</h5>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Remark <span class="text-danger">*</span></label>
                                                                <textarea name="remark" class="form-control" required></textarea>
                                                                <small class="text-danger error_remark"></small>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        {{-- Remark modal --}}
                                        <!-- view investor Modal start-->
                                        <div class="modal fade" id="viewInvestorModal_{{ $investor['id'] }}" tabindex="-1"
                                            aria-labelledby="viewCareerModalLabel" aria-hidden="true">
                                            <div class=" modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="subcategoryModal">
                                                            {{ \App\CPU\translate('Investor_info') }}</h3>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div>

                                                            {{-- ekhane add kore daw --}}
                                                            <div class="row">
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Investor Name</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $investor->name }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Phone</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $investor->mobile_number }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Date & Time</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ \Carbon\Carbon::parse($investor->created_at)->format('d M Y') }}

                                                                                {{ date('h:i A', strtotime($investor['created_at'])) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Occupation</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $investor->occupation }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Investment Amount</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $investor->investment_amount }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Remark Note</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $investor->remark }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mb-3">
                                                                    <div class="row">
                                                                        <div class="col-3">Status</div>
                                                                        <div class="col-2">:</div>
                                                                        <div class="col-7">
                                                                            <strong>{{ $investor['status'] == 0 ? 'Unseen' : 'Seen' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-t-0">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{ \App\CPU\translate('close') }}</button>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- view investor Modal End-->
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
    <!-- Page level plugins -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
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
                        url: "{{ route('admin.investors.delete') }}",
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
        // ajax for view status change
        $(document).on('click', '.viewBtn', function() {
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.investors.view') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    $(`.investStatus_${id}`).html('Seen');
                    $(`.visiable_${id}`).removeClass('viewBtn');
                }
            });
        });
    </script>
    <script>
        // for remark ajax
        $(document).on('submit', '.remarkForm', function(e) {
            e.preventDefault();

            let form = $(this);
            let modal = form.closest('.modal'); // ✅ exact modal
            let formData = form.serialize();

            $.ajax({
                url: "{{ route('admin.investors.update_remark') }}",
                type: "POST",
                data: formData,
                success: function(response) {

                    if (response.status === true) {

                        // ✅ Update TD
                        $('#remark_td_' + response.id).html(
                            '<span class="remark_text">' + response.remark + '</span>'
                        );

                        // ✅ Hide modal (Bootstrap fix)
                        modal.modal('hide');

                        // ✅ Remove backdrop manually (IMPORTANT)
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');

                        // ✅ Reset form
                        form[0].reset();
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endpush
