<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->get();
        $user = Auth::user(); // Get the authenticated user
        return view('product.index', ['products' => $products, 'user' => $user]);
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $photoPath;
        }

        $data['user_id'] = Auth::id();

        Product::create($data);

        return redirect()->route('product.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('product.edit', ['product' => $product]);
    }

    public function update(Product $product, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|numeric',
            'price' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete the old photo if exists
            if ($product->photo) {
                \Storage::delete('public/' . $product->photo);
            }
            $photoPath = $request->file('photo')->store('photos', 'public');
            $data['photo'] = $photoPath;
        }

        $product->update($data);

        return redirect()->route('product.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        // Delete the photo if exists
        if ($product->photo) {
            \Storage::delete('public/' . $product->photo);
        }

        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully');
    }
}
