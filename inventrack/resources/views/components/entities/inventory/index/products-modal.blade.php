@props([
    'name',
    'product',
    'show' => false,
    'maxWidth' => '2xl'
])

<x-modal {{$attributes}} :$name :$show :max-with="$maxWidth">
<div class="p-3">
    <div class="flex justify-center">
        <x-secondary-button x-on:click.prevent="$dispatch('close')"
        >Regresar</x-secondary-button>
    </div>

    <h3 class="text-wrap mt-3">
        <a class="text-lg font-bold" href="{{route('products.show', $product->id)}}">
            {{$product->name}}
        </a>
    </h3>

    <div class="flex justify-center">
        <x-entities.products.image :$product />
    </div>

    <x-table.simple>
        @foreach($product->warehouses_inventory as $warehouse)
        <x-table.simple.tr>
            <x-table.simple.td>
                <div class="grid grid-cols-2">
                    <div class="text-wrap col-span-2">
                        {{$warehouse->name}}
                    </div>
                    <div class="col-span-1 pr-1">
                        <p class="font-bold">Cantidad</p>
                        <x-number-input
                            value="{{$warehouse->existences}}" disabled class="w-full h-6"
                        />
                    </div>
                    <div class="col-span-1">
                        <p class="font-bold">Mínimo</p>
                        <x-number-input
                            value="{{$warehouse->min_stock}}" disabled class="w-full h-6"
                        />
                    </div>
                    <div class="col-span-1 pr-1">
                        <p class="font-bold">Faltan</p>
                        <x-number-input
                            value="{{$warehouse->lack}}" disabled class="w-full h-6"
                        />
                    </div>
                    <div class="col-span-1">
                        <p class="font-bold">Sobran</p>
                        <x-number-input
                            value="{{$warehouse->remain}}" disabled class="w-full h-6"
                        />
                    </div>
                </div>
            </x-table.simple.td>
        </x-table.simple.tr>
        @endforeach
    </x-table.simple>

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

    <h3 class="font-bold mt-3">Ubicaciones Físicas</h3>
    @foreach($product->warehouses_inventory as $warehouse)
    <p>
        En {{$warehouse->name}}.
    </p>
    <x-table.simple :col-tags="['Percha', 'Piso', 'Cantidad']">
        @forelse($product->levelsIn($warehouse->id) as $level)
        <x-table.simple.tr>
            <x-table.simple.td>
                @if($level->shelf_refrigerator)
                <span class="text-blue-300">
                    Refri {{$level->shelf_number}}
                </span>
                @else
                    Percha {{$level->shelf_number}}
                @endif
            </x-table.simple.td>
            <x-table.simple.td>
                @if($level->number > 0)
                    Piso {{$level->number}}
                @else
                    Alrededores
                @endif
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
    @endforeach

    <div class="flex justify-center mt-3">
        <x-secondary-button x-on:click.prevent="$dispatch('close')"
        >Regresar</x-secondary-button>
    </div>
</div>
</x-modal>