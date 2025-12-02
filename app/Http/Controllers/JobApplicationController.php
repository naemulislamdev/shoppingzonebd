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

class JobApplicationController extends Controller
{

    public function index(Request $request)
    {
        $query_param = [];
        $search = $request->search;

        if (!empty($search)) {

            $key = explode(' ', $search);

            $application = JobApplication::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->where(function ($sub) use ($value) {
                        $sub->where('name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('status', 'like', "%{$value}%");
                    });
                }
            })->orderBy('id', 'desc');

            $query_param = ['search' => $search];
        } else {
            $application = JobApplication::orderBy('id', 'desc');
        }

        $applications = $application->paginate(Helpers::pagination_limit())->appends($query_param);

        return view("admin-views.job_applications.view", compact("applications", "search"));
    }

    public function status(Request $request)
    {

        if ($request->ajax()) {
            $app = JobApplication::find($request->id);
            $app->status = $request->status;
            $app->save();
            $data = $request->status;
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
    public function bulk_export_applications()
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
}
