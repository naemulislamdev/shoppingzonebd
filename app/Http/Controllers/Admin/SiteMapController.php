<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\Route;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;

class SiteMapController extends Controller
{
    // public function index()
    // {
    //     return view('admin-views/site-map/view');
    // }

    // public function download(){
    //     SitemapGenerator::create(url('/'))->writeToFile(public_path('sitemap.xml'));
    //     return response()->download(public_path('sitemap.xml'));
    // }


    // without image video
    public function index()
    {
        return view('admin-views/site-map/view');
    }

    public function download()
    {
        $sitemap = Sitemap::create();

        // ✅ Home Page
        $sitemap->add(
            Url::create(url('/'))
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // ✅ Product Categories
        $categories = Category::where('home_status', 1)->get();
        foreach ($categories as $category) {
            $sitemap->add(
                Url::create(url('/category/' . $category->slug))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        }

        // ✅ Products
        $products = Product::where('status', 1)->get();
        foreach ($products as $product) {
            $sitemap->add(
                Url::create(url('/product/' . $product->slug))
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        }

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        return response()->download($path);
    }
}
