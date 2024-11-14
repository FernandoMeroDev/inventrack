<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Auditar
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <span class="block text-sm mt-3">Bodega</span>
                    <x-text-input :value="$warehouse->name" disabled id="warehouseFalseInput" />

                    <a name="products" class="block text-sm mt-3">
                        Discrepancias
                    </a>
                    <x-entities.inventory.audit.filters :$filters />
                    <div class="overflow-x-auto mt-3">
                        {{$products->links(data: ['scrollTo' => false])}}
                    </div>
                    <x-table.simple>
                        @foreach($products as $product)
                        <x-table.simple.tr>
                            <x-table.simple.td>
                                <div class="grid grid-cols-2">
                                    <div
                                        x-data x-on:click="$dispatch('open-modal', 'product-modal-{{$product->id}}')"
                                        class="text-wrap col-span-2"
                                    >
                                        {{$product->name}}
                                    </div>
                                    <x-entities.products.modal
                                        :name="'product-modal-' . $product->id"
                                        :$product
                                        :warehouse-id="$warehouse->id"
                                    />
                                    <div class="col-span-1 pr-1">
                                        <p class="font-bold">Virtual</p>
                                        <x-number-input
                                            value="{{$product->virtualExistences}}" disabled class="w-full h-6"
                                        />
                                    </div>
                                    <div class="col-span-1">
                                        <p class="font-bold">Físico</p>
                                        <x-number-input
                                            value="{{$product->physicalExistences}}" disabled class="w-full h-6"
                                        />
                                    </div>
                                    <div class="col-span-2">
                                        <p class="font-bold">Diferencia</p>
                                        <x-number-input
                                            value="{{$product->difference}}" disabled class="w-full h-6"
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
                    @if(!$anyDiscrepancy)
                        <p class="text-green-500">¡Enhorabuena! No hay discrepancias.</p>
                    @endif

                    <form action="{{route('inventory.audit', $warehouse->id)}}" method="POST">
                        @csrf

                        <h3 class="text-md font-bold mt-8">Acciones</h3>
                        <span class="block text-sm">
                            Ventas por consolidar
                        </span>
                        <x-number-input
                            disabled
                            :value="$unconsolidated_count"
                            id="unconsolidatedFalseInput"
                        />

                        <div class="flex mt-4">
                        @if($anyDiscrepancy)
                            @if($unconsolidated_count > 0)
                            <x-primary-button>
                                Calibrar y consolidar
                            </x-primary-button>
                            @else
                            <x-primary-button>
                                Calibrar
                            </x-primary-button>
                            @endif
                        @else
                            @if($unconsolidated_count > 0)
                            <x-primary-button>
                                Consolidar
                            </x-primary-button>
                            @else
                            <x-primary-button disabled class="opacity-50">
                                Calibrar
                            </x-primary-button>
                            @endif
                        @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
