<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Productos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-3 flex justify-between items-end">
                        <a name="products" class="block text-sm">Productos</a>
                        <x-secondary-link-button
                            :href="route('products.create')"
                        >
                            Agregar
                        </x-secondary-link-button>
                    </div>
                    <x-accordion.simple
                        :id="'filters'"
                        :title="'Filtros'"
                        :expanded="$filters['any'] || $errors->any()"
                    >
                        <form action="{{request()->url() . '#products'}}" class="px-5 py-3 flex flex-col items-start">
                            @foreach(request()->input() as $name => $value)
                                @if($name != 'search')
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
                    <x-table.simple :col-tags="['Nombre']">
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
                                    <x-entities.products.modal
                                        :name="'product-modal-' . $product->id"
                                        :$product
                                    />
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