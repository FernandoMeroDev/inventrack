<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comprobantes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-sm mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{route('receipts.index')}}" class="p-6 flex flex-col text-gray-900">
                    <span>
                        Tipo <span class="text-red-400">*</span>
                    </span>
                    <x-table.simple>
                        @foreach($receiptTypes as $type)
                        <x-table.simple.tr>
                            <x-table.simple.td>
                                <input
                                    type="radio"
                                    name="type_id"
                                    value="{{$type->id}}"
                                    id="typeInput{{$type->id}}"
                                    required
                                />
                                <label
                                    for="typeInput{{$type->id}}"
                                    class="inline-flex w-full h-full"
                                >{{ucfirst($type->label)}}</label>
                            </x-table.simple.td>
                        </x-table.simple.tr>
                    @endforeach
                    </x-table.simple>

                    <livewire:entities.users.choose :default="'all'" :required="true" :all="true" />

                    <livewire:entities.warehouse.choose :default="'all'" :required="true" :all="true" />

                    <span>
                        Intervalo <span class="text-red-400">*</span>
                    </span>
                    @php
                        $date = date('Y-m-d');
                        $aWeekAgoDate = date('Y-m-d', mktime(date('H'), day: date('j') - 7));
                        $aMonthAgoDate = date('Y-m-d', mktime(date('H'), month: date('n') - 1));
                    @endphp
                    <div class="flex items-center">
                        <x-date-input
                            name="initial_date"
                            value="{{$aWeekAgoDate}}"
                            min="{{$aMonthAgoDate}}"
                            max="{{$date}}"
                            class="px-1 w-1/2"
                        />
                        <span class="mx-1">-</span>
                        <x-date-input
                            name="end_date"
                            value="{{$date}}"
                            min="{{$aMonthAgoDate}}"
                            max="{{$date}}"
                            class="px-1 w-1/2"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('initial_date')" />
                    <x-input-error :messages="$errors->get('end_date')" />

                    <x-primary-button class="self-center mt-8">
                        Consultar
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>