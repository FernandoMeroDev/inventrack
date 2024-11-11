<?php

namespace App\View\Components\Entities\Inventory\EditProducts;

use App\Models\Shelves\Level;
use App\Models\Shelves\Shelf;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavigationLinks extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Shelf $shelf,
        public Level $level,
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.entities.inventory.edit-products.navigation-links', [
            'previousShelfLink' => $this->previousShelfLink(),
            'nextShelfLink' => $this->nextShelfLink(),
            'previousLevelLink' => $this->previousLevelLink(),
            'nextLevelLink' => $this->nextLevelLink(),
        ]);
    }

    public function previousShelfLink(): string
    {
        $previousShelf = Shelf::where('warehouse_id', $this->shelf->warehouse_id)
            ->where('number', '<', $this->shelf->number)
            ->orderBy('number', 'desc')
            ->first();

        if(is_null($previousShelf)) return 'none';

        return route('inventory.edit-products', [
            'shelf_id' => $previousShelf->id,
            'level_number' => 1,
        ]);
    }

    public function nextShelfLink(): string
    {
        $nextShelf = Shelf::where('warehouse_id', $this->shelf->warehouse_id)
                ->where('number', '>', $this->shelf->number)
                ->orderBy('number')
                ->first();

        if(is_null($nextShelf)) return 'none';

        return route('inventory.edit-products', [
            'shelf_id' => $nextShelf->id,
            'level_number' => 1,
        ]);
    }

    public function previousLevelLink(): string
    {
        $number = $this->level->number - 1;
        if($number < 0){
            $previousShelf = Shelf::where('warehouse_id', $this->shelf->warehouse_id)
                ->where('number', '<', $this->shelf->number)
                ->orderBy('number', 'desc')
                ->first();

            if(is_null($previousShelf)) return 'none';

            return route('inventory.edit-products', [
                'shelf_id' => $previousShelf->id,
                'level_number' => $previousShelf->levels->count() - 1,
            ]);
        }
        return route('inventory.edit-products', [
            'shelf_id' => $this->shelf->id,
            'level_number' => $number,
        ]);
    }

    public function nextLevelLink(): string
    {
        $number = $this->level->number + 1;
        $levels_count = $this->shelf->levels->count();
        if($number > ($levels_count - 1)){
            $nextShelf = Shelf::where('warehouse_id', $this->shelf->warehouse_id)
                ->where('number', '>', $this->shelf->number)
                ->orderBy('number')
                ->first();

            if(is_null($nextShelf)) return 'none';

            return route('inventory.edit-products', [
                'shelf_id' => $nextShelf->id,
                'level_number' => 1,
            ]);
        }
        return route('inventory.edit-products', [
            'shelf_id' => $this->shelf->id,
            'level_number' => $number,
        ]);
    }
}
