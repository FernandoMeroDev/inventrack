<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cierre de caja
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <span class="block text-sm">Usuario</span>
                    <x-text-input :value="$data['user_name']" disabled />

                    <span class="block text-sm mt-3">Bodega</span>
                    <x-text-input :value="$data['warehouse_name']" disabled />

                    <span class="block text-sm mt-3">Total</span>
                    <x-text-input :value="$data['total']" disabled />

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

                    <span class="block text-sm mt-3">Productos</span>
                    <x-entities.cash-closing.products :$products />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
