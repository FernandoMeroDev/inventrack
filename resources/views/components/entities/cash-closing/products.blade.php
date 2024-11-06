@props(['products'])

<x-table.simple>
    @foreach($products as $product)
    <x-table.simple.tr>
        <x-table.simple.td>
            <div class="grid grid-cols-2">
                <div
                    x-data x-on:click="$dispatch('open-modal', 'product-modal-{{$product['id']}}')"
                    class="text-wrap col-span-2"
                >
                    {{$product['name']}}
                </div>
                <x-entities.cash-closing.product-modal
                    :name="'product-modal-' . $product['id']"
                    :$product
                />
                <div class="col-span-1 pr-1">
                    <p class="font-bold">Cantidad</p>
                    <x-number-input
                        value="{{$product['amount']}}" disabled class="w-full h-6"
                    />
                </div>
                <div class="col-span-1">
                    <p class="font-bold">Valor</p>
                    <x-number-input
                        value="{{'$' . number_format($product['value'], 2, ',', ' ')}}" disabled class="w-full h-6"
                    />
                </div>
            </div>
        </x-table.simple.td>
    </x-table.simple.tr>
    @endforeach
</x-table.simple>
<div class="overflow-x-auto">
    {{$products->links(data: ['scrollTo' => false])}}
</div>
