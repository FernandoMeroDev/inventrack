<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\EditRequest;
use App\Http\Requests\Products\IndexRequest;
use App\Http\Requests\Products\StoreRequest;
use App\Models\Products\Product;
use App\Models\Products\SalePrice;
use App\Models\Receipts\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\select;

class ProductController extends Controller
{
    public function index(IndexRequest $request)
    {
        $validated = $request->validated();
        $products = Product::where('name', 'LIKE', '%' . ($validated['search'] ?? null) . '%')->get();
        $total_count = $products->count();
        $products = $this->simplePaginate(
            $products, 10, $request->get('page', 1), $request->url()
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

    public function create()
    {
        return view('entities.products.create');
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $product = Product::create([
            'name' => mb_strtoupper($validated['name']),
            'min_stock' => $validated['min_stock'],
            'image_uploaded' => $request->hasFile('image')
        ]);
        for($i = 0; $i < count($validated['prices']); $i++){
            SalePrice::create([
                'units_number' => $validated['units_numbers'][$i],
                'value' => $validated['prices'][$i],
                'product_id' => $product->id
            ]);
        }
        if($request->hasFile('image')){
            $file_name = $product->id;
            Storage::disk('public')->putFileAs('/', $request->file('image'), "$file_name");
        }
        return redirect()->route('products.show', $product->id);
    }

    public function show(Product $product)
    {
        return view('entities.products.show', ['product' => $product]);
    }

    public function edit(Product $product)
    {
        return view('entities.products.edit', ['product' => $product]);
    }

    public function update(EditRequest $request, Product $product)
    {
        $validated = $request->validated();
        $product->update([
            'name' => mb_strtoupper($validated['name']),
            'min_stock' => $validated['min_stock'],
            'image_uploaded' => $product->image_updated || $request->hasFile('image')
        ]);
        foreach($product->salePrices as $salePrice) $salePrice->delete();
        for($i = 0; $i < count($validated['prices']); $i++){
            SalePrice::create([
                'units_number' => $validated['units_numbers'][$i],
                'value' => $validated['prices'][$i],
                'product_id' => $product->id
            ]);
        }
        if($request->hasFile('image')){
            $file_name = $product->id;
            Storage::disk('public')->putFileAs('/', $request->file('image'), "$file_name");
        }
        return redirect()->route('products.show', $product->id);
    }

    public function destroy(Product $product)
    {
        $receipts = Receipt::join('movements', 'movements.receipt_id', '=', 'receipts.id')
            ->select('receipts.id')
            ->where('movements.product_id', $product->id)
            ->get();
        foreach($receipts as $receipt) $receipt->delete();
        $product->delete();
        return redirect()->route('products.index');
    }
}
