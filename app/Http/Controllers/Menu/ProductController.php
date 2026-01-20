<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return $query
            ->orderByDesc('recommended')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = validator($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'tag'         => 'nullable|string',
            'custom_tags' => 'nullable|string',
            'image_url'   => 'nullable|image|mimes:jpg,jpeg,png',
            'recommended' => 'boolean',
        ])->validate();

        if ($request->hasFile('image_url')) {
            $validated['image_url'] = $request->file('image_url')->store('products', 'public');
        }

        return Product::create($validated);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = validator($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'tag'         => 'nullable|string',
            'custom_tags' => 'nullable|string',
            'image_url'   => 'nullable|image|mimes:jpg,jpeg,png',
            'recommended' => 'boolean',
        ])->validate();

        if ($request->hasFile('image_url')) {
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }
            $validated['image_url'] = $request->file('image_url')->store('products', 'public');
        }

        $product->update($validated);

        return $product;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return response()->json(['message' => 'Producto eliminado']);
    }
}
