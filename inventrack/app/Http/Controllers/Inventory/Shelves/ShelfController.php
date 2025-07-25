<?php

namespace App\Http\Controllers\Inventory\Shelves;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Shelves\UpdateRequest;
use App\Models\Shelves\Level;
use App\Models\Shelves\Shelf;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
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
}
