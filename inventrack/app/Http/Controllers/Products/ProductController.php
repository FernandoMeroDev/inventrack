<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\EditRequest;
use App\Http\Requests\Products\IndexRequest;
use App\Http\Requests\Products\StoreRequest;
use App\Models\Products\Product;
use App\Models\Products\ProductWarehouse;
use App\Models\Products\SalePrice;
use App\Models\Receipts\Receipt;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(IndexRequest $request)
    {
        $validated = $request->validated();
        $products = Product::where(
            'name', 'LIKE', '%' . ($validated['search'] ?? null) . '%'
        )->orderBy('name')->get();
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
        return view('entities.products.create', [
            'warehouses' => Warehouse::all()
        ]);
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $product = Product::create([
            'name' => mb_strtoupper($validated['name']),
            'purchase_price' => $validated['purchase_price'],
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
        foreach($validated['min_stocks'] as $warehouse_id => $min_stock){
            ProductWarehouse::create([
                'min_stock' => $min_stock,
                'product_id' => $product->id,
                'warehouse_id' => $warehouse_id
            ]);
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
            'purchase_price' => $validated['purchase_price'],
            'image_uploaded' => $product->image_uploaded || $request->hasFile('image')
        ]);
        foreach($product->salePrices as $salePrice) $salePrice->delete();
        for($i = 0; $i < count($validated['prices']); $i++){
            SalePrice::create([
                'units_number' => $validated['units_numbers'][$i],
                'value' => $validated['prices'][$i],
                'product_id' => $product->id
            ]);
        }
        foreach($product->warehouses as $warehouse){
            $productWarehouse = ProductWarehouse::find($warehouse->pivot->id);
            $productWarehouse->update([
                'min_stock' => $validated['min_stocks'][$warehouse->id]
            ]);
            $productWarehouse->save();
        }
        if($request->hasFile('image')){
            $file_name = $product->id;
            Storage::disk('public')->putFileAs('/', $request->file('image'), "$file_name");
        }
        if(isset($validated['remove_image'])){
            Storage::disk('public')->delete("/$product->id");
            $product->image_uploaded = false;
            $product->save();
        }
        return redirect()->route('products.show', $product->id);
    }

    public function destroy(Product $product)
    {
        Storage::disk('public')->delete("/$product->id");
        $receipts = Receipt::join('movements', 'movements.receipt_id', '=', 'receipts.id')
            ->select('receipts.id')
            ->where('movements.product_id', $product->id)
            ->get();
        foreach($receipts as $receipt) $receipt->delete();
        $product->delete();
        return redirect()->route('products.index');
    }
}
