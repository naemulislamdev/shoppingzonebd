@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Leads View'))
@push('css_or_js')
    <link href="{{ asset('assets/back-end') }}/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/back-end/css/croppie.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                        {{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Leads') }}
                    {{ \App\CPU\translate('view') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <h1 class="h3 mb-0 text-black-50">{{ \App\CPU\translate('View Lead Information') }}</h1>
                <a href="{{ route('admin.leads.list') }}" class="btn btn-primary">{{ \App\CPU\translate('Back to Leads') }}</a>
            </div>

            <!-- Content Row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body mt-3 ml-4">
                            <div class="row "
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">

                                <div class=" col-md-9 col-lg-9 hidden-xs hidden-sm">
                                    <strong style="margin-right: 20px">{{ $lead->name }}</strong>
                                    @if ($lead->status == 1)
                                        <label
                                            style="color: green; border: 1px solid;padding: 2px;border-radius: 10px">{{ \App\CPU\translate('Seen') }}</label>
                                    @else
                                        <label
                                            style="color: red; border: 1px solid;padding: 2px;border-radius: 10px">{{ \App\CPU\translate('Not_Seen_Yet') }}</label>
                                    @endif
                                    <br>
                                    <table class="table table-user-information">
                                        <tbody>
                                            <tr>
                                                <td>{{ \App\CPU\translate('User') }} {{ \App\CPU\translate('name') }}:
                                                </td>
                                                <td>{{ $lead->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Phone') }}:</td>
                                                <td>{{ $lead->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Address') }}:</td>
                                                <td>{{ $lead->address }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Division') }}:</td>
                                                <td>
                                                    <p style="font-width:16px;"> {{ $lead->division }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('District') }}:</td>
                                                <td>
                                                    <p style="font-width:16px;"> {{ $lead->district }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Upazila') }}:</td>
                                                <td>
                                                    <p style="font-width:16px;"> {{ $lead->upazila }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Showroom Size (Sft)') }}:</td>
                                                <td>
                                                    <p style="font-width:16px;"> {{ $lead->showroom_size }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Showroom Location') }}:</td>
                                                <td>
                                                    <p style="font-width:16px;"> {{ $lead->showroom_location }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
@endpush
