@props([
    'href',
    'label' => 'Kembali',
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-2 text-sm font-medium transition-colors hover:opacity-70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 rounded-md',
        'style' => 'color: var(--primary);',
    ]) }}
>
    <svg
        width="16"
        height="16"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
    >
        <path d="M19 12H5m7 7-7-7 7-7" />
    </svg>
    <span>{{ $label }}</span>
</a>
