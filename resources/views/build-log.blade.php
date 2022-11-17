@extends('master')

@section('title', $app->project_name)

@section('content')
    <div class="container py-2">
        <div class="card shadow">
            <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-eye fa-stack-1x" aria-hidden="true"></i>
            </span>
                {{ $app->project_name }} Build Log
            </div>
            <div class="card-body">
                <code>
                    {!! nl2br(e($full_log)) !!}
                </code>
            </div>
            <!--
            <div class="card-footer text-muted">
                <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
                Add Footer
            </div>
            !-->
        </div>
    </div>
@endsection
