<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Models\Career;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $banners = Career::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('type', 'like', "%{$value}%")
                        ->orWhere('position', 'like', "%{$value}%")
                        ->orWhere('department', 'like', "%{$value}%")
                        ->orWhere('status', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $banners = Career::orderBy('id', 'desc');
        }
        $careers = $banners->paginate(Helpers::pagination_limit())->appends($query_param);

        return view("admin-views.career.view", compact("careers", "search"));
    }
    public function store(Request $request)
    {
        $request->validate([
            'position'     => 'required|string|max:255',
            'department'   => 'required|string|max:255',
            'location'     => 'nullable|string|max:255',
            'description'  => 'required|string',
            'opening_date' => 'required|date',
            'deadline'     => 'required|date',
            'type'         => 'required|string',
        ]);

        $career = new Career();
        $career->position = $request->position;
        $career->slug = Str::slug($request->position);
        $career->department = $request->department;
        $career->location = $request->location;
        $career->description = $request->description;
        $career->salary = $request->filled('salary')
            ? $request->salary
            : "Negotiable";
        $career->vacancies = $request->filled('vacancies')
            ? $request->vacancies
            : 1;
        $career->type = $request->type;
        $career->opening_date = $request->opening_date;
        $career->deadline = $request->deadline;

        $insertStatus = $career->save();
        if ($insertStatus) {
            return redirect()->back()->with('success', 'Job post added successfully!');
        } else {
            return redirect()->back()->with('error', 'Job post added unsuccessfully!');
        }
    }

    public function status(Request $request)
    {

        if ($request->ajax()) {
            $career = Career::find($request->id);
            $career->status = $request->status;
            $career->save();
            $data = $request->status;
            return response()->json($data);
        }
    }
    public function update(Request $request, $id)
    {
        $career = Career::find($id);

        $request->validate([
            'position'     => 'required|string|max:255',
            'department'   => 'required|string|max:255',
            'location'     => 'nullable|string|max:255',
            'description'  => 'required|string',
            'opening_date' => 'required|date',
            'deadline'     => 'required|date',
            'type'         => 'required|string',
        ]);
        $career->position = $request->position;
        $career->slug = Str::slug($request->position);
        $career->department = $request->department;
        $career->location = $request->location;
        $career->description = $request->description;
        $career->salary = $request->filled('salary')
            ? $request->salary
            : "Negotiable";
        $career->vacancies = $request->filled('vacancies')
            ? $request->vacancies
            : 1;
        $career->type = $request->type;
        $career->opening_date = $request->opening_date;
        $career->deadline = $request->deadline;

        $insertStatus = $career->save();
        if ($insertStatus) {
            return redirect()->back()->with('success', 'Job post updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Job post updated unsuccessfully!');
        }
    }
    public function delete(Request $request)
    {
        $cr = Career::find($request->id);
        $cr->delete();
        return response()->json();
    }


    public function careers()
    {
        $careers = Career::where("status", 1)->get();
        return view("web-views.careers", compact("careers"));
    }
    public function CareerDetails($slug)
    {
        $career = Career::where("slug", $slug)->where("status", 1)->first();
        return view("web-views.partials.career_details", compact("career"));
    }
    public function showApplyForm($slug)
    {
        $career = Career::where("slug", $slug)->first();

        return view("web-views.partials.career_form", compact("career"));
    }
    public function storeApplication(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255|unique:job_applications,email,NULL,id,career_id,' . $request->career_id,
            'phone'             => 'required|string|max:20|unique:job_applications,phone,NULL,id,career_id,' . $request->career_id,

            'expected_salary'   => 'nullable|string|min:0',
            'current_position'  => 'nullable|string|max:255',
            'experience_level'  => 'required|string|max:100',
            'portfolio_link'    => [
                'nullable',
                'regex:/^(https?:\/\/)?([\w\-]+\.)+[\w\-]{2,}(\/\S*)?$/'
            ],
            'resume'            => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);


        $application = new JobApplication();
        $application->career_id = $request->career_id;
        $application->name = $request->name;
        $application->email = $request->email;
        $application->phone = $request->phone;

        $application->expected_salary = $request->expected_salary;
        $application->current_position = $request->current_position;
        $application->experience_level = $request->experience_level;
        $application->portfolio_link = $request->portfolio_link;
        $application->message = $request->message;

        if ($request->hasFile('resume')) {
            $application->resume = ImageManager::uploadFile('files/job_resume/', $request->resume);
        }
        if ($application->save()) {
            return redirect()->back()->with("success", "Application Submitted Success!");
        } else {
            return redirect()->back()->with("error", "Something went wrong!");
        }
    }
}
