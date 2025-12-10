<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $blogs = BlogCategory::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%")
                        ->orWhere('status', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $blogs = BlogCategory::orderBy('id', 'desc');
        }
        $blogsCatgories = $blogs->paginate(Helpers::pagination_limit())->appends($query_param);

        return view("admin-views.blog.category.index", compact("blogsCatgories"));
    }
    public function store(Request $request)
    {
        $request->validate([
            "name" => 'required|unique:blog_categories,name'
        ]);

        BlogCategory::create([
            "name" => $request->name,
            "slug" => Str::slug($request->name)
        ]);

        return redirect()->back()->with("success", "Blog category inserted successfully!");
    }

    public function update(Request $request, $id) {
        $request->validate([
            "name" => 'required|unique:blog_categories,name,' . $id
        ]);

        $category = BlogCategory::findOrFail($id);

        $category->update([
            "name" => $request->name,
            "slug" => Str::slug($request->name)
        ]);

        return redirect()->back()->with("success", "Blog category updated successfully!");
    }
    public function destroy(Request $request) {
        $bc = BlogCategory::find($request->id);
        $bc->delete();
        return response()->json();
    }
    public function status(Request $request)
    {

        if ($request->ajax()) {
            $bc = BlogCategory::find($request->id);
            $bc->status = $request->status;
            $bc->save();
            $data = $request->status;
            return response()->json($data);
        }
    }
}
