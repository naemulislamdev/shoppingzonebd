<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Exports\DynamicExport;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Color;
use App\Model\Contact;
use App\Model\Product;
use App\Models\Investor;
use App\Models\Lead;
use App\Models\UserInfo;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

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
            'subject.required' => 'Subject is Empty!',
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
    public function leadsStatus(Request $request)
    {

        $lead = Lead::find($request->id);
        $lead->lead_status = $request->lead_status;
        $lead->status_note = $request->status_note;
        $lead->save();

        return response()->json([
            'status' => true,
            'id' => $lead->id,
            'note' => $lead->status_note
        ]);
    }

    public function leadView(Request $request)
    {
        $item = Lead::findOrFail($request->id);

        // status update
        if ($item->status !== 1) {
            $item->status = 1;
            $item->save();
        }

        return response()->json([
            'status' => $item->status,
        ]);
    }
    public function updateLeadRemark(Request $request)
    {
        $lead = Lead::find($request->id);
        $lead->remark = $request->remark;
        $lead->save();

        return response()->json([
            'status' => true,
            'id' => $lead->id,
            'remark' => $lead->remark
        ]);
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
        $headings = ['Date', 'Name', 'Phone', 'Address', 'Division', 'District', 'Upazila', 'Showroom Size', 'Showroom Location', 'Status'];
        return Excel::download(new DynamicExport($headings, $data), 'leads_info.xlsx');
    }


    //--- User Information Management start---//
    public function reloadUserInfo()
    {
        return view('admin-views.user-info.partial.userinfo_table');
    }

    public function userInfoList(Request $request)
    {
        if ($request->ajax()) {
            // Date filter
            $query = UserInfo::query()
                ->select([
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'status',
                    'seen_by',
                    'type',
                    'order_process',
                    'product_details',
                    'order_status',
                    'order_note',
                    'created_at',
                    'updated_at'
                ]);

            // ✅ Date wise filter
            if ($request->filled('from') && $request->filled('to')) {
                $query->whereBetween('created_at', [
                    $request->from . ' 00:00:00',
                    $request->to   . ' 23:59:59'
                ]);
            }

            return DataTables::of($query)
                // add column
                ->addColumn('action', function ($row) {
                    return '
                             <button class="btn btn-sm btn-info viewBtn mb-2 visiable_' . $row->id . '"  data-id="' . $row->id . '">
                                <i class="tio-visible"></i>
                            </button>

                            <button type="button" id="' . $row->id . '" class="btn btn-sm btn-danger delete">
                                <i class="tio-delete"></i>
                            </button>
                        ';
                })

                ->editColumn('order_note', function ($row) {
                    return '
                        <span class="note_' . $row->id . '">' . ($row->order_note ?? 'N/A') . '</span>
                   ';
                })


                // Edit Column
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '
            <span class="badge badge-success status_' . $row->id . '">Seen</span>
            <div><small>Seen by: <br/>' . $row->seen_by . '</small></div>
        ';
                    } else {
                        return '<span class="badge badge-primary status_' . $row->id . '">Unseen</span>';
                    }
                })

                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)
                        ->format('d F Y g.i A');
                })
                ->editColumn('product_details', function ($row) {

                    $html = '';
                    $product_details = json_decode($row->product_details, true);

                    if (!is_array($product_details) || count($product_details) === 0) {
                        return 'No product details available';
                    }

                    // CASE 1: Multiple products
                    if (isset($product_details[0]) && is_array($product_details[0])) {

                        foreach ($product_details as $item) {

                            $product = Product::find($item['id'] ?? null);

                            // Variation detect
                            $variationText = null;

                            if (!empty($item['variant'])) {
                                $variationText = $item['variant'];
                            } elseif (!empty($item['variations']) && is_array($item['variations'])) {
                                $parts = [];
                                foreach ($item['variations'] as $key => $value) {
                                    $parts[] = ucfirst($key) . ': ' . ucfirst($value);
                                }
                                $variationText = implode(', ', $parts);
                            }

                            $html .= '<div class="mb-2">';
                            $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                            if ($variationText) {
                                $html .= '<strong>Variation:</strong> ' . $variationText . '<br>';
                            }

                            $html .= '</div>';
                        }
                    }
                    // CASE 2: Single product
                    else {

                        $product = Product::find($product_details['product_id'] ?? null);

                        $color_name = null;
                        if (!empty($product_details['color'])) {
                            $color_name = Color::where('code', $product_details['color'])->value('name');
                        }

                        $html .= '<div class="mb-2">';
                        $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                        if ($color_name) {
                            $html .= '<strong>Color:</strong> ' . $color_name . '<br>';
                        }

                        if (!empty($product_details['choice_8'])) {
                            $html .= '<strong>Size:</strong> ' . $product_details['choice_8'] . '<br>';
                        }

                        $html .= '</div>';
                    }

                    return $html;
                })
                ->editColumn('order_process', function ($row) {

                    if ($row->order_process === 'pending') {
                        return '<span class="badge badge-danger">Pending</span>';
                    }

                    if ($row->order_process === 'completed') {
                        return '<span class="badge badge-success">Confirmed</span>';
                    }

                    // fallback (just in case)
                    return '<span class="badge badge-secondary">' . ucfirst($row->order_process) . '</span>';
                })
                ->editColumn('order_status', function ($row) {
                    $html = '<div class="dropdown w-100 d-block">';
                    $html .= '<select name="order_status" onchange="order_status(this.value, ' . $row->id . ')"
                class="status form-control status_select_' . $row->id . '"
                data-id="' . $row->id . '">';

                    $statuses = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'canceled' => 'Canceled'];

                    foreach ($statuses as $key => $label) {
                        $selected = $row->order_status == $key ? 'selected' : '';
                        $html .= '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
                    }

                    $html .= '</select></div>';

                    return $html;
                })



                ->rawColumns(['product_details', 'status', 'order_process', 'order_status', 'action', 'order_note']) // ⭐ IMPORTANT
                ->addIndexColumn()

                ->make(true);
        }

        return view('admin-views.user-info.list_old');
    }
    public function userInfoView(Request $request)
    {
        $item = UserInfo::findOrFail($request->id);
        // status update
        if ($item->status === 0) {
            $item->update([
                'status'  => 1,
                'seen_by' => auth('admin')->user()->name,
            ]);
        }
        if ($item->seen_by == null) {
            $data = [
                'seen_by' => $item->seen_by
            ];
        }
        // return view content for modal
        $html = view('admin-views.user-info.partial.modal_view', compact('item'))->render();

        $data = [
            'status' => $item->status,
            'html' => $html,
        ];
        return response()->json($data);
    }
    public function userInfoDestroy(Request $request)
    {
        $lead = UserInfo::find($request->id);
        $lead->delete();

        return response()->json();
    }

    public function bulk_export_dataUserInfo(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;
        $query = UserInfo::query();

        // Date filter
        if (!empty($from) && !empty($to)) {
            $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        }

        $userInfos = $query->latest()->get();

        // Export data
        $data = [];
        foreach ($userInfos as $item) {
            $data[] = [
                'Date'          => Carbon::parse($item->created_at)->format('d F Y'), // 12 January 2026
                'Time'          => Carbon::parse($item->created_at)->format('h:i A'),

                'Name'          => $item->name ?? '',
                'Email'         => $item->email ?? '',
                'Phone'         => $item->phone ?? '',
                'Address'       => $item->address ?? '',

                'Type'          => ucfirst($item->type ?? ''),

                'Status'        => $item->status == 0 ? 'Unseen' : 'Seen',

                'Order Process' => $item->order_process == 'pending'
                    ? 'Pending'
                    : ($item->order_process == 'completed' ? 'Confirmed' : ucfirst($item->order_process)),

                'Order Status'  => ucfirst($item->order_status ?? ''),

                'Order Note'    => $item->order_note ?? '',
            ];
        }

        $headings = ['Date', 'Time', 'Name', 'Email', 'Phone', 'Address', 'Type', 'Status', 'Order Process', 'Order Status', 'Order Note'];

        return Excel::download(new DynamicExport($headings, $data), 'user_info.xlsx');
    }

    public function status(Request $request)
    {
        $userinfo = UserInfo::findOrFail($request->id);

        $userinfo->order_status = $request->order_status;
        $userinfo->order_note   = $request->note;

        if ($request->order_status === 'confirmed') {

            $userinfo->confirmed_by = json_encode([
                'user' => auth('admin')->user()->name,
                'time' => now()->format('d M Y h:i A')
            ]);
        } elseif ($request->order_status === 'canceled') {

            $userinfo->canceled_by = json_encode([
                'user' => auth('admin')->user()->name,
                'time' => now()->format('d M Y h:i A')
            ]);
        }


        $userinfo->save();

        return response()->json([
            'status'       => true,
            'id'           => $userinfo->id,
            'order_status' => $userinfo->order_status,
            'note'         => $userinfo->order_note,
            'data'         => $request->order_status === 'confirmed'
                ? json_decode($userinfo->confirmed_by)
                : ($request->order_status === 'canceled'
                    ? json_decode($userinfo->canceled_by)
                    : null)
        ]);
    }

    public function userinfoPendingList(Request $request)
    {
        if ($request->ajax()) {
            // Date filter

            $query = UserInfo::query()

                ->select([
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'status',
                    'seen_by',
                    'type',
                    'order_process',
                    'product_details',
                    'order_status',
                    'order_note',
                    'created_at',
                    'updated_at'
                ]);

            // Filter by order_status canceled

            $query->where('order_status', 'pending')
                ->orderBy('id', 'desc');


            // ✅ Date wise filter
            if ($request->filled('from') && $request->filled('to')) {
                $query->whereBetween('created_at', [
                    $request->from . ' 00:00:00',
                    $request->to   . ' 23:59:59'
                ]);
            }

            return DataTables::of($query)
                // add column
                ->addColumn('action', function ($row) {
                    return '
                            <button class="btn btn-sm btn-info viewBtn mb-2 visiable_' . $row->id . '"  data-id="' . $row->id . '">
                                <i class="tio-visible"></i>
                            </button>

                            <button type="button" id="' . $row->id . '" class="btn btn-sm btn-danger delete">
                                <i class="tio-delete"></i>
                            </button>
                        ';
                })



                ->editColumn('order_note', function ($row) {
                    return '
                        <div>
                            <span class="note_' . $row->id . '">' . ($row->order_note ?? 'N/A') . '</span>
                        </div>
                    ';
                })


                // Edit Column
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '
            <span class="badge badge-success status_' . $row->id . '">Seen</span>
            <div><small>Seen by: <br/>' . $row->seen_by . '</small></div>
        ';
                    } else {
                        return '<span class="badge badge-primary status_' . $row->id . '">Unseen</span>';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)
                        ->format('d F Y g.i A');
                })
                ->editColumn('product_details', function ($row) {

                    $html = '';
                    $product_details = json_decode($row->product_details, true);

                    if (!is_array($product_details) || count($product_details) === 0) {
                        return 'No product details available';
                    }

                    // CASE 1: Multiple products
                    if (isset($product_details[0]) && is_array($product_details[0])) {

                        foreach ($product_details as $item) {

                            $product = Product::find($item['id'] ?? null);

                            // Variation detect
                            $variationText = null;

                            if (!empty($item['variant'])) {
                                $variationText = $item['variant'];
                            } elseif (!empty($item['variations']) && is_array($item['variations'])) {
                                $parts = [];
                                foreach ($item['variations'] as $key => $value) {
                                    $parts[] = ucfirst($key) . ': ' . ucfirst($value);
                                }
                                $variationText = implode(', ', $parts);
                            }

                            $html .= '<div class="mb-2">';
                            $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                            if ($variationText) {
                                $html .= '<strong>Variation:</strong> ' . $variationText . '<br>';
                            }

                            $html .= '</div>';
                        }
                    }
                    // CASE 2: Single product
                    else {

                        $product = Product::find($product_details['product_id'] ?? null);

                        $color_name = null;
                        if (!empty($product_details['color'])) {
                            $color_name = Color::where('code', $product_details['color'])->value('name');
                        }

                        $html .= '<div class="mb-2">';
                        $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                        if ($color_name) {
                            $html .= '<strong>Color:</strong> ' . $color_name . '<br>';
                        }

                        if (!empty($product_details['choice_8'])) {
                            $html .= '<strong>Size:</strong> ' . $product_details['choice_8'] . '<br>';
                        }

                        $html .= '</div>';
                    }

                    return $html;
                })
                ->editColumn('order_process', function ($row) {

                    if ($row->order_process === 'pending') {
                        return '<span class="badge badge-danger">Pending</span>';
                    }

                    if ($row->order_process === 'completed') {
                        return '<span class="badge badge-success">Confirmed</span>';
                    }

                    // fallback (just in case)
                    return '<span class="badge badge-secondary">' . ucfirst($row->order_process) . '</span>';
                })
                ->editColumn('order_status', function ($row) {
                    $html = '<div class="dropdown w-100 d-block">';
                    $html .= '<select name="order_status" onchange="order_status(this.value, ' . $row->id . ')"
                class="status form-control status_select_' . $row->id . '"
                data-id="' . $row->id . '">';

                    $statuses = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'canceled' => 'Canceled'];

                    foreach ($statuses as $key => $label) {
                        $selected = $row->order_status == $key ? 'selected' : '';
                        $html .= '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
                    }

                    $html .= '</select></div>';

                    return $html;
                })



                ->rawColumns(['product_details', 'status', 'order_process', 'order_status', 'action', 'order_note']) // ⭐ IMPORTANT
                ->addIndexColumn()

                ->make(true);
        }

        return view('admin-views.user-info.pending_list');
    }
    public function userinfoConfirmedList(Request $request)
    {
        if ($request->ajax()) {
            // Date filter

            $query = UserInfo::query()

                ->select([
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'status',
                    'seen_by',
                    'type',
                    'order_process',
                    'product_details',
                    'order_status',
                    'order_note',
                    'created_at',
                    'updated_at'
                ]);

            // Filter by order_status canceled

            $query->where('order_status', 'confirmed')
                ->orderBy('id', 'desc');

            // ✅ Date wise filter
            if ($request->filled('from') && $request->filled('to')) {
                $query->whereBetween('created_at', [
                    $request->from . ' 00:00:00',
                    $request->to   . ' 23:59:59'
                ]);
            }

            return DataTables::of($query)
                // add column
                ->addColumn('action', function ($row) {
                    return '
                            <button class="btn btn-sm btn-info viewBtn mb-2 visiable_' . $row->id . '"  data-id="' . $row->id . '">
                                <i class="tio-visible"></i>
                            </button>

                            <button type="button" id="' . $row->id . '" class="btn btn-sm btn-danger delete">
                                <i class="tio-delete"></i>
                            </button>
                        ';
                })



                ->editColumn('order_note', function ($row) {
                    return '<span class="note_' . $row->id . '">' . ($row->order_note ?? 'N/A') . '</span>';
                })


                // Edit Column
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '
            <span class="badge badge-success status_' . $row->id . '">Seen</span>
            <div><small>Seen by: <br/>' . $row->seen_by . '</small></div>
        ';
                    } else {
                        return '<span class="badge badge-primary status_' . $row->id . '">Unseen</span>';
                    }
                })

                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)
                        ->format('d F Y g.i A');
                })
                ->editColumn('product_details', function ($row) {

                    $html = '';
                    $product_details = json_decode($row->product_details, true);

                    if (!is_array($product_details) || count($product_details) === 0) {
                        return 'No product details available';
                    }

                    // CASE 1: Multiple products
                    if (isset($product_details[0]) && is_array($product_details[0])) {

                        foreach ($product_details as $item) {

                            $product = Product::find($item['id'] ?? null);

                            // Variation detect
                            $variationText = null;

                            if (!empty($item['variant'])) {
                                $variationText = $item['variant'];
                            } elseif (!empty($item['variations']) && is_array($item['variations'])) {
                                $parts = [];
                                foreach ($item['variations'] as $key => $value) {
                                    $parts[] = ucfirst($key) . ': ' . ucfirst($value);
                                }
                                $variationText = implode(', ', $parts);
                            }

                            $html .= '<div class="mb-2">';
                            $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                            if ($variationText) {
                                $html .= '<strong>Variation:</strong> ' . $variationText . '<br>';
                            }

                            $html .= '</div>';
                        }
                    }
                    // CASE 2: Single product
                    else {

                        $product = Product::find($product_details['product_id'] ?? null);

                        $color_name = null;
                        if (!empty($product_details['color'])) {
                            $color_name = Color::where('code', $product_details['color'])->value('name');
                        }

                        $html .= '<div class="mb-2">';
                        $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                        if ($color_name) {
                            $html .= '<strong>Color:</strong> ' . $color_name . '<br>';
                        }

                        if (!empty($product_details['choice_8'])) {
                            $html .= '<strong>Size:</strong> ' . $product_details['choice_8'] . '<br>';
                        }

                        $html .= '</div>';
                    }

                    return $html;
                })
                ->editColumn('order_process', function ($row) {

                    if ($row->order_process === 'pending') {
                        return '<span class="badge badge-danger">Pending</span>';
                    }

                    if ($row->order_process === 'completed') {
                        return '<span class="badge badge-success">Confirmed</span>';
                    }

                    // fallback (just in case)
                    return '<span class="badge badge-secondary">' . ucfirst($row->order_process) . '</span>';
                })
                ->editColumn('order_status', function ($row) {
                    $html = '<div class="dropdown w-100 d-block">';
                    $html .= '<select name="order_status" onchange="order_status(this.value, ' . $row->id . ')"
                class="status form-control status_select_' . $row->id . '"
                data-id="' . $row->id . '">';

                    $statuses = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'canceled' => 'Canceled'];

                    foreach ($statuses as $key => $label) {
                        $selected = $row->order_status == $key ? 'selected' : '';
                        $html .= '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
                    }

                    $html .= '</select></div>';

                    return $html;
                })



                ->rawColumns(['product_details', 'status', 'order_process', 'order_status', 'action', 'order_note']) // ⭐ IMPORTANT
                ->addIndexColumn()

                ->make(true);
        }

        return view('admin-views.user-info.confirmed_list');
    }
    public function userinfoCanceledList(Request $request)
    {
        if ($request->ajax()) {
            // Date filter

            $query = UserInfo::query()

                ->select([
                    'id',
                    'name',
                    'email',
                    'phone',
                    'address',
                    'status',
                    'seen_by',
                    'type',
                    'order_process',
                    'product_details',
                    'order_status',
                    'order_note',
                    'created_at',
                    'updated_at'
                ]);

            // Filter by order_status canceled

            $query->where('order_status', 'canceled')
                ->orderBy('id', 'desc');

            // ✅ Date wise filter
            if ($request->filled('from') && $request->filled('to')) {
                $query->whereBetween('created_at', [
                    $request->from . ' 00:00:00',
                    $request->to   . ' 23:59:59'
                ]);
            }

            return DataTables::of($query)
                // add column
                ->addColumn('action', function ($row) {
                    return '
                            <button class="btn btn-sm btn-info viewBtn mb-2 visiable_' . $row->id . '"  data-id="' . $row->id . '">
                                <i class="tio-visible"></i>
                            </button>

                            <button type="button" id="' . $row->id . '" class="btn btn-sm btn-danger delete">
                                <i class="tio-delete"></i>
                            </button>
                        ';
                })



                ->editColumn('order_note', function ($row) {
                    return '<span class="note_' . $row->id . '">' . ($row->order_note ?? 'N/A') . '</span>';
                })


                // Edit Column
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '
            <span class="badge badge-success status_' . $row->id . '">Seen</span>
            <div><small>Seen by: <br/>' . $row->seen_by . '</small></div>
        ';
                    } else {
                        return '<span class="badge badge-primary status_' . $row->id . '">Unseen</span>';
                    }
                })

                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)
                        ->format('d F Y g.i A');
                })
                ->editColumn('product_details', function ($row) {

                    $html = '';
                    $product_details = json_decode($row->product_details, true);

                    if (!is_array($product_details) || count($product_details) === 0) {
                        return 'No product details available';
                    }

                    // CASE 1: Multiple products
                    if (isset($product_details[0]) && is_array($product_details[0])) {

                        foreach ($product_details as $item) {

                            $product = Product::find($item['id'] ?? null);

                            // Variation detect
                            $variationText = null;

                            if (!empty($item['variant'])) {
                                $variationText = $item['variant'];
                            } elseif (!empty($item['variations']) && is_array($item['variations'])) {
                                $parts = [];
                                foreach ($item['variations'] as $key => $value) {
                                    $parts[] = ucfirst($key) . ': ' . ucfirst($value);
                                }
                                $variationText = implode(', ', $parts);
                            }

                            $html .= '<div class="mb-2">';
                            $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                            if ($variationText) {
                                $html .= '<strong>Variation:</strong> ' . $variationText . '<br>';
                            }

                            $html .= '</div>';
                        }
                    }
                    // CASE 2: Single product
                    else {

                        $product = Product::find($product_details['product_id'] ?? null);

                        $color_name = null;
                        if (!empty($product_details['color'])) {
                            $color_name = Color::where('code', $product_details['color'])->value('name');
                        }

                        $html .= '<div class="mb-2">';
                        $html .= '<strong>Product Code:</strong> ' . ($product->code ?? 'N/A') . '<br>';

                        if ($color_name) {
                            $html .= '<strong>Color:</strong> ' . $color_name . '<br>';
                        }

                        if (!empty($product_details['choice_8'])) {
                            $html .= '<strong>Size:</strong> ' . $product_details['choice_8'] . '<br>';
                        }

                        $html .= '</div>';
                    }

                    return $html;
                })
                ->editColumn('order_process', function ($row) {

                    if ($row->order_process === 'pending') {
                        return '<span class="badge badge-danger">Pending</span>';
                    }

                    if ($row->order_process === 'completed') {
                        return '<span class="badge badge-success">Confirmed</span>';
                    }

                    // fallback (just in case)
                    return '<span class="badge badge-secondary">' . ucfirst($row->order_process) . '</span>';
                })
                ->editColumn('order_status', function ($row) {
                    $html = '<div class="dropdown w-100 d-block">';
                    $html .= '<select name="order_status" onchange="order_status(this.value, ' . $row->id . ')"
                class="status form-control status_select_' . $row->id . '"
                data-id="' . $row->id . '">';

                    $statuses = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'canceled' => 'Canceled'];

                    foreach ($statuses as $key => $label) {
                        $selected = $row->order_status == $key ? 'selected' : '';
                        $html .= '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
                    }

                    $html .= '</select></div>';

                    return $html;
                })



                ->rawColumns(['product_details', 'status', 'order_process', 'order_status', 'action', 'order_note']) // ⭐ IMPORTANT
                ->addIndexColumn()

                ->make(true);
        }

        return view('admin-views.user-info.canceled_list');
    }
    public function multipleUserInfoNote(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'multiple_note' => 'required|array'
        ]);

        $userinfo = UserInfo::findOrFail($request->id);

        // existing notes
        $existingNotes = json_decode($userinfo->multiple_note, true) ?? [];

        // new notes with date & time
        $newNotes = [];

        foreach ($request->multiple_note as $note) {
            $newNotes[] = [
                'note' => $note,
                'time' => now()->format('d M Y h:i A'),
                'user' => auth('admin')->user()->name
            ];
        }

        // merge old + new
        $mergedNotes = array_merge($existingNotes, $newNotes);

        // save as json
        $userinfo->multiple_note = json_encode($mergedNotes);
        $userinfo->save();

        return response()->json([
            'status' => true,
            'note' => end($newNotes) // last added note frontend এ পাঠানো
        ]);
    }


    //--- User Information Management end---//

    //--- Investment Management ---//
    public function investorsList(Request $request)
    {
        $investors =  Investor::latest()->get();
        return view('admin-views.investors.list', compact('investors'));
    }

    public function investorsViewStatus(Request $request)
    {
        $item = Investor::findOrFail($request->id);

        // status update
        if ($item->status !== 1) {
            $item->status = 1;
            $item->save();
        }

        return response()->json([
            'status' => $item->status,
        ]);
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
        $data = [];
        foreach ($investors as $item) {
            $data[] = [
                'Date' => Carbon::parse($item->created_at)->format('d M Y'),
                'name' => $item->name,
                'phone' => $item->mobile_number,
                'address' => $item->address,
                'occupation' => $item->occupation,
                'investment_amount' => $item->investment_amount,
            ];
        }
        $headings = ['Date', 'Name', 'Phone', 'Address', 'Occupation', 'Investment Amount'];

        return Excel::download(new DynamicExport($headings, $data), 'investors_info.xlsx');
    }
    public function updateInvestorRemark(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'id' => 'required|exists:investors,id',
            'remark' => 'required|string'
        ]);

        $investor = Investor::find($request->id);
        $investor->remark = $request->remark;
        $investor->save();

        return response()->json([
            'status' => true,
            'id' => $investor->id,
            'remark' => $investor->remark
        ]);
    }
}
