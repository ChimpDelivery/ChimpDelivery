@php ($title = ($isNew) ? 'Create Workspace' : "{$workspace->name} Workspace")

@extends('master')

@section('title', $title)

@section('content')
<div class="container py-2">
    <div class="card shadow bg-dark">
        <div class="card-header text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-users fa-stack-1x" aria-hidden="true"></i>
            </span>
            {{ $title }}
            @if(!$isNew)
                <span class="my-2 text-muted pull-right">ID: {{ $workspace->id }}</span>
            @endif
        </div>
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
                    @include('workspace.scan-jenkins-button')
                @endcan
            </form>
        </div>
        @include('layouts.dashboard.card-footer', [
            'text' => 'All fields can be changed later by <b>Workspace Admin(s)</b> except <b>GitHub Organization</b> name.'
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
