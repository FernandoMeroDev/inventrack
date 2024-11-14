<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agregar producto
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form
                    action="{{route('products.store')}}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="text-gray-900 p-6 space-y-3"
                >
                    @csrf

                    <div>
                    <label for="nameInput" class="block">
                        Nombre
                    </label>
                    <x-text-input
                        name="name" id="nameInput" autocomplete
                        required maxlength="255" value="{{old('name')}}"
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

                    <div>
                    <label for="minStockInput" class="block">
                        Stock Mínimo
                    </label>
                    <x-number-input
                        name="min_stock" id="minStockInput"
                        required min="1" max="255" value="{{old('min_stock')}}"
                    />
                    <x-input-error :messages="$errors->get('min_stock')" />
                    </div>

                    <div
                        x-data="salePricesInput"
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
                                            value="1"
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
                        Alpine.data('salePricesInput', () => ({
                            inputs: [],

                            push(){
                                lastId = this.inputs.length > 0
                                    ? this.inputs.toReversed()[0].id
                                    : null;
                                nextId = (lastId === null) ? 1 : (lastId + 1);
                                this.inputs.push({id: nextId});
                                console.log(this.inputs);
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