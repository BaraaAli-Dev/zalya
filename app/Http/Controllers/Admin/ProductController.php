<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'variants')->orderBy('id', 'desc')->paginate(10);

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
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'gender' => 'required|in:men,women,unisex',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string|max:50',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'order' => 'nullable|array',
            'order.*' => 'in:existing,new',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $slug = Str::slug($validated['product_name']);
        $images = $this->buildImagesArray($request);

        $product = DB::transaction(function () use ($validated, $slug, $images) {
            $product = Product::create([
                'product_name' => $validated['product_name'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'gender' => $validated['gender'],
                'is_featured' => $validated['is_featured'] ?? false,
                'is_best_seller' => $validated['is_best_seller'] ?? false,
                'slug' => $slug,
                'images' => $images,
            ]);

            foreach ($validated['variants'] as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $variant['size'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                ]);
            }

            return $product;
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('variants');

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'gender' => 'required|in:men,women,unisex',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size' => 'required|string|max:50',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'order' => 'nullable|array',
            'order.*' => 'in:existing,new',
            'existing_images' => 'nullable|array',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $slug = Str::slug($validated['product_name']);
        $newImagesList = $this->buildImagesArray($request);

        foreach ($product->images ?? [] as $oldImage) {
            if (! in_array($oldImage, $newImagesList)) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        DB::transaction(function () use ($validated, $slug, $newImagesList, $product) {
            $product->update([
                'product_name' => $validated['product_name'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'gender' => $validated['gender'],
                'is_featured' => $validated['is_featured'] ?? false,
                'is_best_seller' => $validated['is_best_seller'] ?? false,
                'slug' => $slug,
                'images' => $newImagesList,
            ]);

            $keepIds = collect($validated['variants'])->pluck('id')->filter()->all();
            $product->variants()->whereNotIn('id', $keepIds)->delete();

            foreach ($validated['variants'] as $variant) {
                $product->variants()->updateOrCreate(
                    ['id' => $variant['id'] ?? null],
                    [
                        'size' => $variant['size'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                    ]
                );
            }
        });

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
