<?php

namespace App\Livewire\Entities\Warehouse;

use App\Models\Warehouse;
use Illuminate\Pagination\Paginator;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Choose extends Component
{
    use WithPagination, WithoutUrlPagination;

    /**
     * Indicates if choose an option is mandatory
     */
    #[Locked]
    public bool $required;

    /**
     * Can be: false (not default), 'all' (string), or an Entity's id (int)
     */
    #[Locked]
    public string|int|false $default;

    private string|int|false $default_private;

    /**
     * Indicates if 'all' option is allowed
     */
    #[Locked]
    public bool $all;

    private bool $all_private;

    /**
     * Search input
     */
    public $search = null;

    public function mount(
        bool $required = false,
        string|int|false $default = false,
        bool $all = false
    )
    {
        $this->required = $required;
        $this->default_private = $default;
        $this->all_private = $all;
    }

    public function render()
    {
        $this->setPublicProperties(['default', 'all']);
        return view('livewire.entities.warehouse.choose', [
            'warehouses' => $this->warehouses($this->default)
        ]);
    }

    public function updated($property)
    {
        if($property == 'search'){
            $this->resetPage('warehouses');
        }
    }

    private function warehouses(string|int|false $default): Paginator
    {
        if($default !== false && is_null($this->search)){
            return Warehouse::where('id', $default)->simplePaginate(3, pageName: 'warehouses');
        } else {
            return Warehouse::where(
                'name', 'LIKE', '%' . $this->search . '%'
            )->simplePaginate(3, pageName: 'warehouses');
        }
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
