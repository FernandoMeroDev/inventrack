<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Perchas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <span class="block text-sm mt-3">Bodega</span>
                    <x-text-input :value="$inputs['warehouse']->name" disabled id="warehouseFalseInput" />

                    <div class="mt-3 flex justify-between items-end">
                        <a name="shelves" class="block text-sm">Perchas</a>
                        <x-secondary-link-button>
                            Agregar
                        </x-secondary-link-button>
                    </div>
                    <div class="mt-3">
                        {{$shelves->links()}}
                    </div>
                    <x-table.simple>
                        @foreach($shelves as $shelf)
                        <x-table.simple.tr>
                            <x-table.simple.td>
                                <a href="{{route(
                                    'inventory.edit-products', ['shelf_id' => $shelf->id]
                                )}}" class="inline-block w-full h-full">
                                    Número: {{$shelf->number}}
                                </a>
                            </x-table.simple.td>
                        </x-table.simple.tr>
                        @endforeach
                    </x-table.simple>
                    {{$shelves->links()}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>