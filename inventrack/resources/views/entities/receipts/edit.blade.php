<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar comprobante
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('receipts.update', $receipt->id)}}" method="POST" class="p-6 text-gray-900">                    
                    @csrf

                    @method('put')

                    <span class="block text-sm">Número de comprobante</span>
                    <x-text-input :value="$receipt->id" disabled />

                    <span class="block text-sm">Tipo de comprobante</span>
                    <x-text-input :value="ucfirst($receipt->type->label)" disabled />

                    <span class="block text-sm mt-3">Creado por</span>
                    <x-text-input :value="$receipt->user->name" disabled />

                    <span class="block text-sm mt-3">Creado en la bodega:</span>
                    <x-text-input :value="$receipt->warehouse->name" disabled />

                    @if(isset($total))
                        <span class="block text-sm mt-3">Valor toal</span>
                        <x-text-input :value="$total" disabled />
                    @endif

                    <label
                        for="commentInput"
                        class="block text-sm mt-3"
                    >Comentario:</label>
                    <x-textarea-input
                        name="comment"
                        id="commentInput"
                        max="500"
                        class="w-full h-[100px]"
                    >{{$receipt->comment}}</x-textarea-input>
                    <x-input-error :messages="$errors->get('comment')" />

                    <span class="block text-sm mt-3">Movimientos</span>
                    <x-entities.receipts.edit.movements :movements="$receipt->movements" />

                    <span class="block text-sm mt-3">Creado el</span>
                    <x-text-input :value="date('d/m/Y H\h:i', strtotime($receipt->created_at))" disabled />

                    <span class="block text-sm mt-3">Actualizado el</span>
                    <x-text-input :value="date('d/m/Y H\h:i', strtotime($receipt->updated_at))" disabled />

                    <div class="mt-3 flex justify-center">
                        <x-primary-button>
                            Guardar
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>