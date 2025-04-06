@props(['type' => 'submit'])

<button {{ $attributes->merge(['type' => $type, 'class' => 'inline-flex items-center px-4 py-2 bg-pk border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pklt active:bg-pkdk focus:outline-none focus:border-pkdk focus:ring ring-pk/30 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
