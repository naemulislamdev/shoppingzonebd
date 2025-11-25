<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class BlogController extends Controller
{
    public function list(Request $request)
    {
        $search = $request->search;
        $from   = $request->from;
        $to     = $request->to;

        // base query
        $blogsCatgories = Blog::query();

        // search
        if (!empty($search)) {
            $keywords = explode(' ', $search);

            $blogsCatgories->where(function ($q) use ($keywords) {

                foreach ($keywords as $value) {
                    $q->orWhere('created_at', 'like', "%{$value}%")
                        ->orWhere('title', 'like', "%{$value}%")
                        ->orWhere('image', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%")
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
            $blogsCatgories->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        }


        $blogsCatgories = $blogsCatgories->latest()
            ->paginate(20)
            ->appends([
                'search' => $search,
                'from'   => $from,
                'to'     => $to
            ]);
        $categories = BlogCategory::where("status", 1)->get();

        return view("admin-views.blog.index", compact("blogsCatgories", "categories"));
    }
    public function create()
    {
        $categories = BlogCategory::where("status", 1)->get();
        return view("admin-views.blog.create", compact("categories"));
    }
    public function store(Request $request)
    {
        $request->validate([
            "title"       => 'required|unique:blogs,title',
            "category_id" => 'required',
            "description" => 'nullable',
            "image"       => "nullable|image|mimes:jpg,jpeg,png,gif,bmp,tif,tiff,webp|max:4096",
        ], [
            "category_id.required" => "Please select a category.",
        ]);


        $blog = new Blog();
        $blog->title       = $request->title;
        $blog->slug        = Str::slug($request->title);
        $blog->description = $request->description;
        $blog->category_id = $request->category_id;

        if ($request->hasFile('image')) {

            $blog->image = Helpers::uploadWithCompress('blogs/', 300,  $request->file('image'), $request->title);
        }

        $blog->save();

        return redirect()->route('admin.business-settings.blog.index')->with("success", "Blog inserted successfully!");
    }
    public function edit($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        $categories = BlogCategory::where("status", 1)->get();
        return view("admin-views.blog.edit", compact("blog", 'categories'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            "title"       => 'required',
            "category_id" => 'required',
            "description" => 'nullable',
            "image"       => "nullable|image|mimes:jpg,jpeg,png,gif,bmp,tif,tiff,webp|max:4096",
        ], [
            "category_id.required" => "Please select a category.",
        ]);

        $blog = Blog::findOrFail($id);
        $blog->title       = $request->title;
        $blog->slug        = Str::slug($request->title);
        $blog->description = $request->description;
        $blog->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $blog->image = Helpers::updateWithCompress(
                'blogs/',
                $blog->image,
                $request->file('image'),
                $request->title
            );
        }

        $blog->save();

        return redirect()->route('admin.business-settings.blog.index')->with("success", "Blog Updated successfully!");
    }
    public function status(Request $request)
    {

        if ($request->ajax()) {
            $b = Blog::find($request->id);
            $b->status = $request->status;
            $b->save();
            $data = $request->status;
            return response()->json($data);
        }
    }
    public function destroy(Request $request)
    {
        $b = Blog::find($request->id);
        $b->delete();
        return response()->json();
    }
    public function index()
    {

        // return fornt end view
    }
    public function bulk_export_blog()
    {
        $blogs = Blog::latest()->get();
        //export from userInfos
        $data = [];
        foreach ($blogs as $item) {
            $data[] = [
                'Date'                => Carbon::parse($item->created_at)->format('d M Y'),
                'Image'               => asset('storage/blogs/' . $item->image),
                'Category'               => $item->blogCategory->name,
                'Title'             => $item->title,
                'Description'          => strip_tags($item->description),
                'Total Views'   => $item->views,
                'Status'            => $item->status == 0 ? 'unpublised' : 'published',
            ];
        }

        return (new FastExcel($data))->download('blogs_info.xlsx');
    }
}
