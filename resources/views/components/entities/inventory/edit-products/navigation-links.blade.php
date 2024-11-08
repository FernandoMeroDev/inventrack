<div class="mt-3 flex justify-between">
    @if($previousExists)
    <x-secondary-link-button
        :href="route('inventory.edit-products', [
            'shelf_id' => $previous['shelf_id'],
            'level_number' => $previous['level_number'],
    ])">
        <= {{$previous['label']}}
    </x-secondary-link-button>
    @else
    <x-secondary-link-button class="opacity-50">
        <= Piso
    </x-secondary-link-button>
    @endif

    @if($nextExists)
    <x-secondary-link-button
        :href="route( 'inventory.edit-products', [
            'shelf_id' => $next['shelf_id'],
            'level_number' => $next['level_number'],
    ])">
        {{$next['label']}} =>
    </x-secondary-link-button>
    @else
    <x-secondary-link-button class="opacity-50">
        Piso =>
    </x-secondary-link-button>
    @endif
</div>