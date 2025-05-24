@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">{{ __('message.Activity Log') }}</h1>

    <div class="bg-white p-6 rounded shadow">
        @forelse($logs as $log)
            <div class="border-b py-2 text-sm text-gray-700">
                <strong>{{ $log->action }}</strong>
                - {{ $log->details }}
                <span class="text-gray-400 text-xs">({{ $log->created_at->format('Y-m-d H:i') }})</span>
            </div>
        @empty
            <p class="text-gray-400">{{ __('message.No logs found') }}</p>
        @endforelse
    </div>
@endsection