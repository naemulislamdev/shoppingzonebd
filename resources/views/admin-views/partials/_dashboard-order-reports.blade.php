<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card bg-primary card-hover-shadow h-100" href="{{ route('admin.orders.list', ['pending']) }}"
        style="color: #FFFFFF">
        <div class="card-body">
            <div class="align-items-center mb-1">
                <h6 class="card-subtitle" style="color: #ffffff!important;">All Order</h6>
                <div>
                    <span class="card-title" style="color: #ffffff!important;">
                        Quantity: {{ $results->total_orders ?? 0 }} <br>
                        Amount: {{ $results->total_amount ?? 0 }}
                    </span>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card card-hover-shadow h-100" href="{{ route('admin.orders.list', ['pending']) }}"
        style="color: #FFFFFF; background-color: #c46800;">
        <div class="card-body">
            <h6 class="card-subtitle" style="color: #ffffff!important;">{{ \App\CPU\translate('pending') }}</h6>
            <div class="align-items-center mb-1">
                <div>
                    <span class="card-title" style="color: #ffffff!important;">
                        Quantity: {{ $results->pending_qty ?? 0 }} <br>
                        Amount: {{ $results->pending_amount ?? 0 }}
                    </span>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card bg-success card-hover-shadow h-100" href="{{ route('admin.orders.list', ['confirmed']) }}">
        <div class="card-body">
            <h6 class="card-subtitle" style="color: #ffffff!important;">{{ \App\CPU\translate('confirmed') }}
            </h6>
            <div class="align-items-center mb-1">
                <div>
                    <span class="card-title" style="color: #ffffff!important;">
                        Quantity: {{ $results->confirmed_qty ?? 0 }} <br>
                        Amount: {{ $results->confirmed_amount ?? 0 }}
                    </span>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card bg-warning card-hover-shadow h-100" href="{{ route('admin.orders.list', ['canceled']) }}">
        <div class="card-body">
            <div class="align-items-center mb-1">
                <h6 class="card-subtitle" style="color: #ffffff!important;">Canceled
                </h6>
                <div>
                    <span class="card-title" style="color: #ffffff!important;">
                        Quantity: {{ $results->canceled_qty ?? 0 }} <br>
                        Amount: {{ $results->canceled_amount ?? 0 }}
                    </span>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>
