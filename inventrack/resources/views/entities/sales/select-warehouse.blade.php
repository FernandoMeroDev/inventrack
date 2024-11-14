<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bodega de venta
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('sales.set-warehouse')}}" method="POST" class="p-6 flex flex-col text-gray-900">
                    @csrf

                    <livewire:entities.warehouse.choose :required="true" />

                    <x-primary-button class="self-center mt-8">
                        Guardar
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
