<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{route('sales.create')}}" class="block mb-2">
                        <strong>Vender</strong>
                    </a>

                    <a href="{{route('purchases.create')}}" class="block mb-2">
                        <strong>Comprar</strong>
                    </a>

                    <a href="{{route('cash-closing.ask')}}" class="block mb-2">
                        <strong>Cierre de caja</strong>
                    </a>

                    <a href="{{route('receipts.ask')}}" class="block mb-2">
                        <strong>Comprobantes</strong>
                    </a>

                    <a href="#" class="block mt-2">
                        <strong>Link</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
