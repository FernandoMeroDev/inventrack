@props([
    'name',
    'product',
    'warehouseId' => null,
    'receipts' => null,
    'show' => false,
    'maxWidth' => '2xl'
])

<x-modal {{$attributes}} :$name :$show :max-with="$maxWidth">
<div class="p-3">
    <div class="flex justify-center">
        <x-secondary-button x-on:click.prevent="$dispatch('close')"
        >Regresar</x-secondary-button>
    </div>

    <h3 class="text-wrap mt-3">{{$product->name}}</h3>

    <div>
    <img
        class="max-w-full border-gray-300 rounded-md"
        src="{{asset("storage/$product->id.jpg")}}"
        alt="Imagen de Producto"
    >
    </div>

    @if($receipts)
    <p class="font-bold mt-3">Comprobantes</p>
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
    <p class="font-bold mt-3">Quedan</p>
    <x-number-input
        :disabled="true"
        value="{{$product->remainIn($warehouseId)}}"
        class="w-full h-6"
        id="remain"
    />
    @endif

    <p class="font-bold mt-3">Precios de venta</p>
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

    <p class="font-bold mt-3">Ubicaciones</p>
    <x-table.simple :col-tags="['Percha', 'Piso', 'Cantidad']">
        <x-table.simple.tr>
            <x-table.simple.td>
                A1
            </x-table.simple.td>
            <x-table.simple.td>
                2
            </x-table.simple.td>
            <x-table.simple.td>
                5 unidades
            </x-table.simple.td>
        </x-table.simple.tr>
        <x-table.simple.tr>
            <x-table.simple.td>
                A3
            </x-table.simple.td>
            <x-table.simple.td>
                1
            </x-table.simple.td>
            <x-table.simple.td>
                10 unidades
            </x-table.simple.td>
        </x-table.simple.tr>
    </x-table.simple>

    <div class="flex justify-center mt-3">
        <x-secondary-button x-on:click.prevent="$dispatch('close')"
        >Regresar</x-secondary-button>
    </div>
</div>
</x-modal>