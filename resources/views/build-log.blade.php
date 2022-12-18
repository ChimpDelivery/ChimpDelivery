@extends('master')

@section('title', $app->project_name)

@section('content')
    <div class="container py-2">
        <div class="card shadow bg-dark">
            <div class="card-header text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-eye fa-stack-1x" aria-hidden="true"></i>
            </span>
                {{ $app->project_name }} Build Log
            </div>
            <div class="card-body overflow-auto" style="max-height: 70vh;">
                <code class="text-white">
                    {!! nl2br(e($full_log)) !!}
                </code>
            </div>
            @include('layouts.dashboard.card-footer', [
                'text' => round(strlen($full_log) / 1024 / 1024, 2) . ' MB'
            ])
        </div>
    </div>
@endsection
