<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inventario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('inventory.index')}}" class="p-6 flex flex-col text-gray-900">
                    <span>
                        Tipo <span class="text-red-400">*</span>
                    </span>
                    <x-table.simple>
                        <x-table.simple.tr>
                            <x-table.simple.td>
                                <input  type="radio" name="type" value="virtual" id="typeInputVirtual" required />
                                <label for="typeInputVirtual" class="inline-flex w-full h-full">
                                    Virtual
                                </label>
                            </x-table.simple.td>
                        </x-table.simple.tr>
                        <x-table.simple.tr>
                            <x-table.simple.td>
                                <input type="radio" name="type" value="physical" id="typeInputPhysical" required />
                                <label for="typeInputPhysical" class="inline-flex w-full h-full">
                                    Físico
                                </label>
                            </x-table.simple.td>
                        </x-table.simple.tr>
                    </x-table.simple>
                    <input-error :messages="$errors->get('type')" />

                    <livewire:entities.warehouse.choose :required="true" />

                    <x-primary-button class="self-center mt-8">
                        Consultar
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>