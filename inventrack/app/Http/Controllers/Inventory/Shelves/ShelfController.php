<?php

namespace App\Http\Controllers\Inventory\Shelves;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Shelves\CreateRequest;
use App\Http\Requests\Inventory\Shelves\StoreRequest;
use App\Http\Requests\Inventory\Shelves\UpdateRequest;
use App\Models\Shelves\Level;
use App\Models\Shelves\Shelf;

class ShelfController extends Controller
{
    public function create(CreateRequest $request)
    {
        $validated = $request->validated();
        return view('entities.inventory.shelves.create', [
            'warehouse_id' => $validated['warehouse_id']
        ]);
    }

    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        $shelf = Shelf::create([
            'number' => $validated['number'],
            'refrigerator' => false,
            'warehouse_id' => $validated['warehouse_id']
        ]);
        for($i = 0; $i < count($validated['levels']); $i++){
            Level::create([
                'number' => $i,
                'shelf_id' => $shelf->id
            ]);
        }
        return redirect()->route('inventory.edit-products', [
            'shelf_id' => $shelf->id,
            'level_id' => $shelf->levels->get(1)->id
        ]);
    }

    public function edit(Shelf $shelf)
    {
        return view('entities.inventory.shelves.edit', [
            'shelf' => $shelf
        ]);
    }

    public function update(UpdateRequest $request, Shelf $shelf)
    {
        $validated = $request->validated();
        $shelf->number = $validated['number'];
        $shelf->save();
        $levels = $shelf->levels;
        foreach($levels as $level){
            $level->delete();
        }
        for($i = 0; $i < count($validated['levels']); $i++){
            Level::create([
                'number' => $i,
                'shelf_id' => $shelf->id
            ]);
        }
        return redirect()->route('inventory.edit-products', [
            'shelf_id' => $shelf->id,
            'level_id' => $shelf->levels->get(1)->id
        ]);
    }

    public function destroy(Shelf $shelf)
    {
        $warehouse_id = $shelf->warehouse->id;
        $shelf->delete();
        return redirect()->route('inventory.edit', [
            'warehouse_id' => $warehouse_id
        ]);
    }
}
