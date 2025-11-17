<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Contact;
use App\Models\Lead;
use App\Models\UserInfo;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $leads = Lead::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $leads = new Lead();
        }
        $leads = $leads->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.leads.list', compact('leads', 'search'));
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
    public function bulk_export_data()
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
                'division' => $item->division ,
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
        // $query_param = [];
        // $search = $request['search'];
        // if ($request->has('search')) {
        //     $key = explode(' ', $request['search']);
        //     $userInfos = UserInfo::where(function ($q) use ($key) {
        //         foreach ($key as $value) {
        //             $q->orWhere('name', 'like', "%{$value}%")
        //                 ->orWhere('phone', 'like', "%{$value}%");
        //         }
        //     });
        //     $query_param = ['search' => $request['search']];
        // } else {
        //     $userInfos = new UserInfo();
        // }
        $userInfos =  UserInfo::latest()->get();
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
}
