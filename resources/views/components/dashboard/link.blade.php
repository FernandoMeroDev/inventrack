@props(['href'])

<a
    href="{{$href}}"
    {{$attributes}}
    class="
        flex flex-col scale-75
        w-36 h-40 border border-slate-400 rounded
        justify-center items-center
        focus:outline-none focus:bg-gray-100
        hover:bg-gray-100
    "
>
    {{$slot}}
</a>