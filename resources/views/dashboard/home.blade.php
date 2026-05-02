@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Page Title --}}
    <div>
        <h1 class="text-2xl font-semibold text-white">Dashboard</h1>
        <p class="text-sm text-slate-400 mt-1">Quick access to core actions</p>
    </div>

    {{-- Shortcuts Section --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Shortcuts</h2>
            <span class="text-xs text-slate-400">Common actions</span>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($shortcuts as $shortcut)
                <a href="{{ $shortcut['route'] }}" 
                   class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg p-4 text-center transition-all hover:border-indigo-500/50">
                    <div class="text-2xl mb-2">{{ $shortcut['icon'] }}</div>
                    <div class="text-sm font-medium text-white">{{ $shortcut['label'] }}</div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($cards as $card)
            <div class="card">
                <h2 class="text-lg font-semibold text-white mb-4">{{ $card['title'] }}</h2>
                <div class="space-y-2">
                    @foreach($card['items'] as $item)
                        <div class="text-sm text-slate-300">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection