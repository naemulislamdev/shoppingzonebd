<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Models\Career;
use App\Models\JobApplication;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class JobApplicationController extends Controller
{

    public function index(Request $request)
    {


        return view("admin-views.job_applications.view");
    }

    public function status(Request $request)
    {

        if ($request->ajax()) {
            $app = JobApplication::find($request->id);
            $app->status = $request->status;
            $app->save();
            $data = $request->all();
            return response()->json($data);
        }
    }

    public function delete(Request $request)
    {
        $app = JobApplication::find($request->id);

        ImageManager::delete('files/job_resume/' . $app['resume']);
        $app->delete();
        return response()->json();
    }
    public function bulk_export_applications(Request $request)
    {

        $applications = JobApplication::latest()->get();


        $data = [];

        foreach ($applications as $item) {
            $data[] = [
                'Applied Date'           => Carbon::parse($item->created_at)->format('d M Y'),
                'Name'                   => $item->name,
                'Email'                  => $item->email,
                'Phone'                  => $item->phone,
                'Position'               => $item->career->position ?? 'N/A',
                'Experience Level'       => $item->experience_level,
                'Current Job Position'   => $item->current_position,
                'Expected Salary'        => $item->expected_salary,
                'CV or Resume'           => asset('storage/files/job_resume/' . $item->resume),
                'Cover Letter'           => $item->message,
                'Portfolio'              => $item->portfolio_link,
                'Status'                 => $item->status,
            ];
        }
        // Export to Excel
        return (new FastExcel($data))->download('jobs_applications_info.xlsx');
    }

    public function datatables(Request $request, $status)
    {
        $query = JobApplication::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // ✅ Date wise filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay(),
            ]);
        }

        //--- Integrating This Collection Into Datatables
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function (JobApplication $data) {
                $date = \Carbon\Carbon::parse($data->created_at)->format('d M Y');
                $time = \Carbon\Carbon::parse($data->created_at)->format('h:i A');
                return $date . ' ' . $time;
            })




            ->editColumn('status', function (JobApplication $data) {

                if ($data->status == 'pending') {
                    $badge = 'warning';
                    $source = __('Pending');
                } elseif ($data->status == 'shortlisted') {
                    $badge = 'success';
                    $source = __('Shortlisted');
                } elseif ($data->status == 'rejected') {
                    $badge = 'danger';
                    $source = __('Rejected');
                } else {
                    $badge = 'dark';
                    $source = __('Unknown');
                }
                return '<div class="statusBadge_' . $data->id . '"><span class=" badge badge-' . $badge . '">' . $source . '</span></div>';
            })

            ->editColumn('career_id', function (JobApplication $data) {
                return $data->career->position;
            })
            ->editColumn('resume', function (JobApplication $data) {

                $url = asset('storage/files/job_resume/' . $data->resume);
                $icon = asset('assets/back-end/svg/cv.svg');
                $title = \App\CPU\translate('view resume or CV');

                return '
                    <a href="' . $url . '"
                    class="btn btn-primary btn-sm edit"
                    target="_blank"
                    title="' . $title . '"
                    style="cursor: pointer;">
                        <img width="20px" src="' . $icon . '" alt="CV">
                    </a>
                ';
            })


            ->addColumn('change_status', function ($row) {
                return '
            <select class="form-control form-control-sm changeStatus"
                 id="' . $row->id . '">
                <option value="pending" ' . ($row->status == 'pending' ? 'selected' : '') . '>Pending</option>
                <option value="shortlisted" ' . ($row->status == 'shortlisted' ? 'selected' : '') . '>Shortlisted</option>
                <option value="rejected" ' . ($row->status == 'rejected' ? 'selected' : '') . '>Rejected</option>
            </select>
        ';
            })


            ->addColumn('action', function (JobApplication $data) {

                $viewTitle = \App\CPU\translate('View');

                return '
        <a href="javascript:;"
           class="delete btn btn-danger btn-sm"
           id="' . $data->id . '">
            <i class="tio-add-to-trash"></i>
        </a>

        <a href="javascript:;"
           class="btn btn-info btn-sm viewBtn mt-3"
            data-toggle="modal"
            data-target="#viewApplicationModal"
           data-id="' . $data->id . '"
           title="' . $viewTitle . '"
           style="cursor: pointer;">
            <i class="tio-visible"></i>
        </a>
    ';
            })
            ->rawColumns(['date', 'change_status', 'id', 'status', 'resume', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }


    public function viewApplication(Request $request)
    {
        if ($request->id) {

            $app = JobApplication::with('career')->find($request->id);

            if (!$app) {
                return response()->json(['status' => false], 404);
            }

            return response()->json([
                'id' => $app->id,
                'name' => $app->name,
                'email' => $app->email,
                'phone' => $app->phone,
                'district' => $app->district,
                'created_at' => $app->created_at->format('d M Y h:i A'),

                'career' => $app->career?->position ?? 'N/A',
                'current_position' => $app->current_position,
                'experience_level' => $app->experience_level,
                'expected_salary' => $app->expected_salary ?? 'No',
                'portfolio_link' => $app->portfolio_link,
                'status' => ucfirst($app->status),
                'apply_date' => $app->created_at->format('d-m-Y'),
                'message' => strip_tags($app->message),
                'resume_url' => asset('storage/files/job_resume/' . $app->resume),
            ]);
        }
    }
}
