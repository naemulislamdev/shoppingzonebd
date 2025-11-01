@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('User View'))
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
                <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('User') }}
                    {{ \App\CPU\translate('view') }}</li>
            </ol>
        </nav>
        <!-- Page Heading -->
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <h1 class="h3 mb-0 text-black-50">{{ \App\CPU\translate('View User Information') }}</h1>
                <a href="{{ route('admin.user-info.list') }}" class="btn btn-primary">{{ \App\CPU\translate('Back to Users Information') }}</a>
            </div>

            <!-- Content Row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body mt-3 ml-4">
                            <div class="row "
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">

                                <div class=" col-md-9 col-lg-9 hidden-xs hidden-sm">
                                    <strong style="margin-right: 20px">{{ $userInfo->name }}</strong>
                                    @if ($userInfo->status == 1)
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
                                                <td>{{ $userInfo->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Phone') }}:</td>
                                                <td>{{ $userInfo->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ \App\CPU\translate('Address') }}:</td>
                                                <td>{{ $userInfo->address }}</td>
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
