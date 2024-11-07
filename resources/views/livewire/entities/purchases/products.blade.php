<div>
    <span>
        Productos <span class="text-red-400">*</span>
    </span>
    @if($selected->isNotEmpty())
    <x-table.simple>
        @foreach($selected as $product)
            <x-table.simple.tr>
                <x-table.simple.td>
                    <div class="grid grid-cols-2">
                        <div
                            x-on:click.prevent="$dispatch('open-modal', 'product-modal-{{$product->id}}')"
                            class="text-wrap col-span-2"
                        >
                            <input name="product_ids[]" hidden value="{{$product->id}}" />
                            {{$product->name}}
                        </div>
                        <x-entities.products.modal
                            :$product
                            :name="'product-modal-' . $product->id"
                        />
                        <div class="pr-1 col-span-1">
                            <p>Cantidad</p>
                            <x-number-input
                                x-model="amount"
                                name="amounts[]"
                                value="1"
                                required
                                min="1"
                                max="65000"
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

    <x-input-error :messages="$errors->get('product_ids')" />
    <x-input-error :messages="$errors->get('product_ids.*')" />
    <x-input-error :messages="$errors->get('amounts')" />
    <x-input-error :messages="$errors->get('amounts.*')" />

    <x-text-input wire:model.live="search" placeholder="Buscar..." />
    @if($products->isNotEmpty())
    <x-table.simple>
        @foreach($products as $product)
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
                            :$product
                            :name="'product-modal-' . $product->id"
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
    {{$products->links(data: ['scrollTo' => false])}}
    @endif
</div>