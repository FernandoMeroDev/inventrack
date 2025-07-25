<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Percha
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form
                    action="{{route('shelves.update', $shelf->id)}}"
                    method="POST"
                    class="p-6 text-gray-900"
                >
                    @csrf
                    @method('put')

                    <div>
                    <label for="numberInput" class="block">
                        Número
                    </label>
                    <x-number-input
                        name="number" id="numberInput"
                        required min="1" max="255" value="{{old('number', $shelf->number)}}"
                    />
                    <x-input-error :messages="$errors->get('number')" />
                    </div>

                    <div
                        x-data="levelsInput({{$shelf->levels->toJson()}})"
                    >
                        <span class="block">Pisos</span>
                        <x-table.simple>
                            <template x-for="input of inputs" x-bind:key="input.id">
                                <x-table.simple.tr>
                                    <x-table.simple.td>
                                        <div class="grid">
                                        <div>
                                            <label x-bind:for="`levelInput${input.id}`" class="block">
                                                Piso
                                            </label>
                                            <x-number-input
                                                name="levels[]"
                                                x-bind:value="input.number"
                                                readonly
                                                class="w-full"
                                                x-bind:id="`levelInput${input.id}`"
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
                        <x-input-error :messages="$errors->get('levels')" />
                        <x-input-error :messages="$errors->get('levels.*')" />
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
                        Alpine.data('levelsInput', (levels) => ({
                            inputs: levels,

                            push(){
                                lastId = this.inputs.length > 0
                                    ? this.inputs.toReversed()[0].id
                                    : null;
                                nextId = (lastId === null) ? 1 : (lastId + 1);
                                this.inputs.push({id: nextId, number: this.inputs.length});
                            },

                            pop(){
                                if(this.inputs.length > 2) this.inputs.pop();
                            }
                        }));
                    })
                </script>
            </div>
        </div>
    </div>
</x-app-layout>