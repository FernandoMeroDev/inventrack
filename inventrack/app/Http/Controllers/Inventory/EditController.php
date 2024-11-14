<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\EditProductsRequest;
use App\Http\Requests\Inventory\EditRequest;
use App\Http\Requests\Inventory\UpdateProductsRequest;
use App\Models\Shelves\Level;
use App\Models\Shelves\LevelProduct;
use App\Models\Shelves\Shelf;
use App\Models\Warehouse;

class EditController extends Controller
{
    public function edit(EditRequest $request)
    {
        $validated = $request->validated();
        $inputs = ['warehouse' => Warehouse::find($validated['warehouse_id'])];
        $shelves = Shelf::where('warehouse_id', $validated['warehouse_id'])
            ->orderBy('number')->simplePaginate(10)
            ->withQueryString()->fragment('shelves');
        return view('entities.inventory.edit', [
            'inputs' => $inputs,
            'shelves' => $shelves
        ]);
    }

    public function editProducts(EditProductsRequest $request)
    {
        $validated = $request->validated();
        $inputs = [
            'shelf' => $shelf = Shelf::find($validated['shelf_id']),
            'level' => $shelf->levels->first(function(Level $level, int $key) use($validated) {
                return $level->number == ($validated['level_number'] ?? 1);
            }),
        ];
        return view('entities.inventory.edit-products', [
            'inputs' => $inputs,
        ]);
    }

    public function updateProducts(UpdateProductsRequest $request, Level $level)
    {
        $validated = $request->validated();
        $levelProducts = LevelProduct::where('level_id', $level->id)->get();
        foreach($levelProducts as $levelProduct){
            $levelProduct->delete();
        }
        if(isset($validated['product_ids'])){
            for($i = 0; $i < count($validated['product_ids']); $i++){
                LevelProduct::create([
                    'amount' => $validated['amounts'][$i],
                    'level_id' => $level->id,
                    'product_id' => $validated['product_ids'][$i],
                ]);
            }
        }
        return redirect(route('inventory.edit-products', [
            'shelf_id' => $level->shelf->id,
            'level_number' => $level->number
        ]) . '#products');
    }
}
