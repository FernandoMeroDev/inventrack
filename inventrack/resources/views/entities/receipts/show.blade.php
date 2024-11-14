<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comprobante
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">                    
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

                    @if($receipt->comment)
                        <span class="block text-sm mt-3">Comentario:</span>
                        <x-textarea-input class="w-full h-[150px]" disabled>{{$receipt->comment}}</x-textarea-input>
                    @endif

                    <span class="block text-sm mt-3">Movimientos</span>
                    <x-entities.receipts.show.movements :movements="$receipt->movements" />

                    <span class="block text-sm mt-3">Creado el</span>
                    <x-text-input :value="date('d/m/Y H\h:i', strtotime($receipt->created_at))" disabled />

                    <span class="block text-sm mt-3">Actualizado el</span>
                    <x-text-input :value="date('d/m/Y H\h:i', strtotime($receipt->updated_at))" disabled />

                    @if($receipt->type->name == 'sale' && !$receipt->consolidated)
                    <div class="mt-3 flex justify-center">
                        <x-primary-link-button :href="route('receipts.edit', $receipt->id)">
                            Editar
                        </x-primary-link-button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>