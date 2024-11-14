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
                    <x-text-input :value="$inputs['user_name']" disabled />

                    <span class="block text-sm mt-3">Bodega</span>
                    <x-text-input :value="$inputs['warehouse_name']" disabled />

                    <span class="block text-sm mt-3">Total</span>
                    <x-text-input :value="$inputs['total']" disabled />

                    <span class="block text-sm mt-3">Intervalo</span>
                    <div class="flex items-center">
                        <x-date-input
                            disabled
                            value="{{$inputs['initial_date']}}"
                            class="px-1 w-1/2"
                        />
                        <span class="mx-1">-</span>
                        <x-date-input
                            disabled
                            value="{{$inputs['end_date']}}"
                            class="px-1 w-1/2"
                        />
                    </div>

                    <a name="products" class="block text-sm mt-3">
                        Productos
                    </a>
                    <x-accordion.simple
                        :id="'filters'"
                        :title="'Filtros'"
                        :expanded="$filters['any'] || $errors->any()"
                    >
                        <form action="{{request()->url() . '#products'}}" class="px-5 py-3 flex flex-col items-start">
                            @foreach(request()->input() as $name => $value)
                                @if($name != 'search' && $name != 'order_by' && $name != 'order')
                                <input hidden name="{{$name}}" value="{{$value}}" />
                                @endif
                            @endforeach

                            <span class="block text-sm">Buscar</span>
                            <x-text-input
                                name="search" value="{{$filters['search'] ?? null}}"
                                class="w-full" placeholder="escribe aqui..."
                            />
                            <div>
                                <x-input-error :messages="$errors->get('search')" />
                            </div>

                            <span class="block text-sm mt-1">Ordenar por</span>
                            <x-select-input name="order_by" class="w-full">
                                @php
                                    $orderByInputOptions = [
                                        'name' => 'Nombre',
                                        'amount' => 'Cantidad',
                                        'value' => 'Valor'
                                    ];
                                @endphp
                                @foreach($orderByInputOptions as $value => $label)
                                <option
                                    @selected($value === $filters['order_by'] ?? null)
                                    value="{{$value}}"
                                >{{$label}}</option>
                                @endforeach
                            </x-select-input>
                            <div>
                                <x-input-error :messages="$errors->get('order_by')" />
                            </div>

                            <span class="block text-sm mt-1">Orden</span>
                            <x-select-input name="order" class="w-full">
                                @php
                                    $orderInputOptions = ['asc' => 'Ascendente', 'desc' => 'Descendente',];
                                @endphp
                                @foreach($orderInputOptions as $value => $label)
                                <option
                                    @selected($value === $filters['order'] ?? null)
                                    value="{{$value}}"
                                >{{$label}}</option>
                                @endforeach
                            </x-select-input>
                            <div>
                                <x-input-error :messages="$errors->get('order')" />
                            </div>

                            <div class="w-full flex justify-center">
                                <x-secondary-button type="submit" class="mt-3">
                                    Aplicar
                                </x-secondary-button>
                            </div>
                        </form>
                    </x-accordion.simple>
                    <x-entities.cash-closing.show.products :$products />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
