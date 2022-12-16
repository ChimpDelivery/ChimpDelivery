@php ($title = ($isNew) ? 'Create Workspace' : "{$workspace->name} Workspace")

@extends('master')

@section('title', $title)

@section('content')
<div class="container py-2">
    <div class="card shadow">
        <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-users fa-stack-1x" aria-hidden="true"></i>
            </span>
            {{ $title }}
            @if(!$isNew)
                <pre class="text-muted pull-right">ID: {{ $workspace->id }}</pre>
            @endif
        </div>
        <div class="card-body">
            <form action="{{ url('dashboard/workspace-settings') }}" name="create-workspace-form" id="create-workspace-form" method="post" enctype="multipart/form-data">
                @csrf
                @honeypot
                @include('workspace.board-settings')
                @include('workspace.appstore-api-settings')
                @include('workspace.appstore-sign-settings')
                @include('workspace.testflight-api-settings')
                @include('workspace.github-api-settings')
                <br/>
                <button type="submit" class="btn btn-success font-weight-bold shadow" @if(!$isNew) onclick="return confirm('Workspace settings will be updated, are you sure?')" @endif>
                    <i class="fa {{ ($isNew) ? 'fa-plus-square' : 'fa-pencil-square-o' }}"></i>
                    {{ ($isNew) ? 'Create Workspace' : 'Update' }}
                </button>
                @can('scan jobs')
                    @include('workspace.scan-jenkins-button')
                @endcan
            </form>
        </div>
        @include('layouts.dashboard.card-footer', [
            'text' => 'All fields can be changed later by "Workspace Admin" except "GitHub Organization" name.'
        ])
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
</script>
@endsection
