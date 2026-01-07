<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('category')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'tag' => 'nullable|string',
            'image' => 'nullable|string',
            'recommended' => 'boolean'
        ]);

        return Product::create($validated);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update($request->only([
            'category_id',
            'name',
            'description',
            'price',
            'tag',
            'image',
            'recommended'
        ]));

        return $product;
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Producto eliminado']);
    }
}
