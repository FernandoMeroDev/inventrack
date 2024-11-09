<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\IndexRequest;
use App\Models\Products\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(IndexRequest $request)
    {
        $validated = $request->validated();
        $products = Product::where('name', 'LIKE', '%' . ($validated['search'] ?? null) . '%')->get();
        $total_count = $products->count();
        $products = $this->simplePaginate(
            $products, 15, $request->get('page', 1), $request->url()
        )->withQueryString()->fragment('products');

        $outsidePageRange = $products->count() < 1 && $total_count > 0;
        if($outsidePageRange)
            return $this->resetIndexPage($validated);

        return view('entities.products.index', [
            'products' => $products,
            'filters' => [
                'any' => isset($validated['search']),
                'search' => $validated['search'] ?? null,
            ]
        ]);
    }

    private function resetIndexPage(array $inputs)
    {
        $inputs['page'] = 1;
        return redirect()->route('products.index', $inputs);
    }

    public function show(Product $product)
    {
        return view('entities.products.show', [
            'product' => $product
        ]);
    }
}
