<?php

namespace App\Livewire\Entities\Purchases;

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

    public function render()
    {
        return view('livewire.entities.purchases.products', [
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
        $this->selectedIds[] = $id;
    }

    public function remove($id): void
    {
        $flipped = array_flip($this->selectedIds);
        Arr::pull($flipped, $id);
        $this->selectedIds = array_flip($flipped);
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
            ->simplePaginate(4, pageName: 'products');
    }
}
