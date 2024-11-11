<div>
<div class="mt-3 flex justify-between">
    @if($previousLevelLink == 'none')
        <x-secondary-link-button class="opacity-50">
            <= Piso
        </x-secondary-link-button>
    @else
        <x-secondary-link-button href="{!! $previousLevelLink !!}">
            <= Piso
        </x-secondary-link-button>
    @endif

    @if($nextLevelLink == 'none')
        <x-secondary-link-button class="opacity-50">
            Piso =>
        </x-secondary-link-button>
    @else
        <x-secondary-link-button href="{!! $nextLevelLink !!}">
            Piso =>
        </x-secondary-link-button>
    @endif
</div>
<div class="mt-3 flex justify-between">
    @if($previousShelfLink == 'none')
        <x-secondary-link-button class="opacity-50">
            <= Percha
        </x-secondary-link-button>
    @else
        <x-secondary-link-button href="{!! $previousShelfLink !!}">
            <= Percha
        </x-secondary-link-button>
    @endif

    @if($nextShelfLink == 'none')
        <x-secondary-link-button class="opacity-50">
            Percha =>
        </x-secondary-link-button>
    @else
        <x-secondary-link-button href="{!! $nextShelfLink !!}">
            Percha =>
        </x-secondary-link-button>
    @endif
</div>
</div>