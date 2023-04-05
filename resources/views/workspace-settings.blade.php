@php ($title = ($isNew) ? 'Create Workspace' : "{$workspace->name} Workspace")

@extends('master')

@section('title', $title)

@section('content')
<div class="container py-2">
    <div class="card shadow bg-dark">
        @include('layouts.dashboard.card-header', [
            'text' => $title,
            'icon' => 'fa-users',
            'additional' => (!$isNew) ? "ID: {$workspace->id}" : ""
        ])
        <div class="card-body shadow-sm">
            <form action="{{ url('dashboard/workspace-settings') }}" name="create-workspace-form" id="create-workspace-form" method="post" enctype="multipart/form-data">
                @csrf
                @honeypot
                @include('workspace.board-settings')
                @include('workspace.appstore-api-settings')
                @include('workspace.appstore-sign-settings')
                @include('workspace.testflight-api-settings')
                @include('workspace.github-api-settings')
                <br/>
                @include('layouts.dashboard.button-success', [
                    'icon' => 'fa-check-square-o',
                    'name' => ($isNew) ? 'Create Workspace' : 'Update'
                ])
                @can('scan jobs')
                    <livewire:workspace.scan-github-button-view />
                @endcan
            </form>
        </div>
        @include('layouts.dashboard.card-footer', [
            'text' => ($isNew)
                ? 'All fields can be changed later by <b>Workspace Admin(s)</b>.'
                : 'GitHub Scan Logs: <a href=' . route('scan-log') . '> Click Here</a>'
        ])
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/views/workspace-settings.js') }}"></script>
@endsection
