<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Producto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-sm">Nombre</h3>
                    <p class="mb-3 text-md font-bold">
                        {{$product->name}}
                    </p>

                    <div class="mt-3 flex justify-center">
                        <x-entities.products.image :$product />
                    </div>

                    <h3 class="text-sm mt-3">Srock Mínimo</h3>

                    <x-number-input
                        id="minStockFalseInput"
                        readonly value="{{$product->min_stock}}"
                    />

                    <h3 class="text-sm mt-3">Precios de venta</h3>
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

                    <div class="flex justify-between">
                    <x-secondary-link-button :href="route('products.edit', $product->id)">
                        Editar
                    </x-secondary-link-button>
                    <x-danger-button x-data x-on:click="$dispatch('open-modal', 'delete-modal')">
                        Eliminar
                    </x-danger-button>
                    <x-modal :name="'delete-modal'">
                        <form action="{{route('products.destroy', $product->id)}}" method="POST" class="p-2">
                            @csrf
                            @method('delete')
                            <h2 class="text-center text-md font-bold">¿Seguro?</h2>
                            <div class="mt-3 flex justify-evenly">
                            <x-danger-button type="sumbit">
                                Si
                            </x-danger-button>
                            <x-secondary-button x-on:click.prevent="$dispatch('close')">
                                No
                            </x-secondary-button>
                            </div>
                        </form>
                    </x-modal>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>