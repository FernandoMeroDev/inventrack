<div>
    <span>
        Productos <span class="text-red-400">*</span>
    </span>
    @if($selected->isNotEmpty())
    <x-table.simple>
        @foreach($selected as $product)
            <x-table.simple.tr>
                <x-table.simple.td>
                    <div
                        x-data="movementInput(
                            {{$product->remainIn($warehouse_id)}}
                        )"
                        class="grid grid-cols-2"
                    >
                        <div
                            x-on:click.prevent="$dispatch('open-modal', 'product-modal-{{$product->id}}')"
                            class="text-wrap col-span-2"
                        >
                            <input name="product_ids[]" hidden value="{{$product->id}}" />
                            {{$product->name}}
                        </div>
                        <x-entities.products.modal
                            :$product
                            :warehouse-id="$warehouse_id"
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
                                max="{{$product->remainIn($warehouse_id)}}"
                                class="w-full h-6 pl-1"
                            />
                        </div>
                        <div class="col-span-1">
                            <p>Precio</p>
                            <x-select-input name="sale_price_ids[]" required class="w-full h-6 pl-0 pt-0 pb-0">
                                @foreach($product->salePrices as $salePrice)
                                    <template x-if="amount >= {{$salePrice->units_number}}">
                                        <option
                                            value="{{$salePrice->id}}"
                                        >{{$salePrice->valueFormated()}}</option>
                                    </template>
                                @endforeach
                            </x-select-input>
                        </div>
                        <div class="col-span-2">
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
    <x-input-error :messages="$errors->get('sale_price_ids')" />
    <x-input-error :messages="$errors->get('sale_price_ids.*')" />

    <x-text-input wire:model.live="search" placeholder="Buscar..." class="block" />
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
                            :warehouse-id="$warehouse_id"
                            :name="'product-modal-' . $product->id"
                        />
                        <div class="pr-1 col-span-1">
                            <p>Quedan</p>
                            <x-number-input
                                :disabled="true"
                                value="{{$product->remainIn($warehouse_id)}}"
                                class="w-full h-6 pl-1"
                            />
                        </div>
                        <div class="col-span-1 flex justify-center items-end">
                            <button
                                @disabled($product->remainIn($warehouse_id) < 1)
                                wire:click.prevent="add({{$product->id}})"
                                class="
                                    text-white bg-black px-3 rounded mt-1
                                    {{($product->remainIn($warehouse_id) >= 1) ?: 'opacity-50'}}
                                "
                            >Agregar</button>
                        </div>
                    </div>
                </x-table.simple.td>
            </x-table.simple.tr>
        @endforeach
    </x-table.simple>
    {{$products->links(data: ['scrollTo' => false])}}
    @endif

    <script>
        // Alpine components
        document.addEventListener('alpine:init', () => {
            Alpine.data('movementInput', (remain) => ({
                remainInWarehouse: remain,
                amount: 1,
            }));
        })
    </script>
</div>
