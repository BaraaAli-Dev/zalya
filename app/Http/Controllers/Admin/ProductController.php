<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('id', 'desc')->paginate(10);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'size' => 'required|string|max:50',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'gender' => 'required|in:men,women,unisex',
            'order' => 'nullable|array',
            'order.*' => 'in:existing,new',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['product_name']);
        $validated['images'] = $this->buildImagesArray($request);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'size' => 'required|string|max:50',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'gender' => 'required|in:men,women,unisex',
            'order' => 'nullable|array',
            'order.*' => 'in:existing,new',
            'existing_images' => 'nullable|array',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['product_name']);
        $newImagesList = $this->buildImagesArray($request);

        foreach ($product->images ?? [] as $oldImage) {
            if (! in_array($oldImage, $newImagesList)) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        $validated['images'] = $newImagesList;

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images ?? [] as $image) {
            Storage::disk('public')->delete($image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }


    private function buildImagesArray(Request $request): array
    {
        $order = $request->input('order', []);
        $existingImages = $request->input('existing_images', []);
        $newFiles = $request->file('new_images', []);

        $final = [];
        $existingIndex = 0;
        $newIndex = 0;

        foreach ($order as $type) {
            if ($type === 'existing') {
                $final[] = $existingImages[$existingIndex] ?? null;
                $existingIndex++;
            } else {
                if (isset($newFiles[$newIndex])) {
                    $final[] = $newFiles[$newIndex]->store('products', 'public');
                }
                $newIndex++;
            }
        }

        return array_values(array_filter($final));
    }
}
