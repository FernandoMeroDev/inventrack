<div>
    <span>
        Usuario @if($required)<span class="text-red-500">*</span>@endif
    </span>
    <x-text-input wire:model.live="search" placeholder="buscar..." class="block" />
    <x-table.simple>
        @if($all)
            <x-table.simple.tr>
                <x-table.simple.td>
                    <input
                        type="radio"
                        name="user_id"
                        value="all"
                        id="userInputAll"
                        @required($required)
                        @checked($default == 'all')
                    />
                    <label
                        for="userInputAll"
                        class="inline-flex w-full h-full"
                    >Todos</label>
                </x-table.simple.td>
            </x-table.simple.tr>
        @endif
        @foreach($users as $user)
            <x-table.simple.tr>
                <x-table.simple.td>
                    <input
                        type="radio"
                        name="user_id"
                        value="{{$user->id}}"
                        id="userInput{{$user->id}}"
                        @required($required)
                        @checked($user->id == $default)
                    />
                    <label
                        for="userInput{{$user->id}}"
                        class="inline-flex w-full h-full"
                    >{{$user->name}}</label>
                </x-table.simple.td>
            </x-table.simple.tr>
        @endforeach
    </x-table.simple>
    {{$users->links(data: ['scrollTo' => false])}}
    <x-input-error :messages="$errors->get('user_id')" />
</div>
