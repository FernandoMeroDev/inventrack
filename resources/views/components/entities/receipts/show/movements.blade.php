@props(['movements'])

<x-table.simple>
    @foreach($movements as $movement)
    <x-table.simple.tr>
        <x-table.simple.td>
            <div class="grid grid-cols-3">
                <div
                    x-data x-on:click="$dispatch('open-modal', 'product-modal-{{$movement->product->id}}')"
                    class="text-wrap col-span-3"
                >
                    <h3 class="font-bold">Producto</h3>
                    <p>{{$movement->product->name}}</p>
                </div>
                <x-entities.products.modal
                    :name="'product-modal-' . $movement->product->id"
                    :product="$movement->product"
                    :warehouse-id="$movement->receipt->warehouse->id"
                />
                <div class="text-wrap col-span-1 pr-1">
                    <h3 class="font-bold">Cantidad</h3>
                    <p>
                        {{$movement->amount}}
                    </p>
                </div>
                <div class="col-span-1">
                    <h3 class="font-bold">Precio</h3>
                    <p>
                        {{'$' . number_format($movement->price, 2, ',', ' ')}}
                    </p>
                </div>
                <div class="col-span-1">
                    <h3 class="font-bold">Total</h3>
                    <p>
                        {{'$' . number_format(
                            $movement->price * $movement->amount, 2, ',', ' '
                        )}}
                    </p>
                </div>
            </div>
        </x-table.simple.td>
    </x-table.simple.tr>
    @endforeach
</x-table.simple>