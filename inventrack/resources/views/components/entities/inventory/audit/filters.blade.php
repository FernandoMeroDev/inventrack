@props(['filters'])

<x-accordion.simple
    :id="'filters'"
    :title="'Filtros'"
    :expanded="$filters['any'] || $errors->any()"
>
    <form action="{{request()->url() . '#products'}}" class="px-5 py-3 flex flex-col items-start">
        @foreach(request()->input() as $name => $value)
            @if($name != 'search' && $name != 'order_by' && $name != 'order')
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

        <span class="block text-sm mt-1">Ordenar por</span>
        <x-select-input name="order_by" class="w-full">
            @php
                $orderByInputOptions = [
                    'name' => 'Nombre',
                    'virtualExistences' => 'Virtual',
                    'physicalExistences' => 'Físico',
                    'difference' => 'Diferencia'
                ];
            @endphp
            @foreach($orderByInputOptions as $value => $label)
            <option
                @selected($value === $filters['order_by'] ?? null)
                value="{{$value}}"
            >{{$label}}</option>
            @endforeach
        </x-select-input>
        <div>
            <x-input-error :messages="$errors->get('order_by')" />
        </div>

        <span class="block text-sm mt-1">Orden</span>
        <x-select-input name="order" class="w-full">
            @php
                $orderInputOptions = ['asc' => 'Ascendente', 'desc' => 'Descendente',];
            @endphp
            @foreach($orderInputOptions as $value => $label)
            <option
                @selected($value === $filters['order'] ?? null)
                value="{{$value}}"
            >{{$label}}</option>
            @endforeach
        </x-select-input>
        <div>
            <x-input-error :messages="$errors->get('order')" />
        </div>

        <div class="w-full flex justify-center">
            <x-secondary-button type="submit" class="mt-3">
                Aplicar
            </x-secondary-button>
        </div>
    </form>
</x-accordion.simple>