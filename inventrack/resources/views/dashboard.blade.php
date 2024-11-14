<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <img class="w-full fill-current text-gray-500" src="/storage/logo_hd.jpg" alt="Logo">

                <div class="
                    p-4 text-gray-900 
                    grid gap-3 justify-center justify-items-center
                    grid-cols-2 md:grid-cols-3 lg:grid-cols-6
                ">

                    @php
                        $user = auth()->user();
                    @endphp
                    @if($user->id == 1)
                        <x-dashboard.products :href="route('products.index')">
                            Productos
                        </x-dashboard.products>

                        <x-dashboard.purchases :href="route('purchases.create')">
                            Comprar
                        </x-dashboard.purchases>

                        <x-dashboard.inventory :href="route('inventory.ask')">
                            Inventario
                        </x-dashboard.inventory>
                    @endif

                    <x-dashboard.sales :href="route('sales.create')">
                        Vender
                    </x-dashboard.sales>

                    <x-dashboard.cash-closing :href="route('cash-closing.ask')">
                        Cierre de caja
                    </x-dashboard.cash-closing>

                    <x-dashboard.receipts :href="route('receipts.ask')">
                        Comprobantes
                    </x-dashboard.receipts>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
