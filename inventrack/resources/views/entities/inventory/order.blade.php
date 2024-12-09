<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pedido
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a name="products" class="block text-sm mt-3">Productos</a>
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
                                max="255"
                            />
                            <div>
                                <x-input-error :messages="$errors->get('search')" />
                            </div>

                            <span class="block text-sm mt-1">Ordenar por</span>
                            <x-select-input name="order_by" class="w-full">
                                @php
                                    $orderByInputOptions = [
                                        'name' => 'Nombre',
                                        'lack' => 'Pedir',
                                        'existences' => 'Cantidad',
                                        'move' => 'Mover'
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
                    <div class="mt-3">
                        {{$products->links(data: ['scrollTo' => false])}}
                    </div>
                    <x-table.simple>
                        @foreach($products as $product)
                        <x-table.simple.tr>
                            <x-table.simple.td>
                                <div class="grid grid-cols-2">
                                    <div
                                        x-data x-on:click="$dispatch('open-modal', 'product-modal-{{$product['id']}}')"
                                        class="text-wrap col-span-2"
                                    >
                                        {{$product->name}}
                                    </div>
                                    <x-entities.inventory.index.products-modal
                                        :name="'product-modal-' . $product->id"
                                        :$product
                                    />
                                    <div class="col-span-1 pr-1">
                                        <p class="font-bold">Pedir</p>
                                        <x-number-input
                                            value="{{$product->lack}}" disabled class="w-full h-6"
                                        />
                                    </div>
                                    <div class="col-span-1 pr-1">
                                        <p class="font-bold">Cantidad</p>
                                        <x-number-input
                                            value="{{$product->existences}}" disabled class="w-full h-6"
                                        />
                                    </div>
                                    <div class="col-span-2">
                                        <p class="font-bold">Mover</p>
                                        <x-number-input
                                            value="{{$product->move}}" disabled class="w-full h-6"
                                        />
                                        @if($product->move > 0)
                                        <p>
                                            Desde {{$product->move_from}} hacia {{$product->move_to}}.
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </x-table.simple.td>
                        </x-table.simple.tr>
                        @endforeach
                    </x-table.simple>
                    {{$products->links(data: ['scrollTo' => false])}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>