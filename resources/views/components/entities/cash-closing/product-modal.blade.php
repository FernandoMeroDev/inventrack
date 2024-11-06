@props([
    'product',
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

<x-modal {{$attributes}} :$name :$show :max-with="$maxWidth">
<div class="p-3">
    <div class="flex justify-center">
        <x-secondary-button x-on:click.prevent="$dispatch('close')"
        >Regresar</x-secondary-button>
    </div>
    <p class="font-bold mt-3">Comprobantes</p>
    <x-table.simple>
        @foreach($product['receipts'] as $receipt)
            <x-table.simple.tr>
                <x-table.simple.td>
                    <a
                        href="#{{-- route('receipts.show', $receipt->id) --}}"
                        class="text-blue-400 underline"
                    >
                        Número: {{$receipt->id}}
                    </a>
                </x-table.simple.td>
            </x-table.simple.tr>
        @endforeach
    </x-table.simple>
    <h3 class="text-wrap mt-3">
        {{$product['name']}}
    </h3>
    <div>
        <img
            class="max-w-full border-gray-300 rounded-md"
            src="{{asset('storage/' . $product['id'] . '.jpg')}}"
            alt="Imagen de Producto"
        >
    </div>
    <div class="flex justify-center mt-3">
        <x-secondary-button x-on:click.prevent="$dispatch('close')"
        >Regresar</x-secondary-button>
    </div>
</div>
</x-modal>