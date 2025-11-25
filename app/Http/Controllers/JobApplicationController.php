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
        $search = $request->search;
        $from   = $request->from;
        $to     = $request->to;

        // base query
        $applications = JobApplication::query();

        // search
        if (!empty($search)) {
            $keywords = explode(' ', $search);

            $applications->where(function ($q) use ($keywords) {

                foreach ($keywords as $value) {

                    // normal fields
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('position', 'like', "%{$value}%")
                        ->orWhere('experience_level', 'like', "%{$value}%")
                        ->orWhere('portfolio_link', 'like', "%{$value}%")
                        ->orWhere('message', 'like', "%{$value}%")
                        ->orWhere('status', 'like', "%{$value}%")
                        ->orWhere('created_at', 'like', "%{$value}%");;
                }
            });
        }


        // date filter
        if (!empty($from) && !empty($to)) {
            $applications->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        }


        $applications = $applications->latest()
            ->paginate(20)
            ->appends([
                'search' => $search,
                'from'   => $from,
                'to'     => $to
            ]);

        return view("admin-views.job_applications.view", compact("applications"));
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
