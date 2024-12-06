@props(['product'])

@if($product->image_uploaded)
<div class="w-full p-1 border border-gray-300 rounded-md flex justify-center">
    <img
        {{$attributes}}
        class="min-w-20 max-w-full min-h-48 max-h-64"
        src="{{asset("storage/$product->id")}}"
        alt="Imagen de Producto"
        loading="lazy"
    >
</div>
@endif