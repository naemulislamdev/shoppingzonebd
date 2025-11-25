<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Contact;
use App\Models\Investor;
use App\Models\Lead;
use App\Models\UserInfo;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'mobile_number.required' => 'Mobile Number is Empty!',
            'subject.required' => ' Subject is Empty!',
            'message.required' => 'Message is Empty!',

        ]);
        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_number = $request->mobile_number;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        return response()->json(['success' => 'Your Message Send Successfully']);
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $contacts = Contact::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('mobile_number', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $contacts = new Contact();
        }
        $contacts = $contacts->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.contacts.list', compact('contacts', 'search'));
    }

    public function view($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin-views.contacts.view', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        $contact->feedback = $request->feedback;
        $contact->seen = 1;
        $contact->update();
        Toastr::success('Feedback  Update successfully!');
        return redirect()->route('admin.contact.list');
    }

    public function destroy(Request $request)
    {
        $contact = Contact::find($request->id);
        $contact->delete();

        return response()->json();
    }

    public function send_mail(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $data = array('body' => $request['mail_body']);
        Mail::send('email-templates.customer-message', $data, function ($message) use ($contact, $request) {
            $message->to($contact['email'], BusinessSetting::where(['type' => 'company_name'])->first()->value)
                ->subject($request['subject']);
        });

        Contact::where(['id' => $id])->update([
            'reply' => json_encode([
                'subject' => $request['subject'],
                'body' => $request['mail_body']
            ])
        ]);

        Toastr::success('Mail sent successfully!');
        return back();
    }

    //---Leads Management ---//
    public function leadsList(Request $request)
    {
        $search = $request->search;
        $from   = $request->from;
        $to     = $request->to;

        // base query
        $leads = Lead::query();

        // search
        if (!empty($search)) {
            $keywords = explode(' ', $search);

            $leads->where(function ($q) use ($keywords) {

                foreach ($keywords as $value) {
                    $q->orWhere('created_at', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('address', 'like', "%{$value}%")
                        ->orWhere('upazila', 'like', "%{$value}%")
                        ->orWhere('district', 'like', "%{$value}%")
                        ->orWhere('division', 'like', "%{$value}%")
                        ->orWhere('showroom_size', 'like', "%{$value}%")
                        ->orWhere('showroom_location', 'like', "%{$value}%");
                    if ($value === 'seen') {
                        $q->orWhere('status', 1);
                    }

                    if ($value === 'unseen') {
                        $q->orWhere('status', 0);
                    }

                }
            });
        }


        // date filter
        if (!empty($from) && !empty($to)) {
            $leads->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        }


        $leads = $leads->latest()
            ->paginate(20)
            ->appends([
                'search' => $search,
                'from'   => $from,
                'to'     => $to
            ]);
        return view('admin-views.leads.list', compact('leads'));
    }

    public function leadView($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->update(['status' => 0]);
        return view('admin-views.leads.view', compact('lead'));
    }
    public function leadDestroy(Request $request)
    {
        $lead = Lead::find($request->id);
        $lead->delete();

        return response()->json();
    }
    public function bulk_export_LeadsData()
    {
        $leads = Lead::latest()->get();
        //export from leads
        $data = [];
        foreach ($leads as $item) {
            $data[] = [
                'Date' => Carbon::parse($item->created_at)->format('d M Y'),
                'name' => $item->name,
                'phone' => $item->phone,
                'address' => $item->address,
                'division' => $item->division,
                'district' => $item->district,
                'upazila' => $item->upazila,
                'Showroom Size' => $item->showroom_size,
                'Showroom Location' => $item->showroom_location,
                'status' => $item->status == 1 ? 'Unseen' : 'Seen',

            ];
        }
        return (new FastExcel($data))->download('leads_info.xlsx');
    }

    //--- User Information Management ---//
    public function userInfoList(Request $request)
    {
        $search = $request->search;
        $from   = $request->from;
        $to     = $request->to;

        // base query
        $userInfos = UserInfo::query();

        // search
        if (!empty($search)) {
            $keywords = explode(' ', $search);

            $userInfos->where(function ($q) use ($keywords) {

                foreach ($keywords as $value) {

                    // normal fields
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('address', 'like', "%{$value}%")
                        ->orWhere('type', 'like', "%{$value}%")
                        ->orWhere('product_details', 'like', "%{$value}%")
                        ->orWhere('order_note', 'like', "%{$value}%")
                        ->orWhere('order_process', 'like', "%{$value}%");
                    // order_status keyword
                    if ($value === 'confirm' || $value === 'cancel') {
                        $q->orWhere('order_status', $value);
                    } else {
                        $q->orWhere('order_status', 'like', "%{$value}%");
                    }

                    // seen/unseen
                    if ($value === 'seen' || $value === 'unseen') {
                        $q->orWhere('status', $value === 'seen' ? 1 : 0);
                    }
                }
            });
        }


        // date filter
        if (!empty($from) && !empty($to)) {
            $userInfos->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        }


        $userInfos = $userInfos->latest()
            ->paginate(20)
            ->appends([
                'search' => $search,
                'from'   => $from,
                'to'     => $to
            ]);

        return view('admin-views.user-info.list', compact('userInfos'));
    }


    public function userInfoView($id)
    {
        $userInfo = UserInfo::findOrFail($id);
        $userInfo->update(['status' => 1]);
        return view('admin-views.user-info.view', compact('userInfo'));
    }
    public function userInfoDestroy(Request $request)
    {
        $lead = UserInfo::find($request->id);
        $lead->delete();

        return response()->json();
    }
    public function bulk_export_dataUserInfo()
    {
        $userInfos = UserInfo::latest()->get();
        //export from userInfos
        $data = [];
        foreach ($userInfos as $item) {
            $data[] = [
                'Date' => Carbon::parse($item->created_at)->format('d M Y'),
                'name' => $item->name,
                'phone' => $item->phone,
                'address' => $item->address,
                'status' => $item->status == 0 ? 'Unseen' : 'Seen',

            ];
        }
        return (new FastExcel($data))->download('user_info.xlsx');
    }
    public function status(Request $request)
    {

        if ($request->ajax()) {
            $userinfo = UserInfo::find($request->id);
            $userinfo->order_status = $request->order_status;
            $userinfo->order_note = $request->note;
            $userinfo->save();
            $data = $request->order_status;
            return response()->json($data);
        }
    }
    //--- Investment Management ---//
    public function investorsList(Request $request)
    {
        $search = $request->search;
        $from   = $request->from;
        $to     = $request->to;

        // base query
        $investors = Investor::query();

        // search
        if (!empty($search)) {
            $keywords = explode(' ', $search);

            $investors->where(function ($q) use ($keywords) {

                foreach ($keywords as $value) {
                    $q->orWhere('created_at', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('mobile_number', 'like', "%{$value}%")
                        ->orWhere('address', 'like', "%{$value}%")
                        ->orWhere('occupation', 'like', "%{$value}%")
                        ->orWhere('investment_amount', 'like', "%{$value}%")
                        ->orWhere('remark', 'like', "%{$value}%")
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
            $investors->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        }


        $investors = $investors->latest()
            ->paginate(20)
            ->appends([
                'search' => $search,
                'from'   => $from,
                'to'     => $to
            ]);
        return view('admin-views.investors.list', compact('investors'));
    }

    public function investorsView($id)
    {
        $investor = Investor::findOrFail($id);
        $investor->update(['status' => 0]);
        return view('admin-views.investors.view', compact('investor'));
    }
    public function investorsDestroy(Request $request)
    {
        $lead = Investor::find($request->id);
        $lead->delete();

        return response()->json();
    }
    public function bulk_export_investors()
    {
        $investors = Investor::latest()->get();
        //export from userInfos
        $data = [];
        foreach ($investors as $item) {
            $data[] = [
                'Date' => Carbon::parse($item->created_at)->format('d M Y'),
                'name' => $item->name,
                'phone' => $item->mobile_number,
                'address' => $item->address,
                'occupation' => $item->occupation,
                'investment_amount' => $item->investment_amount,
                //'status' => $item->status == 0 ? 'Unseen' : 'Seen',
            ];
        }
        return (new FastExcel($data))->download('investors_info.xlsx');
    }
    public function remarkStatus(Request $request)
    {

        if ($request->ajax()) {
            $invest = Investor::find($request->id);
            $invest->remark = auth('admin')->name . ': ' . $request->remark;
            $invest->status =  0;
            $invest->save();
            $data = $request->remark;
            return response()->json($data);
        }
    }
}
