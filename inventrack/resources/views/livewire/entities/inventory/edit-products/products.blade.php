<div>
@if($products->isNotEmpty())
<x-table.simple>
    <x-table.simple.tr wire:key="0">
        <x-table.simple.td class="flex justify-center">
            <x-secondary-button disabled id="ableDragAndDropButton">
                Cambiar Orden
            </x-secondary-button>
        </x-table.simple.td>
    </x-table.simple.tr>
    @foreach($products as $i => $product)
        <x-table.simple.tr id="productRow{{$product->id}}" class="productRowDraggable" wire:key="{{$product->id}}">
            <x-table.simple.td>
                <div class="grid grid-cols-2">
                    <div
                        x-on:click.prevent="$dispatch('open-modal', 'product-modal-{{$product->id}}')"
                        class="text-wrap col-span-2"
                    >
                        {{$product->name}}
                        <input name="product_ids[]" hidden value="{{$product->id}}" />
                    </div>
                    <x-entities.products.modal
                        :$product
                        :warehouse-id="$shelf->warehouse->id"
                        :name="'product-modal-' . $product->id"
                    />
                    <div class="pr-1 col-span-1">
                        <label for="amountInput{{$product->id}}" class="block font-bold">
                            Cantidad
                        </label>
                        <x-number-input
                            name="amounts[]"
                            value="{{$amounts[$i]}}"
                            x-on:change="$wire.changeAmount({{$i}}, $event.target.value)"
                            min="1" max="255"
                            id="amountInput{{$product->id}}"
                            class="w-full h-6 pl-1"
                        />
                    </div>
                    <div class="col-span-1 flex justify-center items-end">
                        <button
                            wire:click.prevent="remove({{$product->id}})"
                            class="livewireActionButton text-white bg-red-400 px-2 rounded mt-1"
                        >Remover</button>
                    </div>
                </div>
            </x-table.simple.td>
        </x-table.simple.tr>
    @endforeach
</x-table.simple>

@script
<script>
    const ableDragAndDrop = () => {
        let draggedElement = '';

        let productRows = document.querySelectorAll('.productRowDraggable');

        if(productRows.length < 2){
            alert('Debe haber al menos dos productos');
            return;
        }

        // Unable actions to prevent livewire conflicts with HTML drag and drop
        searchProductsInput = document.getElementById('searchProductsFalseInput');
        searchProductsInput.disabled = true;
        searchProductsInput.classList.add('opacity-50');
        document.querySelectorAll('.livewireActionButton').forEach(button => {
            button.disabled = true;
            button.classList.add('opacity-50');
        });

        productRows.forEach(row => {
            row.draggable = true;

            row.addEventListener('dragstart', (e) => {
                draggedElement = row;
                e.dataTransfer.setData('text/plain', row.id);
            });

            row.addEventListener('dragover', (e) => {
                e.preventDefault();
                row.classList.add('bg-red-400');
            });

            row.addEventListener('dragleave', () => {
                row.classList.remove('bg-red-400');
            });

            row.addEventListener('drop', (e) => {
                e.preventDefault();
                row.classList.remove('bg-red-400');

                const targetElement = row;
                if (draggedElement !== targetElement) {
                    // Intercambiar los elementos usando clonación
                    const container = targetElement.parentNode;
                    const cloneDragged = draggedElement.cloneNode(true);
                    const cloneTarget = targetElement.cloneNode(true);

                    container.replaceChild(cloneDragged, targetElement);
                    container.replaceChild(cloneTarget, draggedElement);

                    // Volver a agregar eventos a los nuevos elementos
                    addDragEvents(cloneDragged);
                    addDragEvents(cloneTarget);
                }
            });
        });

        function addDragEvents(el) {
            el.addEventListener('dragstart', (e) => {
                draggedElement = el;
                e.dataTransfer.setData('text/plain', el.id);
            });

            el.addEventListener('dragover', (e) => {
                e.preventDefault();
                el.classList.add('bg-red-400');
            });

            el.addEventListener('dragleave', () => {
                el.classList.remove('bg-red-400');
            });

            el.addEventListener('drop', (e) => {
                e.preventDefault();
                el.classList.remove('bg-red-400');

                const targetElement = el;
                if (draggedElement !== targetElement) {
                const container = targetElement.parentNode;
                const cloneDragged = draggedElement.cloneNode(true);
                const cloneTarget = targetElement.cloneNode(true);

                container.replaceChild(cloneDragged, targetElement);
                container.replaceChild(cloneTarget, draggedElement);

                addDragEvents(cloneDragged);
                addDragEvents(cloneTarget);
                }
            });
        }
    };

    setTimeout(() => {
        let ableDragAndDropButton = document.getElementById('ableDragAndDropButton');
        ableDragAndDropButton.disabled = false;
        ableDragAndDropButton.addEventListener('click', (event) => {
            event.preventDefault();
            event.target.disabled = true;
            ableDragAndDrop();
        });
    }, 2000);
</script>
@endscript
@endif

<x-text-input 
    wire:model.live.debounce.400ms="search" 
    placeholder="Buscar..." 
    id="searchProductsFalseInput" 
/>
@if($searchedProducts->isNotEmpty())
<x-table.simple>
    @foreach($searchedProducts as $product)
        <x-table.simple.tr>
            <x-table.simple.td>
                <div class="grid grid-cols-2">
                    <div
                        x-on:click.prevent="$dispatch('open-modal', 'product-modal-{{$product->id}}')"
                        class="text-wrap col-span-2"
                    >
                        {{$product->name}}
                    </div>
                    <x-entities.products.modal
                        :name="'product-modal-' . $product->id"
                        :$product
                        :warehouse-id="$shelf->warehouse->id"
                    />
                    <div class="col-span-2 flex justify-center items-end">
                        <button
                            wire:click.prevent="add({{$product->id}})"
                            class="livewireActionButton text-white bg-black px-3 rounded mt-1"
                        >Agregar</button>
                    </div>
                </div>
            </x-table.simple.td>
        </x-table.simple.tr>
    @endforeach
</x-table.simple>
{{$searchedProducts->links(data: ['scrollTo' => false])}}
@endif

<div class="mt-8 flex justify-between">
    <x-primary-button type="submit">
        Guardar
    </x-primary-button>
    <x-secondary-button
        x-data
        x-on:click.prevent="$dispatch('open-modal', 'empty-modal')"
    >
        Vaciar
    </x-secondary-button>
    <x-modal :name="'empty-modal'">
        <div class="p-2">
            <h2 class="text-center text-md font-bold">¿Seguro?</h2>
            <div class="mt-3 flex justify-evenly">
            <x-danger-button x-on:click.prevent="$dispatch('close'); $wire.empty()">
                Si
            </x-danger-button>
            <x-secondary-button x-on:click.prevent="$dispatch('close')">
                No
            </x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>
</div>
