<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::latest()->paginate(15);
        return view('masterdata::product-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('masterdata::product-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ProductCategory::create($request->all());
        return redirect()->route('master-data.product-categories.index')->with('success', 'Product Category created successfully.');
    }

    public function show(ProductCategory $productCategory)
    {
        return view('masterdata::product-categories.show', compact('productCategory'));
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('masterdata::product-categories.edit', compact('productCategory'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $productCategory->update($request->all());
        return redirect()->route('master-data.product-categories.index')->with('success', 'Product Category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect()->route('master-data.product-categories.index')->with('success', 'Product Category deleted successfully.');
    }
}
