@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Investor View'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                        {{ \App\CPU\translate('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('Investor') }}
                    {{ \App\CPU\translate('view') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <h1 class="h3 mb-0 text-black-50">{{ \App\CPU\translate('View User Information') }}</h1>
                <a href="{{ route('admin.investors.list') }}"
                    class="btn btn-primary">{{ \App\CPU\translate('Back to Users Information') }}</a>
            </div>

            <!-- Content Row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body mt-3 ml-4">
                            <div class="row "
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">

                                <div class=" col-md-9 col-lg-9 hidden-xs hidden-sm">
                                    <strong style="margin-right: 20px">{{ $investor->name }}</strong>
                                  
                                    <table class="table table-user-information">
                                        <tbody>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Investor') }} {{ \App\CPU\translate('name') }}:
                                                </td>
                                                <td>{{ $investor->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Phone') }}:</td>
                                                <td>{{ $investor->mobile_number }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Address') }}:</td>
                                                <td>{{ $investor->address }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Occupation') }}:</td>
                                                <td>{{ $investor->occupation }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Investment Amount') }}:</td>
                                                <td>{{ $investor->investment_amount }}</td>
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
