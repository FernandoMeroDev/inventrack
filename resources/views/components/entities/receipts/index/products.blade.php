@props(['receipts'])

<x-table.simple>
    @foreach($receipts as $receipt)
    <x-table.simple.tr>
        <x-table.simple.td>
            <div class="grid grid-cols-2">
                @if($receipt->comment)
                    <div class="text-wrap col-span-2">
                        <h3 class="font-bold">Comentario</h3>
                        <p>{{$receipt->comment}}</p>
                    </div>
                @endif
                <div class="text-wrap col-span-1 pr-1">
                    <h3 class="font-bold">Fecha</h3>
                    <p>
                        {{date('d/m/Y H\h:i', strtotime($receipt->created_at))}}
                    </p>
                </div>
                <div class="col-span-1">
                    <h3 class="font-bold">Comprobante</h3>
                    <p class="flex items-center"
                    ><a
                        class="text-blue-400 underline"
                        href="{{route('receipts.show', $receipt->id)}}"
                    >Número: {{$receipt->id}}</a></p>
                </div>
            </div>
        </x-table.simple.td>
    </x-table.simple.tr>
    @endforeach
</x-table.simple>
<div class="overflow-x-auto">
    {{$receipts->links(data: ['scrollTo' => false])}}
</div>