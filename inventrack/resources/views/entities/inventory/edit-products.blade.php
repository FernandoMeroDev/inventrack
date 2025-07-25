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
                    <x-secondary-link-button
                        href="{{route('inventory.edit', ['warehouse_id' => $inputs['shelf']->warehouse->id])}}" class="self-center mt-1"
                    >Regresar</x-secondary-link-button>

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
                    />

                    <a name="products" class="mt-3 block text-sm">Productos</a>
                    <livewire:entities.inventory.edit-products.products
                        :shelf_id="$inputs['shelf']->id"
                        :level_id="$inputs['level']->id"
                    />

                    <div class="mt-8">
                    <x-table.simple :col-tags="['Percha ' . $inputs['shelf']->number]">
                        @foreach($inputs['shelf']->levels->reverse() as $level)
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
                </form>
            </div>

            <div class="flex justify-between mt-4 p-6 pt-0">
                <x-secondary-link-button href="{{route('shelves.edit', $inputs['shelf']->id)}}">
                    Editar
                </x-secondary-link-button>
                <form action="{{route('shelves.destroy', $inputs['shelf']->id)}}" method="POST">
                    @csrf
                    @method('delete')
                    <x-danger-button type="submit">
                        Eliminar
                    </x-danger-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>