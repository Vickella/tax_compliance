@props(['variant' => 'ghost'])

@php
    $base = 'inline-flex items-center gap-2 rounded-2xl px-5 py-2.5 text-sm font-semibold transition border shadow-sm';
    $base = 'inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition border';
    $variants = [
        'primary' => 'bg-indigo-500/80 hover:bg-indigo-500 text-white border-indigo-300/40',
        'ghost' => 'bg-white/10 hover:bg-white/20 text-white border-white/10',
        'danger' => 'bg-rose-500/80 hover:bg-rose-500 text-white border-rose-300/40',
        'muted' => 'bg-slate-900/60 hover:bg-slate-900 text-white/80 border-white/10',
    ];
    $classes = $variants[$variant] ?? $variants['ghost'];
@endphp

<button {{ $attributes->merge(['class' => $base.' '.$classes, 'type' => 'button']) }}>
<button {{ $attributes->merge(['class' => $base.' '.$classes]) }}>
    {{ $slot }}
</button>
