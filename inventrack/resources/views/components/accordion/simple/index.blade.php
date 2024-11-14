@props(['title' => null, 'id', 'expanded' => false])

@if($expanded)

<div {{$attributes}} id="{{$id}}">
    <div
        class="rounded-lg border border-neutral-200 bg-white dark:border-neutral-600 dark:bg-body-dark">
        <h2 class="mb-0" id="{{$id}}HeadingOne">
            <button
                class="group relative flex w-full items-center rounded-lg border-0 border-b border-neutral-200 dark:border-neutral-600 bg-white px-5 py-4 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-body-dark dark:text-white [&:not([data-twe-collapse-collapsed])]:bg-white [&:not([data-twe-collapse-collapsed])]:text-primary [&:not([data-twe-collapse-collapsed])]:shadow-border-b dark:[&:not([data-twe-collapse-collapsed])]:bg-surface-dark dark:[&:not([data-twe-collapse-collapsed])]:text-primary dark:[&:not([data-twe-collapse-collapsed])]:shadow-white/10"
                type="button"
                data-twe-collapse-init
                data-twe-target="#{{$id}}CollapseOne"
                aria-expanded="true"
                aria-controls="{{$id}}CollapseOne">
                {{$title}}
                <span
                    class="-me-1 ms-auto h-5 w-5 shrink-0 rotate-[-180deg] transition-transform duration-200 ease-in-out group-data-[twe-collapse-collapsed]:me-0 group-data-[twe-collapse-collapsed]:rotate-0 motion-reduce:transition-none [&>svg]:h-6 [&>svg]:w-6">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </span>
            </button>
        </h2>
        <div
            id="{{$id}}CollapseOne"
            data-twe-collapse-item
            class="!visible"
            data-twe-collapse-show
            aria-labelledby="{{$id}}HeadingOne"
            data-twe-parent="#{{$id}}">
            <div>
                {{$slot}}
            </div>
        </div>
    </div>
</div>

@else

<div {{$attributes}} id="{{$id}}">
    <div
        class="rounded-lg border border-neutral-200 bg-white dark:border-neutral-600 dark:bg-body-dark">
        <h2 class="mb-0" id="{{$id}}HeadingOne">
            <button
                class="data-[twe-collapse-collapsed] group relative flex w-full items-center border-0 bg-white px-5 py-4 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none data-[twe-collapse-collapsed]:rounded-b-lg dark:bg-body-dark dark:text-white [&:not([data-twe-collapse-collapsed])]:bg-white [&:not([data-twe-collapse-collapsed])]:text-primary [&:not([data-twe-collapse-collapsed])]:shadow-border-b dark:[&:not([data-twe-collapse-collapsed])]:bg-surface-dark dark:[&:not([data-twe-collapse-collapsed])]:text-primary  dark:[&:not([data-twe-collapse-collapsed])]:shadow-white/10"
                type="button"
                data-twe-collapse-init
                data-twe-target="#{{$id}}CollapseOne"
                data-twe-collapse-collapsed
                aria-expanded="false"
                aria-controls="{{$id}}CollapseOne">
                {{$title}}
                <span
                    class="-me-1 ms-auto h-5 w-5 shrink-0 rotate-[-180deg] transition-transform duration-200 ease-in-out group-data-[twe-collapse-collapsed]:me-0 group-data-[twe-collapse-collapsed]:rotate-0 motion-reduce:transition-none [&>svg]:h-6 [&>svg]:w-6">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor">
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </span>
            </button>
        </h2>
        <div
            id="{{$id}}CollapseOne"
            data-twe-collapse-item
            class="!visible hidden"
            aria-labelledby="{{$id}}HeadingOne"
            data-twe-parent="#{{$id}}">
            <div>
                {{$slot}}
            </div>
        </div>
    </div>
</div>

@endif