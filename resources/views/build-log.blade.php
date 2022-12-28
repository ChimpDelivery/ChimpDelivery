@extends('master')

@section('title', $app->project_name)

@section('content')
    <div class="container py-2">
        <div class="card shadow bg-dark">
            @include('layouts.dashboard.card-header', [
                'text' => "{$app->project_name} Build Log",
                'icon' => 'fa-eye'
            ])
            <div class="card-body overflow-auto" style="max-height: 70vh;">
                <code style="color: #dcdbd4;">
                    {!! nl2br(e($full_log)) !!}
                </code>
            </div>
            @include('layouts.dashboard.card-footer', [
                'text' => round(strlen($full_log) / 1024 / 1024, 2) . ' MB'
            ])
        </div>
    </div>
@endsection
