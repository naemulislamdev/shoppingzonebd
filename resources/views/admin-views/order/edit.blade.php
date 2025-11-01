@extends('layouts.back-end.app')

@section('title', 'Edit Order')

@section('content')
    <style>
        .v-color-box input,
        .v-size-box input {
            display: none;
        }

        .v-color-box,
        .v-size-box {
            display: flex;
            align-items: center;
            width: 4.375rem;
            height: 1.875rem !important;
            margin-top: 0rem;
            margin-right: .625rem;
        }

        .v-color-box>.color-label,
        .v-size-box>.size-label {
            cursor: pointer;
            border: .125rem solid #ccc;
            padding: .125rem .375rem !important;
            border-radius: .3125rem;
            width: 100%;
            text-align: center;
            /* height: 1.875rem !important; */
            position: relative;
        }

        .v-color-box>input:checked+.color-label,
        .v-size-box>input:checked+.size-label {
            border: .125rem solid #02ab16 !important;
        }

        .v-size-box>input:checked+.size-label::after {
            content: '✔';
            color: white;
            font-size: .75rem;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .product-list-box {
            padding: 10px 15px;
            background-color: #fff;
            border: 1px solid #e3e6ed;
            border-radius: 8px;
            margin-bottom: 6px;
            transition: all 0.2s ease;
        }

        .product-list-box:hover {
            background-color: #f8fafc;
            border-color: #007bff;
            transform: translateY(-2px);
        }

        .product-list-box:active {
            background-color: #e9f2ff;
        }

        .product-list-box img {
            border-radius: 6px;
        }

        .product-list-box strong {
            font-size: 14px;
            color: #212529;
        }

        .product-list-box small {
            font-size: 12px;
            color: #6c757d;
        }

        .product-list-box span {
            font-size: 13px;
            color: #0d6efd;
            font-weight: 500;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>


    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Order #{{ $order->id }}</h5>
            </div>
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <!-- Product Search -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="productSearch" class="form-control"
                                placeholder="Search product name / code / scan barcode">
                            <!-- Product search result dropdown -->
                            <div id="productResults" class="list-group position-absolute w-100 shadow-sm"
                                style="z-index: 1000; display:none; height:400px; overflow-y:auto;"></div>

                        </div>
                    </div>

                    <!-- Order Product Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="orderProductsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Variation</th>
                                    <th style="width:120px;">Price</th>
                                    <th style="width:100px;">Qty</th>
                                    <th style="width:120px;">Subtotal</th>
                                    <th style="width:80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="productRows">
                                @foreach ($order->details as $detail)
                                    @php
                                        $subtotalG =
                                            $detail['price'] * $detail->qty + $detail['tax'] - $detail['discount'];
                                        $price = \App\CPU\BackEndHelper::usd_to_currency($detail['price']);
                                        $subtotal = \App\CPU\BackEndHelper::usd_to_currency($subtotalG);
                                    @endphp
                                    <tr data-id="{{ $detail->id }}">
                                        <td><img src="{{ asset('storage/product/thumbnail/' . $detail->product->thumbnail ?? 'default.png') }}"
                                                width="60" class="rounded"></td>
                                        <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                        <td>{{ $detail->product->code ?? 'N/A' }}</td>
                                        <td>{{ $detail->variant ?? '-' }} - {{ $detail->variation ?? '-' }}</td>
                                        <td>
                                            {{ $price }}
                                            <input type="hidden" class="form-control price" value="{{ $price }}">
                                        </td>
                                        {{-- <td><input type="number" class="form-control price" value="{{$price}}"></td> --}}
                                        <td><input type="number" class="form-control qty" name="qty[]" value="{{ $detail->qty }}"
                                                min="1"></td>
                                        <td class="subtotal">{{ $subtotal }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-danger removeRow"><i
                                                    class="tio-delete"></i></button>
                                        </td>
                                        <input type="hidden" name="product_id[]" value="{{ $detail->product_id }}">
            <input type="hidden" name="Product_price[]" value="{{ $detail->price }}">
            <input type="hidden" name="variant[]" value="{{ $detail->variant }}">
            <input type="hidden" name="variation[]" value="{{ $detail->variation }}">
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Summary -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th>Subtotal</th>
                                    <td><input type="text" id="subtotal" class="form-control text-end" readonly></td>
                                </tr>
                                <tr>
                                    <th>Discount Type</th>
                                    <td>
                                        <select id="discount_type" class="form-control" name="discount_type">
                                            <option selected disabled>Select Type</option>
                                            <option value="fixed">Fixed</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td><input type="number" id="discount" name="discount_amount" class="form-control text-end" value="{{ \App\CPU\BackEndHelper::usd_to_currency($order->discount_amount) }}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Shipping Method</th>
                                    @php
                                        $shippingMethod = \App\Model\ShippingMethod::all();
                                    @endphp
                                    <td>
                                        <select id="shipping_method" class="form-control" name="shipping_method">
                                            <option selected disabled>Select Method</option>
                                            @foreach ($shippingMethod as $method)
                                                <option value="{{ $method->id }}"
                                                    {{ $order->details->first()->shipping_method_id == $method->id ? 'selected' : '' }}>
                                                    {{ $method->title }}
                                                    ({{ \App\CPU\BackEndHelper::usd_to_currency($method->cost) }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Delivery Charge</th>
                                    <td><input type="number" id="delivery_charge" readonly class="form-control text-end"
                                            value="{{ \App\CPU\BackEndHelper::usd_to_currency($order->shipping_cost) }}">
                                    </td>
                                </tr>
                                <tr class="table-secondary">
                                    <th>Grand Total</th>
                                    <td><input type="text" id="grand_total" name="grand_total" class="form-control text-end fw-bold"
                                            readonly>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success">Update Order</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('productRows');
            const subtotalField = document.getElementById('subtotal');
            const discountField = document.getElementById('discount');
            const discountTypeField = document.getElementById('discount_type');
            const deliveryField = document.getElementById('delivery_charge');
            const grandTotalField = document.getElementById('grand_total');
            const productSearch = document.getElementById('productSearch');
            const productResults = document.getElementById('productResults');

            // ======== CALCULATE TOTALS ========
            function calculateTotals() {
                let subtotal = 0;

                document.querySelectorAll('#productRows tr').forEach(row => {
                    const price = parseFloat(row.querySelector('.price')?.value) || 0;
                    const qty = parseFloat(row.querySelector('.qty')?.value) || 0;
                    const rowSubtotal = price * qty;

                    row.querySelector('.subtotal').textContent = rowSubtotal.toFixed(2);
                    subtotal += rowSubtotal;
                });

                const discountValue = parseFloat(discountField.value) || 0;
                const discountType = discountTypeField.value;
                const delivery = parseFloat(deliveryField.value) || 0;

                let discountAmount = 0;
                if (discountType === 'percentage') {
                    discountAmount = subtotal * (discountValue / 100);
                } else if (discountType === 'fixed') {
                    discountAmount = discountValue;
                }

                const grandTotal = subtotal - discountAmount + delivery;

                subtotalField.value = subtotal.toFixed(2);
                grandTotalField.value = grandTotal.toFixed(2);
            }

            // ======== SEARCH PRODUCT AJAX ========
            let typingTimer;
            productSearch.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                const query = this.value.trim();

                if (query.length < 2) {
                    productResults.style.display = 'none';
                    return;
                }

                typingTimer = setTimeout(() => {
                    fetch(`/admin/orders/products/search?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(products => {
                            if (products.length > 0) {
                                productResults.innerHTML = products.map(p => {
                                    const price = parseFloat(p.unit_price).toFixed(2);
                                    const image = p.thumbnail ?
                                        `/storage/product/thumbnail/${p.thumbnail}` :
                                        `/images/default-product.png`;

                                    // Fallback for product code
                                    const productCode = p.code || p.product_code ||
                                        'N/A';

                                    // Handle color variants safely
                                    const colors = (() => {
                                        if (!p.color_variant)
                                            return `<small class="text-muted">No color</small>`;
                                        let parsed = [];
                                        try {
                                            parsed = typeof p.color_variant ===
                                                "string" ?
                                                JSON.parse(p.color_variant) :
                                                p.color_variant;
                                        } catch {
                                            parsed = [];
                                        }

                                        if (!Array.isArray(parsed) || parsed
                                            .length === 0)
                                            return `<small class="text-muted">No color</small>`;

                                        return parsed
                                            .filter(c => c && (c.code || c
                                                .color))
                                            .map((c, i) => {
                                                const colorCode = c.code ||
                                                    c.color || '#ddd';
                                                const imageSrc = c.image ? c
                                                    .image : '';
                                                const labelStyle =
                                                    imageSrc ?
                                                    `background-image:url(${imageSrc}); background-size:cover; height:40px;` :
                                                    `background-color:${colorCode};`;
                                                return `
                <div class="v-color-box me-1 mb-1">
                    <input id="color-${p.id}-${i}" type="radio" name="color-${p.id}" value="${colorCode}" ${i === 0 ? 'checked' : ''}>
                    <label for="color-${p.id}-${i}" class="color-label" style="${labelStyle}"></label>
                </div>
            `;
                                            })
                                            .join('');
                                    })();

                                    // Handle size options safely
                                    const sizes = (() => {
                                        if (!p.choice_options)
                                            return `<small class="text-muted">No size</small>`;
                                        let parsed = [];
                                        try {
                                            parsed = JSON.parse(p
                                                .choice_options);
                                        } catch {
                                            parsed = [];
                                        }
                                        if (!Array.isArray(parsed) || parsed
                                            .length === 0)
                                            return `<small class="text-muted">No size</small>`;

                                        return parsed.map(choice =>
                                            choice.options.map((opt, i) => `
                                    <div class="v-size-box">
                                        <input id="size-${p.id}-${choice.name}-${i}" type="radio" name="size-${p.id}" value="${opt}" ${i === 0 ? 'checked' : ''}>
                                        <label for="size-${p.id}-${choice.name}-${i}" class="size-label">${opt}</label>
                                    </div>
                                `).join('')
                                        ).join('');
                                    })();

                                    // Product box HTML
                                    return `
                            <div class="product-list-box border rounded p-3 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="${image}" width="60" height="60" class="rounded mr-3">
                                        <div>
                                            <h6 class="mb-1">${p.name}</h6>
                                            <small class="text-muted">${productCode}</small><br>
                                            <span class="fw-bold text-success">৳ ${price}</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary add-product-btn"
                                        data-id="${p.id}"
                                        data-pid="${p.id}"
                                        data-name="${p.name}"
                                        data-pcode="${productCode}"
                                        data-price="${price}"
                                        data-image="${image}">
                                        Add Product
                                    </button>
                                </div>

                                <div class="mt-2">
                                    <strong class="d-block mb-2">Color:</strong>
                                    <div class="d-flex flex-wrap gap-1">${colors}</div>
                                </div>

                                <div class="mt-2">
                                    <strong class="d-block mb-2">Size:</strong>
                                    <div class="d-flex flex-wrap gap-1">${sizes}</div>
                                </div>
                            </div>
                        `;
                                }).join('');
                            } else {
                                productResults.innerHTML = `
                        <div class="list-group-item text-muted">
                            No products found
                        </div>`;
                            }
                            productResults.style.display = 'block';
                        });
                }, 400);
            });

            // ======== ADD PRODUCT TO TABLE ========
            productResults.addEventListener('click', function(e) {
                const btn = e.target.closest('.add-product-btn');
                if (!btn) return;

                const id = btn.dataset.id;
                const pid = btn.dataset.pid;
                const name = btn.dataset.name;
                const pcode = btn.dataset.pcode;
                const price = parseFloat(btn.dataset.price).toFixed(2);
                const image = btn.dataset.image;

                // get selected color and size
                const color = document.querySelector(`input[name="color-${id}"]:checked`)?.value || '-';
                const size = document.querySelector(`input[name="size-${id}"]:checked`)?.value || '-';

                // prevent duplicate (same product + color + size)
                const duplicate = [...document.querySelectorAll('#productRows tr')].some(tr =>
                    tr.dataset.id == id &&
                    tr.dataset.color == color &&
                    tr.dataset.size == size

                );
                if (duplicate) {
                    alert('This product with same color and size already added!');
                    return;
                }

                const row = `
        <tr data-id="${id}" data-color="${color}" data-size="${size}">
            <td><img src="${image}" width="60" class="rounded"></td>
            <td>${name}</td>
            <td>${pcode}</td>
            <td>Color: ${color} | Size: ${size}</td>
            <td><input type="number" readonly class="form-control price text-end" value="${price}" step="0.01"></td>
            <td><input type="number" class="form-control qty text-end" name="qty[]" value="1" min="1" step="1"></td>
            <td class="subtotal text-end">${price}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-danger removeRow"><i class="tio-delete"></i></button>
            </td>
             <td hidden>
        <input type="hidden" name="product_id[]" value="${pid}">
        <input type="hidden" name="product_price[]" value="${price}">
        <input type="hidden" name="variant[]" value="${size}">
        <input type="hidden" name="variation[]" value="${color}">
    </td>
        </tr>
    `;
                tableBody.insertAdjacentHTML('beforeend', row);
                productResults.style.display = 'none';
                productSearch.value = '';
                calculateTotals();
            });

            // ======== TABLE EVENTS ========
            tableBody.addEventListener('input', function(e) {
                if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
                    calculateTotals();
                }
            });

            tableBody.addEventListener('click', function(e) {
                if (e.target.closest('.removeRow')) {
                    e.target.closest('tr').remove();
                    calculateTotals();
                }
            });

            // ======== OTHER INPUT EVENTS ========
            discountField.addEventListener('input', calculateTotals);
            discountTypeField.addEventListener('change', calculateTotals);
            deliveryField.addEventListener('input', calculateTotals);

            // ======== INITIAL CALCULATION ========
            calculateTotals();
        });
    </script>
    <script>
        //make a ajax request to update ther order
        $('#editOrderForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Handle success
                },
                error: function(xhr) {
                    // Handle error
                }
            });
        });
    </script>
@endpush
