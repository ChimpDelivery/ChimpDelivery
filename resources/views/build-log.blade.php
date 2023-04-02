@extends('master')

@section('title', $app->project_name)

@section('headers')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/styles/a11y-dark.min.css">
@endsection

@section('content')
    <div class="container py-2">
        <div class="card shadow bg-dark">
            @include('layouts.dashboard.card-header', [
                'text' => "{$app->project_name} Build Log",
                'icon' => 'fa-eye'
            ])
            <div class="card-body overflow-auto" style="max-height: 70vh;">
                <pre><code class="language-csharp bg-transparent">{{ $full_log }}</code></pre>
            </div>
            @include('layouts.dashboard.card-footer', [
                'text' => round(strlen($full_log) / 1024 / 1024, 2) . ' MB'
            ])
        </div>
    </div>
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.7.0/build/highlight.min.js"></script>
    <script src="{{ asset('js/views/build-log.js') }}"></script>
@endsection
