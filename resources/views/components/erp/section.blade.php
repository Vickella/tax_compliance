<div {{ $attributes->merge(['class' => 'rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-xl']) }}>
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
