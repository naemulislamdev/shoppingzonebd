<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Product;
use App\Models\BatchDiscount;
use App\Models\FlatDiscount;
use Illuminate\Http\Request;

class DiscountManageController extends Controller
{
    public function discountFlat()
    {
        $flatDiscounts = FlatDiscount::with('category')->get();
        return view('admin-views.discount-manage.flat.index', compact('flatDiscounts'));
    }

    public function discountFlatCreate()
    {
        $categories = Category::where(['parent_id' => 0])->get();
        return view('admin-views.discount-manage.flat.create', compact('categories'));
    }

    public function discountFlatStore(Request $request)
    {
        // Validate the request data
        $request->validate([
            'category' => 'required|string',
            'discount_amount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:flat,percent',
        ]);

        $discountAmount = $request->discount_amount;
        $discountType   = $request->discount_type;

        // Query products depending on category
        $products = Product::where('status', 1)->get();

        $foundAnyProduct = false;

        foreach ($products as $product) {
            $categoryIds = json_decode($product->category_ids, true);
            $ids = array_column($categoryIds, 'id');

            // Check if this product belongs to the selected category OR all-category
            if ($request->category === 'all-category' || in_array($request->category, $ids)) {
                $foundAnyProduct = true;

                if ($discountType === 'flat') {
                    $product->discount = $discountAmount;
                    $product->discount_type = 'flat';
                } elseif ($discountType === 'percent') {
                    $product->discount = $discountAmount;
                    $product->discount_type = 'percent';
                }

                $product->save();
            }
        }

        if (!$foundAnyProduct) {
            return redirect()->back()->with('error', 'No products found for the selected category.');
        }

        // Save discount record
        $discount = new FlatDiscount();
        $discount->category_id     = $request->category;
        $discount->discount_amount = $discountAmount;
        $discount->discount_type   = $discountType;
        $discount->save();

        return redirect()->route('admin.discount.flat')->with('success', 'Flat discount created successfully.');
    }

