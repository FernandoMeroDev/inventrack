<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar producto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form 
                    action="{{route('products.update', $product->id)}}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="text-gray-900 p-6 space-y-3"
                >
                    @csrf
                    @method('put')

                    <div>
                    <label for="nameInput" class="block">
                        Nombre
                    </label>
                    <x-text-input
                        name="name" id="nameInput" autocomplete
                        required maxlength="255" value="{{old('name', $product->name)}}"
                    />
                    <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div>
                    <label for="imageInput" class="block">
                        Imagen
                    </label>
                    <x-file-input type="file" name="image" id="imageInput" />
                    <x-input-error :messages="$errors->get('image')" />
                    </div>

                    <div class="flex justify-center">
                        <x-entities.products.image :$product />
                    </div>

                    <div>
                        <label for="removeImageInput" class="flex items-center">
                            <input
                                name="remove_image"
                                type="checkbox"
                                id="removeImageInput"
                                class="mr-1 rounded"
                            />
                            Quitar imagen
                        </label>
                        <x-input-error :messages="$errors->get('remove_image')" />
                    </div>

                    <div>
                    <label for="minStockInput" class="block">
                        Stock Mínimo
                    </label>
                    <x-table.simple>
                        @foreach($product->warehouses as $key => $warehouse)
                            <x-table.simple.tr>
                                <x-table.simple.td>
                                    {{$warehouse->name}}:
                                </x-table.simple.td>
                                <x-table.simple.td>
                                    <x-number-input
                                        name="min_stocks[{{$warehouse->id}}]"
                                        id="minStockInput{{$key}}"
                                        required min="0" max="255"
                                        value="{{old('min_stocks.'.$warehouse->id, $warehouse->pivot->min_stock)}}"
                                    />
                                </x-table.simple.td>
                            </x-table.simple.tr>
                        @endforeach
                    </x-table.simple>
                    <x-input-error :messages="$errors->get('min_stock')" />
                    <x-input-error :messages="$errors->get('min_stock.*')" />
                    </div>

                    <div
                        x-data="salePricesInput({{$product->salePrices->toJson()}})"
                    >
                    <span class="block">Precios de venta</span>
                    <x-table.simple>
                        <template x-for="input of inputs" x-bind:key="input.id">
                            <x-table.simple.tr>
                                <x-table.simple.td>
                                    <div class="grid">
                                    <div>
                                        <label x-bind:for="`unitsNumberInput${input.id}`" class="block">
                                            Unidades
                                        </label>
                                        <x-number-input
                                            name="units_numbers[]"
                                            x-bind:value="input.units_number"
                                            required min="1" max="255"
                                            class="w-full"
                                            x-bind:id="`unitsNumberInput${input.id}`"
                                        />
                                    </div>
                                    <div>
                                        <label x-bind:for="`priceInput${input.id}`" class="block">
                                            Precio ($)
                                        </label>
                                        <x-number-input
                                            name="prices[]"
                                            x-bind:value="input.value"
                                            required min="0.01" max="9999.999999"
                                            step="0.000001"
                                            class="w-full"
                                            x-bind:id="`priceInput${input.id}`"
                                        />
                                    </div>
                                    </div>
                                </x-table.simple.td>
                            </x-table.simple.tr>
                        </template>
                        <x-table.simple.tr>
                            <x-table.simple.td>
                            <div class="flex justify-between">
                                <x-entities.products.sale-price-button
                                    x-on:click="push()"
                                    class="bg-green-500 hover:bg-green-400"
                                >
                                    <x-icons.cross color="#fff" class="w-3 h-3" />
                                </x-entities.products.sale-price-button>
                                <x-entities.products.sale-price-button
                                    x-on:click="pop()"
                                    class="bg-red-500 hover:bg-red-400"
                                >
                                    <x-icons.cross color="#fff" class="w-3 h-3 rotate-45" />
                                </x-entities.products.sale-price-button>
                            </div>
                            </x-table.simple.td>
                        </x-table.simple.tr>
                    </x-table.simple>
                    <x-input-error :messages="$errors->get('units_numbers')" />
                    <x-input-error :messages="$errors->get('units_numbers.*')" />
                    <x-input-error :messages="$errors->get('prices')" />
                    <x-input-error :messages="$errors->get('prices.*')" />
                    </div>

                    <div class="flex justify-center">
                        <x-primary-button type="submit">
                            Guardar
                        </x-primary-button>
                    </div>
                </form>

                <script>
                    // Alpine components
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('salePricesInput', (salePrices) => ({
                            inputs: salePrices,

                            push(){
                                lastId = this.inputs.length > 0
                                    ? this.inputs.toReversed()[0].id
                                    : null;
                                nextId = (lastId === null) ? 1 : (lastId + 1);
                                this.inputs.push({id: nextId, units_number: 1, value: 0});
                            },

                            pop(){
                                this.inputs.pop();
                            }
                        }));
                    })
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
