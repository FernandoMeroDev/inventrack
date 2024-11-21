<?php

namespace App\Livewire\Entities\Inventory\EditProducts;

use App\Models\Products\Product;
use App\Models\Shelves\Shelf;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
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
     * Products in level
     */
    #[Locked]
    public $productsIds = [];

    private $productsIds_private;

    /**
     * Amounts in level
     */
    #[Locked]
    public $amounts = [];

    private $amounts_private;

    /**
     * Shelf id
     */
    #[Locked]
    public $shelf_id;

    private $shelf_id_private;

    /**
     * Level id
     */
    #[Locked]
    public $level_id;

    private $level_id_private;

    public function mount(int $shelf_id, int $level_id)
    {
        $this->shelf_id_private = $shelf_id;
        $this->level_id_private = $level_id;
        $products = Product::join('level_product', 'level_product.product_id', '=', 'products.id')
            ->select('products.id', 'level_product.amount')
            ->where('level_product.level_id', $level_id)
            ->get();
        $this->productsIds_private = $products->pluck('id')->toArray();
        $this->amounts_private = $products->pluck('amount')->toArray();
    }

    public function render()
    {
        $this->setPublicProperties(['shelf_id', 'level_id', 'productsIds', 'amounts']);
        $products = $this->products();
        $searchedProducts = $this->searchedProducts();
        return view('livewire.entities.inventory.edit-products.products', [
            'products' => $products,
            'searchedProducts' => $searchedProducts,
            'shelf' => Shelf::find($this->shelf_id)
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
        $flipped = array_flip($this->productsIds);
        if( ! Arr::exists($flipped, $id) ){
            $this->productsIds[] = $id;
            $this->amounts[] = 0;
        };
    }

    public function remove($id): void
    {
        $flipped = array_flip($this->productsIds);
        if(Arr::exists($flipped, $id)){
            $key = $flipped[$id];
            Arr::pull($this->amounts, $key);
            Arr::pull($flipped, $id);
            $this->productsIds = array_flip($flipped);
        }
    }

    #[Renderless]
    public function changeAmount($i, $amount)
    {
        $this->amounts[$i] = $amount;
    }

    public function empty(): void
    {
        $this->productsIds = [];
    }

    private function products(): BaseCollection
    {
        $products = collect([]);
        foreach($this->productsIds as $key => $id){
            $product = Product::find($id);
            $product->amount = $product->remainIn(
                $this->level_id,
                entity: 'Level'
            );
            $products->put($key, $product);
        };
        return $products;
    }

    private function searchedProducts(): Paginator
    {
        return Product::whereNotIn('id', $this->productsIds)
            ->where('name','LIKE', "%$this->search%")
            ->orderBy('name')
            ->simplePaginate(3, pageName: 'products');
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
