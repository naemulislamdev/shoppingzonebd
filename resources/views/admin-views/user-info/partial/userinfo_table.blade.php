<table id="datatable"
                                style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                                class="table table-hover table-borderless table-thead-bordered table-align-middle card-table"
                                style="width:100%;">
                                <thead class="thead-light">
                                   <tr>
                                        <th style="width: 2%;">SL#</th>
                                        <th style="width: 10%;">Date and Time</th>
                                        <th style="width: 10%;">Name</th>
                                        <th style="width: 10%;">Phone</th>
                                        <th style="width: 15%;">Address</th>
                                        <th style="width: 5%;">Status</th>
                                        <th style="width: 10%;">Product</th>
                                        <th style="width: 3%;">Order Process</th>
                                        <th style="width: 12%;">Order Status</th>
                                        <th style="width: 10%;">Status Note</th>
                                        <th style="width: 8%;">Type</th>
                                        <th style="width: 5%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userInfos as $k => $userInfo)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($userInfo->created_at)->format('d M Y') }}
                                                <br>
                                                {{ date('h:i A', strtotime($userInfo['created_at'])) }}
                                            </td>
                                            <td>{{ $userInfo['name'] }}</td>
                                            <td>{{ $userInfo['phone'] }}</td>
                                            <td>{{ $userInfo['address'] }}</td>
                                            <td>{{ $userInfo['status'] == 0 ? 'Unseen' : 'Seen' }}</td>
                                            <td>
                                                @php
                                                    $product_details = json_decode($userInfo->product_details, true);
                                                @endphp

                                                @if (is_array($product_details) && count($product_details) > 0)
                                                    {{-- CASE 1: multiple cart items (array of items) --}}
                                                    @if (isset($product_details[0]) && is_array($product_details[0]))
                                                        @foreach ($product_details as $item)
                                                            @php
                                                                $productId = $item['id'] ?? null;
                                                                $product = \App\Model\Product::find($productId);

                                                                // Detect variation data
                                                                $variationText = null;
                                                                if (!empty($item['variant'])) {
                                                                    $variationText = $item['variant'];
                                                                } elseif (
                                                                    !empty($item['variations']) &&
                                                                    is_array($item['variations'])
                                                                ) {
                                                                    $variationParts = [];
                                                                    foreach ($item['variations'] as $key => $value) {
                                                                        $variationParts[] =
                                                                            ucfirst($key) . ': ' . ucfirst($value);
                                                                    }
                                                                    $variationText = implode(', ', $variationParts);
                                                                }
                                                            @endphp

                                                            <div class="mb-2">
                                                                <strong>Product Code:</strong>
                                                                {{ $product->code ?? 'N/A' }}<br>

                                                                @if ($variationText)
                                                                    <strong>Variation:</strong> {{ $variationText }}<br>
                                                                @endif
                                                            </div>
                                                        @endforeach

                                                        {{-- CASE 2: single product data (object-like array) --}}
                                                    @else
                                                        @php
                                                            $productId = $product_details['product_id'] ?? null;
                                                            $product = \App\Model\Product::find($productId);
                                                            $color_name = isset($product_details['color'])
                                                                ? \App\Model\Color::where(
                                                                    'code',
                                                                    $product_details['color'],
                                                                )->value('name')
                                                                : null;
                                                        @endphp

                                                        <div class="mb-2">
                                                            <strong>Product Code:</strong>
                                                            {{ $product->code ?? 'N/A' }}<br>

                                                            @if ($color_name)
                                                                <strong>Color:</strong> {{ $color_name ?? 'N/A' }}<br>
                                                            @endif

                                                            @if (!empty($product_details['choice_8']))
                                                                <strong>Size:</strong>
                                                                {{ $product_details['choice_8'] }}<br>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <p>No product details available.</p>
                                                @endif
                                            </td>


                                            <td>
                                                @if ($userInfo['order_process'] == 'pending')
                                                    <span class="badge badge-danger">Pending</span>
                                                @elseif($userInfo['order_process'] == 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @endif
                                            </td>
                                            <td class="m-0 p-0">
                                                <div class="form-group">
                                                    <div class="hs-unfold float-right">
                                                        <div class="dropdown">
                                                            <select name="order_status"
                                                                onchange="order_status(this.value, {{ $userInfo['id'] }})"
                                                                class="status form-control"
                                                                data-id="{{ $userInfo['id'] }}">

                                                                <option value="pending"
                                                                    {{ $userInfo->order_status == 'pending' ? 'selected' : '' }}>
                                                                    {{ \App\CPU\translate('Pending') }}</option>
                                                                <option value="confirmed"
                                                                    {{ $userInfo->order_status == 'confirmed' ? 'selected' : '' }}>
                                                                    {{ \App\CPU\translate('Confirmed') }}</option>
                                                                <option value="canceled"
                                                                    {{ $userInfo->order_status == 'canceled' ? 'selected' : '' }}>
                                                                    {{ \App\CPU\translate('Canceled') }} </option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $userInfo->order_note }}
                                            </td>
                                            <td>{{ $userInfo->type }}</td>
                                            <td>

                                                <div class="d-flex justify-content-between">
                                                    <a title="{{ \App\CPU\translate('View') }}"
                                                        class="btn btn-info btn-sm mr-2 mb-2" style="cursor: pointer;"
                                                        href="{{ route('admin.user-info.view', $userInfo->id) }}">
                                                        <i class="tio-visible"></i>
                                                    </a>
                                                    @if (auth('admin')->user()->admin_role_id == 3)
                                                        <a class="btn btn-danger btn-sm delete mb-2 mr-2"
                                                            style="cursor: pointer;" id="{{ $userInfo['id'] }}"
                                                            title="{{ \App\CPU\translate('Delete') }}">
                                                            <i class="tio-delete"></i>
                                                        </a>
                                                    @endif
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
