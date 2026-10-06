@props([
    'type' => 'success', // success or error
    'message' => null,
    'errorsop' => null
])

@php
    $isSuccess = $type === 'success';
    $color = $isSuccess ? 'green' : 'red';
@endphp

@if($message)
<div id="alert-border-{{ $type }}" class="flex items-start p-4 mb-4 text-{{ $color }}-800 border-t-4 border-{{ $color }}-300 bg-{{ $color }}-50 dark:text-{{ $color }}-400 dark:bg-gray-800 dark:border-{{ $color }}-800" role="alert">
    <svg class="shrink-0 w-4 h-4 mt-1" fill="currentColor" viewBox="0 0 20 20">
        @if($isSuccess)
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        @else
            <path d="M18 10a8 8 0 1 0-16 0 8 8 0 0 0 16 0ZM9 7a1 1 0 1 1 2 0v3a1 1 0 0 1-2 0V7Zm1 7a1.25 1.25 0 1 1 0-2.5A1.25 1.25 0 0 1 10 14Z"/>
        @endif
    </svg>

    <div class="ms-3 text-sm font-medium w-full">
        <strong>{{ ucfirst($type) }}:</strong> {{ $message }}
        
        @if(!$isSuccess && $errorsop)
            <ul class="list-disc pl-5 mt-2 text-sm">
                @foreach($errorsop->all() as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-{{ $color }}-50 text-{{ $color }}-500 rounded-lg focus:ring-2 focus:ring-{{ $color }}-400 p-1.5 hover:bg-{{ $color }}-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-{{ $color }}-400 dark:hover:bg-gray-700" aria-label="Close">
        <span class="sr-only">Dismiss</span>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
    </button>
</div>
@endif
