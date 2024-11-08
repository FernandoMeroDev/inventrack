<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Percha
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form
                    action="{{route('inventory.update-products', $inputs['level']->id)}}"
                    method="POST"
                    class="p-6 text-gray-900"
                >
                    @csrf
                    @method('put')

                    <span class="block text-sm mt-3">Bodega</span>
                    <x-text-input
                        :value="$inputs['shelf']->warehouse->name" 
                        disabled
                        id="warehouseFalseInput" 
                    />

                    <span class="block text-sm mt-3">Percha</span>
                    <x-text-input
                        :value="'Número ' . $inputs['shelf']->number"
                        disabled
                        id="shelfFalseInput"
                    />
                    <x-text-input
                        :value="'Piso ' . $inputs['level']->number"
                        disabled
                        class="mt-1"
                        id="levelFalseInput"
                    />

                    <x-entities.inventory.edit-products.navigation-links
                        :shelf="$inputs['shelf']"
                        :level="$inputs['level']"
                        :previous="['pito']"
                    />

                    <a name="products" class="mt-3 block text-sm">Productos</a>
                    <livewire:entities.inventory.edit-products.products
                        :shelf_id="$inputs['shelf']->id"
                        :level_id="$inputs['level']->id"
                    />

                    <div class="mt-8">
                    <x-table.simple :col-tags="['Percha Número ' . $inputs['shelf']->number]">
                        @foreach($inputs['shelf']->levels as $level)
                        <x-table.simple.tr>
                            <x-table.simple.td>
                                <a
                                    href="{{route(
                                        'inventory.edit-products', [
                                            'shelf_id' => $inputs['shelf']->id,
                                            'level_number' => $level->number
                                        ]
                                    )}}"
                                    class="inline-block w-full h-full"
                                >
                                    Piso: {{$level->number}}
                                </a>
                            </x-table.simple.td>
                        </x-table.simple.tr>
                        @endforeach
                    </x-table.simple>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>