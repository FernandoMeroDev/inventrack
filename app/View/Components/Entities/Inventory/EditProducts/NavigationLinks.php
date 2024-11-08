<?php

namespace App\View\Components\Entities\Inventory\EditProducts;

use App\Models\Shelves\Level;
use App\Models\Shelves\Shelf;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavigationLinks extends Component
{
    public ?array $previous;

    public bool $previousExists = true;

    public ?array $next;

    public bool $nextExists = true;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Shelf $shelf,
        public Level $level,
    ){
        $this->previous = $this->previousLink();
        $this->next = $this->nextLink();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.entities.inventory.edit-products.navigation-links');
    }

    public function previousLink()
    {
        $number = $this->level->number - 1;
        if($number < 0){
            $previousShelf = Shelf::where('warehouse_id', $this->shelf->warehouse_id)
                ->where('number', '<', $this->shelf->number)
                ->orderBy('number', 'desc')
                ->first();
            if(is_null($previousShelf)){
                $this->previousExists = false;
                return null;
            }
            return [
                'shelf_id' => $previousShelf->id,
                'level_number' => 1,
                'label' => "Percha $previousShelf->number"
            ];
        } else {
            return $this->defaultLink($number);
        }
    }

    public function nextLink()
    {
        $number = $this->level->number + 1;
        $levels_count = $this->shelf->levels->count();
        if($number > ($levels_count - 1)){
            $nextShelf = Shelf::where('warehouse_id', $this->shelf->warehouse_id)
                ->where('number', '>', $this->shelf->number)
                ->orderBy('number')
                ->first();
            if(is_null($nextShelf)){
                $this->nextExists = false;
                return null;
            }
            return [
                'shelf_id' => $nextShelf->id,
                'level_number' => 1,
                'label' => "Percha $nextShelf->number"
            ];
        } else {
            return $this->defaultLink($number);
        }
    }

    private function defaultLink(int $number): array
    {
        return [
            'shelf_id' => $this->shelf->id,
            'level_number' => $number,
            'label' => "Piso $number",
        ];
    }
}
