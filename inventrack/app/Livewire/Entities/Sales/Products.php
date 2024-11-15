<?php

namespace App\Livewire\Entities\Sales;

use App\Models\Products\Product;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination, WithoutUrlPagination;

    /**
     * Search user input
     */
    public $search;

    /**
     * Products selected
     */
    #[Locked]
    public $selectedIds = [];

    /**
     * Warehouse id
     */
    #[Locked]
    public $warehouse_id;

    private $warehouse_id_private;

    public function mount(int $warehouse_id)
    {
        $this->warehouse_id_private = $warehouse_id;
    }

    public function render()
    {
        $this->setPublicProperties(['warehouse_id']);
        return view('livewire.entities.sales.products', [
            'products' => $this->products(),
            'selected' => $this->selected()
        ]);
    }

    public function updated($property)
    {
        if($property == 'search'){
            $this->resetPage('products');
        }
    }

    public function add($id): void
    {
        $flipped = array_flip($this->selectedIds);
        if( ! Arr::exists($flipped, $id) ) $this->selectedIds[] = $id;
    }

    public function remove($id): void
    {
        $flipped = array_flip($this->selectedIds);
        if(Arr::exists($flipped, $id)){
            Arr::pull($flipped, $id);
            $this->selectedIds = array_flip($flipped);
        }
    }

    private function selected(): Collection
    {
        $products = collect([]);
        foreach($this->selectedIds as $id) $products->push(Product::find($id));
        return $products;
    }

    private function products(): Paginator
    {
        return Product::whereNotIn('id', $this->selectedIds)
            ->where('name','LIKE', "%$this->search%")
            ->orderBy('name')
            ->simplePaginate(4, pageName: 'products');
    }

    private function setPublicProperties(array $names): void
    {
        foreach($names as $name){
            if(isset($this->{$name . '_private'})){
                $this->{$name} = $this->{$name . '_private'};
            }
        }
    }
}
