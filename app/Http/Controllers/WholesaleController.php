<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\Models\Wholesale;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Request;

class WholesaleController extends Controller
{
    public function create()
    {
        return view("web-views.wholesale.wholesale");
    }
    public function store(Request $request)
    {


        $request->validate([
            'name'              => 'required|string|max:255|min:2',
            'phone'     => 'required|string|max:11',
            'address'           => 'nullable|string',
            'occupation'        => 'nullable|string|max:255',
            'product_quantity'  => 'nullable|numeric|min:1',
        ]);
        Wholesale::create([
            'name'              => $request->name,
            'phone'             => $request->phone,
            'address'           => $request->address,
            'occupation'        => $request->occupation,
            'product_quantity'  => $request->product_quantity,
        ]);

        return redirect()->back()->with('success', 'Wholesale Info Submit successfully!');
    }
    public function wholesaleList(Request $request)  {
        $search = $request->search;
        $from   = $request->from;
        $to     = $request->to;

        // base query
        $wholesaleList = Wholesale::query();

        // search
        if (!empty($search)) {
            $keywords = explode(' ', $search);

            $wholesaleList->where(function ($q) use ($keywords) {
                foreach ($keywords as $value) {
                    $q->orWhere('created_at', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('address', 'like', "%{$value}%")
                        ->orWhere('occupation', 'like', "%{$value}%")
                        ->orWhere('product_quantity', 'like', "%{$value}%")
                        ->orWhere('status', 'like', "%{$value}%");
                    if ($value === 'seen') {
                        $q->orWhere('status', 0);
                    }

                    if ($value === 'unseen') {
                        $q->orWhere('status', 1);
                    }
                }
            });
        }


        // date filter
        if (!empty($from) && !empty($to)) {
            $wholesaleList->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        }


        $wholesaleList = $wholesaleList->latest()
            ->paginate(20)
            ->appends([
                'search' => $search,
                'from'   => $from,
                'to'     => $to
            ]);
        return view('admin-views.wholesale.list', compact('wholesaleList', 'search'));
    }
    public function wholesaleView($id)
    {
        $wholesale = Wholesale::findOrFail($id);
        $wholesale->update(['status' => 0]);
        return view('admin-views.wholesale.view', compact('wholesale'));
    }
    public function wholesaleDestroy(Request $request) {
        $wlist = Wholesale::find($request->id);
        $wlist->delete();

        return response()->json();
    }
    public function bulk_export_data()
    {
        $leads = Wholesale::latest()->get();
        //export from leads
        $data = [];
        foreach ($leads as $item) {
            $data[] = [
                'Date' => Carbon::parse($item->created_at)->format('d M Y'),
                'name' => $item->name,
                'phone' => $item->phone,
                'address' => $item->address,
                'occupation' => $item->occupation,
                'product_quantity' => $item->product_quantity,
                'status' => $item->status == 1 ? 'Unseen' : 'Seen',

            ];
        }
        return (new FastExcel($data))->download('wholesale_info.xlsx');
    }
}
