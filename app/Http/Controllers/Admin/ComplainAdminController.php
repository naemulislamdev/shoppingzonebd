<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\CPU\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use App\Model\Complaint;
use Illuminate\Http\Request;

class ComplainAdminController extends Controller
{

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $complains = Complaint::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('mobile_number', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $complains = new Complaint();
        }
        $complains = $complains->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.complain.list', compact('complains', 'search'));
    }

    public function view($id)
    {
        $contact = Complaint::findOrFail($id);
        return view('admin-views.complain.view', compact('contact'));
    }

    public function delete(Request $request)
    {
        $lead = Complaint::find($request->id);
        $lead->delete();

        return response()->json();
    }
}
