@props(['product'])

@if($product->image_uploaded)
    <img
        {{$attributes}}
        class="max-w-full max-h-56 border border-gray-300 rounded-md"
        src="{{asset("storage/$product->id")}}"
        alt="Imagen de Producto"
    >
@endif