    public function discountFlatEdit($id)
    {
        $flatDiscount = FlatDiscount::findOrFail($id);
        $categories = Category::where(['parent_id' => 0])->get();
        return view('admin-views.discount-manage.flat.edit', compact('flatDiscount', 'categories'));
    }
    public function discountFlatUpdate(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'category' => 'required|string',
            'discount_amount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:flat,percentage',
        ]);

        $discountAmount = $request->discount_amount;
        $discountType   = $request->discount_type;

        // Query products depending on category
        $products = Product::where('status', 1)->get();

        $foundAnyProduct = false;

        foreach ($products as $product) {
            $categoryIds = json_decode($product->category_ids, true);
            $ids = array_column($categoryIds, 'id');

            // Check if this product belongs to the selected category OR all-category
            if ($request->category === 'all-category' || in_array($request->category, $ids)) {
                $foundAnyProduct = true;

                if ($discountType === 'flat') {
                    $product->discount = $discountAmount;
                    $product->discount_type = 'flat';
                } elseif ($discountType === 'percentage') {
                    $product->discount = $discountAmount;
                    $product->discount_type = 'percent';
                }

                $product->save();
            }
        }

        if (!$foundAnyProduct) {
            return redirect()->back()->with('error', 'No products found for the selected category.');
        }

        // update discount record
        $discount = FlatDiscount::findOrFail($id);
        $discount->category_id     = $request->category;
        $discount->discount_amount = $discountAmount;
        $discount->discount_type   = $discountType;
        $discount->save();

        return redirect()->route('admin.discount.flat')->with('success', 'Flat discount updated successfully.');
    }
    public function discountFlatDelete($id)
    {
        $flatDiscount = FlatDiscount::findOrFail($id);
        $categoryId = $flatDiscount->category_id;

        // Query products depending on category
        $products = Product::where('status', 1)->get();

        foreach ($products as $product) {
            $categoryIds = json_decode($product->category_ids, true);
            $ids = array_column($categoryIds, 'id');

            if (in_array($categoryId, $ids)) {
                // Reset discount fields
                $product->discount = 0;
                $product->discount_type = null;
                $product->save();
            }
        }

        $flatDiscount->delete();

        return redirect()->route('admin.discount.flat')->with('success', 'Flat discount deleted successfully.');
    }

    // Batch Discount Function
    public function discountBatch()
    {
        $batchDiscounts = BatchDiscount::all();
        return view('admin-views.discount-manage.batch.index', compact('batchDiscounts'));
    }

    public function discountBatchCreate()
    {
        $products = Product::where('status', 1)->get();
        return view('admin-views.discount-manage.batch.create', compact('products'));
    }

    public function discountBatchStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'discount_amounts' => 'required|array',
            'discount_types' => 'required|array',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);
        $products = $request->product_ids;
        foreach ($products as $productId) {
            $amount = $request->discount_amounts[$productId] ?? 0;
            $type = $request->discount_types[$productId] ?? 'flat';

            $product = Product::find($productId);
            if ($product) {
                $product->discount = $amount;
                $product->discount_type = $type;
                $product->save();
            }
        }


        // Store the batch discount
        BatchDiscount::create([
            'title' => $request->title,
            'discount_amount' => json_encode($request->discount_amounts),
            'discount_type' => json_encode($request->discount_types),
            'product_ids' => json_encode($request->product_ids),
            'status' => true
        ]);
        return redirect()->route('admin.discount.batch')->with('success', 'Batch discount created successfully.');
    }
    public function discountBatchEdit($id)
    {
        $batchDiscount = BatchDiscount::findOrFail($id);
        $products = Product::where('status', 1)->get();
        return view('admin-views.discount-manage.batch.edit', compact('batchDiscount', 'products'));
    }
    public function discountBatchUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'discount_amounts' => 'required|array',
            'discount_types' => 'required|array',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);
        $products = $request->product_ids;
        foreach ($products as $productId) {
            $amount = $request->discount_amounts[$productId] ?? 0;
            $type = $request->discount_types[$productId] ?? 'flat';

            $product = Product::find($productId);
            if ($product) {
                $product->discount = $amount;
                $product->discount_type = $type;
                $product->save();
            }
        }


        // Store the batch discount
        $batchDiscount = BatchDiscount::findOrFail($id);
        $batchDiscount->update([
            'title' => $request->title,
            'discount_amount' => json_encode($request->discount_amounts),
            'discount_type' => json_encode($request->discount_types),
            'product_ids' => json_encode($request->product_ids),
        ]);
        return redirect()->route('admin.discount.batch')->with('success', 'Batch discount updated successfully.');
    }
    public function discountBatchDelete($id)
    {
        $batchDiscount = BatchDiscount::findOrFail($id);

        $productIds = json_decode($batchDiscount->product_ids, true);
        Product::whereIn('id', $productIds)->update(['discount' => 0, 'discount_type' => null]);

        $batchDiscount->delete();
        return redirect()->route('admin.discount.batch')->with('success', 'Batch discount deleted successfully.');
    }
    public function discountBatchProduct($id)
    {
        $batchDiscount = BatchDiscount::findOrFail($id);

        $productIds = json_decode($batchDiscount->product_ids, true);
        $products = Product::whereIn('id', $productIds)->get();
        return view('admin-views.discount-manage.batch.see_product', compact('products'));
    }
    public function discountBatchRemoveProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->discount = 0;
        $product->discount_type = null;
        $product->save();

        // Remove product from any batch discount
        $batchDiscounts = BatchDiscount::all();

        foreach ($batchDiscounts as $batchDiscount) {
            $productIds = json_decode($batchDiscount->product_ids, true) ?? [];
            $discountAmounts = json_decode($batchDiscount->discount_amount, true) ?? [];
            $discountTypes = json_decode($batchDiscount->discount_type, true) ?? [];

            // If product exists in this batch
            if (in_array($id, $productIds)) {
                // Remove product ID
                $updatedProductIds = array_diff($productIds, [$id]);
                $batchDiscount->product_ids = json_encode(array_values($updatedProductIds));

                // Remove discount amount & type
                unset($discountAmounts[$id]);
                unset($discountTypes[$id]);

                $batchDiscount->discount_amount = json_encode($discountAmounts);
                $batchDiscount->discount_type = json_encode($discountTypes);

                $batchDiscount->save();
            }
        }

        return back()->with('success', 'Product removed from batch discount successfully.');
    }
    public function discountBatchStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:batch_discounts,id',
        ]);

        $batchDiscount = BatchDiscount::findOrFail($request->id);
        $batchDiscount->status = !$batchDiscount->status;
        $batchDiscount->save();

        return response()->json(['success' => true, 'status' => $batchDiscount->status]);
    }
}
