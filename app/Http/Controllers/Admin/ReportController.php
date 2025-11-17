<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function order_index(Request $request)
    {

        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('admin-views.report.order-index');
    }

    public function earning_index(Request $request)
    {
        if (!$request->has('from_date')) {
            $from = $to = date('Y-m-01');
        } else {
            $from = $request['from_date'];
            $to = $request['to_date'];
        }
        return view('admin-views.report.earning-index', compact('from', 'to'));
    }

    public function set_date(Request $request)
    {
        $from = $request['from'];
        $to = $request['to'];

        session()->put('from_date', $from);
        session()->put('to_date', $to);

        $previousUrl = strtok(url()->previous(), '?');
        return redirect()->to($previousUrl . '?' . http_build_query(['from_date' => $request['from'], 'to_date' => $request['to']]))->with(['from' => $from, 'to' => $to]);
    }
    public function cot_store()
    {
        $orders = Order::where('order_status', 'confirmed',)->where('payment_status', 'paid')->get();
        foreach ($orders as $order) {
            \App\Model\OrderTransaction::create([
                'seller_id' => 1,
                'order_id' => $order->id,
                'order_amount' => $order->order_amount,
                'seller_amount' => 00,
                'received_by' => 'admin',
                'status' => 'disburse',
                'delivery_charge' => $order->shipping_cost,
                'payment_method' => $order->payment_method,
                'created_at' => $order->updated_at,
                'updated_at' => now(),
            ]);
        }
        return 'Order transactions created successfully';
    }
}
