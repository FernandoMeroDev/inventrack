<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vender
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('sales.store')}}" method="POST" class="p-6 flex flex-col text-gray-900">
                    @csrf

                    <label for="issuanceDateInput">
                        Fecha de emisión <span class="text-red-500">*</span>
                    </label>
                    @php
                        $date = date('Y-m-d');
                        $aWeekAgoDate = date('Y-m-d', mktime(date('H'), day: date('j') - 7));
                    @endphp
                    <x-date-input
                        value="{{old('issuance_date', $date)}}"
                        name="issuance_date"
                        id="issuanceDateInput"
                        required min="{{$aWeekAgoDate}}" max="{{$date}}"
                    />
                    <x-input-error :messages="$errors->get('issuance_date')" />

                    <span>
                        Bodega <span class="text-red-500">*</span>
                    </span>
                    <input name="warehouse_id" hidden value="{{$warehouse->id}}" />
                    <x-text-input disabled :value="$warehouse->name" />
                    <x-secondary-link-button
                        href="{{route('sales.select-warehouse')}}" class="self-center mt-1"
                    >cambiar
                    </x-secondary-link-button>
                    <x-input-error :messages="$errors->get('warehouse_id')" />

                    <label for="commentInput">
                        Comentario
                    </label>
                    <x-textarea-input name="comment" id="commentInput"
                    >{{old('comment')}}</x-textarea-input>
                    <x-input-error :messages="$errors->get('comment')" />

                    <livewire:entities.sales.products :warehouse_id="$warehouse->id" />

                    <x-primary-button class="self-center mt-8">
                        Guardar
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>