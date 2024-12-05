<div>
@if($products->isNotEmpty())
<x-table.simple>
    @foreach($products as $i => $product)
        <x-table.simple.tr>
            <x-table.simple.td>
                <div class="grid grid-cols-2">
                    <div
                        x-on:click.prevent="$dispatch('open-modal', 'product-modal-{{$product->id}}')"
                        class="text-wrap col-span-2"
                    >
                        {{$product->name}}
                        <input name="product_ids[]" hidden value="{{$product->id}}" />
                    </div>
                    <x-entities.products.modal
                        :$product
                        :warehouse-id="$shelf->warehouse->id"
                        :name="'product-modal-' . $product->id"
                    />
                    <div class="pr-1 col-span-1">
                        <label for="amountInput{{$product->id}}" class="block font-bold">
                            Cantidad
                        </label>
                        <x-number-input
                            name="amounts[]"
                            value="{{$amounts[$i]}}"
                            x-on:change="$wire.changeAmount({{$i}}, $event.target.value)"
                            min="1" max="255"
                            id="amountInput{{$product->id}}"
                            class="w-full h-6 pl-1"
                        />
                    </div>
                    <div class="col-span-1 flex justify-center items-end">
                        <button
                            wire:click.prevent="remove({{$product->id}})"
                            class="text-white bg-red-400 px-2 rounded mt-1"
                        >Remover</button>
                    </div>
                </div>
            </x-table.simple.td>
        </x-table.simple.tr>
    @endforeach
</x-table.simple>
@endif

<x-text-input 
    wire:model.live.debounce.400ms="search" 
    placeholder="Buscar..." 
    id="searchProductsFalseInput" 
/>
@if($searchedProducts->isNotEmpty())
<x-table.simple>
    @foreach($searchedProducts as $product)
        <x-table.simple.tr>
            <x-table.simple.td>
                <div class="grid grid-cols-2">
                    <div
                        x-on:click.prevent="$dispatch('open-modal', 'product-modal-{{$product->id}}')"
                        class="text-wrap col-span-2"
                    >
                        {{$product->name}}
                    </div>
                    <x-entities.products.modal
                        :name="'product-modal-' . $product->id"
                        :$product
                        :warehouse-id="$shelf->warehouse->id"
                    />
                    <div class="col-span-2 flex justify-center items-end">
                        <button
                            wire:click.prevent="add({{$product->id}})"
                            class="text-white bg-black px-3 rounded mt-1"
                        >Agregar</button>
                    </div>
                </div>
            </x-table.simple.td>
        </x-table.simple.tr>
    @endforeach
</x-table.simple>
{{$searchedProducts->links(data: ['scrollTo' => false])}}
@endif

<div class="mt-8 flex justify-between">
    <x-primary-button type="submit">
        Guardar
    </x-primary-button>
    <x-secondary-button
        x-data
        x-on:click.prevent="$dispatch('open-modal', 'empty-modal')"
    >
        Vaciar
    </x-secondary-button>
    <x-modal :name="'empty-modal'">
        <div class="p-2">
            <h2 class="text-center text-md font-bold">¿Seguro?</h2>
            <div class="mt-3 flex justify-evenly">
            <x-danger-button x-on:click.prevent="$dispatch('close'); $wire.empty()">
                Si
            </x-danger-button>
            <x-secondary-button x-on:click.prevent="$dispatch('close')">
                No
            </x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>
</div>
