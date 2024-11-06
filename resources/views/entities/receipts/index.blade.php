<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comprobantes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">                    
                    <span class="block text-sm">Tipo</span>
                    <x-text-input :value="ucfirst($data['type_name'])" disabled />

                    <span class="block text-sm mt-3">Usuario</span>
                    <x-text-input :value="$data['user_name']" disabled />

                    <span class="block text-sm mt-3">Bodega</span>
                    <x-text-input :value="$data['warehouse_name']" disabled />

                    <span class="block text-sm mt-3">Intervalo</span>
                    <div class="flex items-center">
                        <x-date-input
                            disabled
                            value="{{$data['initial_date']}}"
                            class="px-1 w-1/2"
                        />
                        <span class="mx-1">-</span>
                        <x-date-input
                            disabled
                            value="{{$data['end_date']}}"
                            class="px-1 w-1/2"
                        />
                    </div>

                    <a name="receipts" class="block text-sm mt-3">Comprobantes</a>
                    <x-entities.receipts.index.products :$receipts />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>