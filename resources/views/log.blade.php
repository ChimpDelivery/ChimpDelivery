@extends('master')

@section('title', $log_title)

@section('headers')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/styles/a11y-dark.min.css">
@endsection

@section('content')
    <div class="container py-2">
        <div class="card shadow bg-dark">
            @include('layouts.dashboard.card-header', [
                'text' => $log_title,
                'icon' => 'fa-eye'
            ])
            <div class="card-body overflow-auto" style="max-height: 70vh;">
                <pre class="text-white"><code class="language-csharp bg-transparent">{{ $full_log }}</code></pre>
            </div>
            @include('layouts.dashboard.card-footer', [
                'text' => 'Log Size: ' . round(strlen($full_log) / 1024 / 1024, 2) . ' MB'
            ])
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/highlight.min.js"></script>
    <script>hljs.highlightAll();</script>
@endsection
