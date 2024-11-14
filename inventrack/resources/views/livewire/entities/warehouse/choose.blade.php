<div>
    <span>
        Bodega @if($required)<span class="text-red-500">*</span>@endif
    </span>
    <x-text-input wire:model.live="search" placeholder="buscar..." class="block" />
    <x-table.simple>
        @if($all)
            <x-table.simple.tr>
                <x-table.simple.td>
                    <input
                        type="radio"
                        name="warehouse_id"
                        value="all"
                        id="warehouseInputAll"
                        @required($required)
                        @checked($default == 'all')
                    />
                    <label
                        for="warehouseInputAll"
                        class="inline-flex w-full h-full"
                    >Todos</label>
                </x-table.simple.td>
            </x-table.simple.tr>
        @endif
        @foreach($warehouses as $warehouse)
            <x-table.simple.tr>
                <x-table.simple.td>
                    <input
                        type="radio"
                        name="warehouse_id"
                        value="{{$warehouse->id}}"
                        id="warehouseInput{{$warehouse->id}}"
                        @required($required)
                        @checked($warehouse->id == $default)
                    />
                    <label
                        for="warehouseInput{{$warehouse->id}}"
                        class="inline-flex w-full h-full"
                    >{{$warehouse->name}}</label>
                </x-table.simple.td>
            </x-table.simple.tr>
        @endforeach
    </x-table.simple>
    {{$warehouses->links(data: ['scrollTo' => false])}}
    <x-input-error :messages="$errors->get('warehouse_id')" />
</div>
