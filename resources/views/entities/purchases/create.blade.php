<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comprar
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('purchases.store')}}" method="POST" class="p-6 flex flex-col text-gray-900">
                    @csrf

                    <livewire:entities.warehouse.choose
                        :required="true" :default="$warehouse_id ?? false"
                    />

                    <label for="commentInput">
                        Comentario
                    </label>
                    <x-textarea-input name="comment" id="commentInput"
                    >{{old('comment')}}</x-textarea-input>
                    <x-input-error :messages="$errors->get('comment')" />

                    <livewire:entities.purchases.products />

                    <x-primary-button class="self-center mt-8">
                        Guardar
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>