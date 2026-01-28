@props(['title', 'subtitle' => null])

<div class="min-h-screen bg-gradient-to-br from-indigo-950 via-violet-900 to-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-white">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="text-sm text-white/70 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if (isset($actions))
                <div class="flex flex-wrap items-center justify-start gap-2 sm:justify-end">
                    {{ $actions }}
                </div>
            @endif
        </div>

        <div class="mt-6 space-y-6">
            {{ $slot }}
        </div>
    </div>
</div>
