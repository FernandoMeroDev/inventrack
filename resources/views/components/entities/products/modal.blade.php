@props([
    'name',
    'product',
    'warehouseId' => null,
    'receipts' => null,
    'show' => false,
    'maxWidth' => '2xl'
])

@use('App\Models\Warehouse')

<x-modal {{$attributes}} :$name :$show :max-with="$maxWidth">
<div class="p-3">
    <div class="flex justify-center">
        <x-secondary-button x-on:click.prevent="$dispatch('close')"
        >Regresar</x-secondary-button>
    </div>

    <h3 class="text-wrap mt-3">
        <a href="{{route('products.show', $product->id)}}">
            {{$product->name}}
        </a>
    </h3>

    <div>
    <img
        class="max-w-full border-gray-300 rounded-md"
        src="{{asset("storage/$product->id.jpg")}}"
        alt="Imagen de Producto"
    >
    </div>

    @if($receipts)
    <h3 class="font-bold mt-3">Comprobantes</h3>
    <x-table.simple>
        @foreach($product->receipts as $receipt)
            <x-table.simple.tr>
                <x-table.simple.td>
                    <a
                        href="{{route('receipts.show', $receipt->id)}}"
                        class="text-blue-400 underline"
                    >
                        Número: {{$receipt->id}}
                    </a>
                </x-table.simple.td>
            </x-table.simple.tr>
        @endforeach
    </x-table.simple>
    @endif
    
    @if($warehouseId)
        @php
            $warehouse = Warehouse::find($warehouseId);
        @endphp
        <h3 class="font-bold mt-3">Quedan</h3>
        <x-number-input
            :disabled="true"
            value="{{$product->remainIn($warehouseId)}}"
            class="w-full h-6"
            id="remain"
        />
        <p>
            En {{$warehouse->name}}.
        </p>
    @endif

    <h3 class="font-bold mt-3">Precios de venta</h3>
    <x-table.simple>
        @foreach($product->salePrices as $salePrice)
            <x-table.simple.tr>
                <x-table.simple.td>
                    Por {{$salePrice->units_number}}
                    {{$salePrice->units_number == 1 ? 'unidad' : 'unidades'}}
                </x-table.simple.td>
                <x-table.simple.td>
                    {{$salePrice->valueFormated()}}
                </x-table.simple.td>
            </x-table.simple.tr>
        @endforeach
    </x-table.simple>

    @if($warehouseId)
    <h3 class="font-bold mt-3">Ubicaciones Físicas</h3>
    <p>
        En {{$warehouse->name}}.
    </p>
    <x-table.simple :col-tags="['Percha', 'Piso', 'Cantidad']">
        @forelse($product->levelsIn($warehouseId) as $level)
        <x-table.simple.tr>
            <x-table.simple.td>
                {{$level->shelf_number}}
            </x-table.simple.td>
            <x-table.simple.td>
                {{$level->number}}
            </x-table.simple.td>
            <x-table.simple.td>
                {{$level->product_amount}}
                {{$level->product_amount == 1 ? 'unidad' : 'unidades'}}
            </x-table.simple.td>
        </x-table.simple.tr>
        @empty
        <x-table.simple.tr>
            <x-table.simple.td>
                No encontrado
            </x-table.simple.td>
            <x-table.simple.td></x-table.simple.td>
            <x-table.simple.td></x-table.simple.td>
        </x-table.simple.tr>
        @endforelse
    </x-table.simple>
    @endif

    <div class="flex justify-center mt-3">
        <x-secondary-button x-on:click.prevent="$dispatch('close')"
        >Regresar</x-secondary-button>
    </div>
</div>
</x-modal>