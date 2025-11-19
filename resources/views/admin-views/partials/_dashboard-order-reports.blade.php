
<style>
/* Premium Card Style */
.order-card {
    border-radius: 16px !important;
    padding: 18px !important;
    border: none !important;
    transition: all 0.25s ease-in-out;
    color: #ffffff !important;
    box-shadow: 0px 8px 20px rgba(0,0,0,0.08);
    text-decoration: none;
    display: block;
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0px 12px 26px rgba(0,0,0,0.15);
}

/* Title */
.order-card-title {
    font-size: 15px;
    font-weight: 600;
    letter-spacing: .3px;
    color: #ffffff !important;
    margin-bottom: 8px;
}

/* Quantity + Amount Text */
.order-card-details {
    font-size: 16px;
    line-height: 22px;
    margin-top: 6px;
    font-weight: 500;
    color: #ffffff !important;
}

/* Background Colors (soft gradient look) */
.bg-all-order {
    background: linear-gradient(135deg, #3b82f6, #60a5fa) !important;
}

.bg-pending {
    background: linear-gradient(135deg, #c46800, #ff9f1c) !important;
}

.bg-confirmed {
    background: linear-gradient(135deg, #10b981, #34d399) !important;
}

.bg-canceled {
    background: linear-gradient(135deg, #f43f5e, #fb7185) !important;
}
</style>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card order-card bg-all-order h-100"
       href="{{ route('admin.orders.list', ['pending']) }}"
       style="color: #FFFFFF">
        <div class="card-body p-0">
            <h6 class="order-card-title">All Order</h6>
            <div class="order-card-details">
                Quantity: {{ $results->total_orders ?? 0 }} <br>
                Amount: {{ $results->total_amount ?? 0 }}
            </div>
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card order-card bg-pending h-100"
       href="{{ route('admin.orders.list', ['pending']) }}"
       style="color: #FFFFFF">
        <div class="card-body p-0">
            <h6 class="order-card-title">{{ \App\CPU\translate('pending') }}</h6>
            <div class="order-card-details">
                Quantity: {{ $results->pending_qty ?? 0 }} <br>
                Amount: {{ $results->pending_amount ?? 0 }}
            </div>
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card order-card bg-confirmed h-100"
       href="{{ route('admin.orders.list', ['confirmed']) }}"
       style="color: #FFFFFF">
        <div class="card-body p-0">
            <h6 class="order-card-title">{{ \App\CPU\translate('confirmed') }}</h6>
            <div class="order-card-details">
                Quantity: {{ $results->confirmed_qty ?? 0 }} <br>
                Amount: {{ $results->confirmed_amount ?? 0 }}
            </div>
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card order-card bg-canceled h-100"
       href="{{ route('admin.orders.list', ['canceled']) }}"
       style="color: #FFFFFF">
        <div class="card-body p-0">
            <h6 class="order-card-title">Canceled</h6>
            <div class="order-card-details">
                Quantity: {{ $results->canceled_qty ?? 0 }} <br>
                Amount: {{ $results->canceled_amount ?? 0 }}
            </div>
        </div>
    </a>
    <!-- End Card -->
</div>
