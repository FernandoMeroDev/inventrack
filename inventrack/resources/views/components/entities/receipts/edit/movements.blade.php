@props(['movements'])

<div>
<x-table.simple>
    @foreach($movements as $movement)
        <x-table.simple.tr>
            <x-table.simple.td>
                <div
                    x-data="{ amount: {{$movement->amount}} }"
                    class="grid grid-cols-2"
                >
                    <div
                        x-on:click.prevent="$dispatch('open-modal', 'product-modal-{{$movement->product->id}}')"
                        class="text-wrap col-span-2"
                    >
                        <input name="movement_ids[]" hidden value="{{$movement->id}}" />
                        {{$movement->product->name}}
                    </div>
                    <x-entities.products.modal
                        :name="'product-modal-' . $movement->product->id"
                        :product="$movement->product"
                        :warehouse_id="$movement->receipt->warehouse_id"
                    />
                    <div class="pr-1 col-span-1">
                        <p>Cantidad</p>
                        <x-number-input
                            x-model="amount"
                            name="amounts[]"
                            required
                            min="0"
                            max="65000"
                            class="w-full h-6 pl-1"
                        />
                    </div>
                    <div class="col-span-1">
                        <p>Precio</p>
                        <x-select-input name="sale_price_ids[]" class="w-full h-6 pl-0 pt-0 pb-0">
                            @foreach($movement->product->salePrices as $salePrice)
                                <template x-if="amount >= {{$salePrice->units_number}}">
                                    <option
                                        @selected($movement->price == $salePrice->value)
                                        value="{{$salePrice->id}}"
                                    >{{$salePrice->valueFormated()}}</option>
                                </template>
                            @endforeach
                        </x-select-input>
                    </div>
                </div>
            </x-table.simple.td>
        </x-table.simple.tr>
    @endforeach
</x-table.simple>

<x-input-error :messages="$errors->get('movement_ids')" />
<x-input-error :messages="$errors->get('movement_ids.*')" />
<x-input-error :messages="$errors->get('amounts')" />
<x-input-error :messages="$errors->get('amounts.*')" />
<x-input-error :messages="$errors->get('sale_price_ids')" />
<x-input-error :messages="$errors->get('sale_price_ids.*')" />
</div>