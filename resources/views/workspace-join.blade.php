@extends('master')

@section('title', 'Join Workspace')

@section('content')
    <div class="container py-2">
        <div class="card bg-dark shadow">
            <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-users fa-stack-1x" aria-hidden="true"></i>
            </span>
                Join Workspace
            </div>
            <div class="card-body">
                <form name="join-workspace-form" id="join-workspace-form" method="post" action="{{ url('dashboard/workspace-join') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="invite_code" class="text-white font-weight-bold">
                            Invite Code
                        </label>
                        <input type="text" id="invite_code" name="invite_code" class="form-control shadow-sm">
                    </div>
                    <br />
                    <button type="submit" class="btn btn-success font-weight-bold shadow">
                        <i class="fa fa-plus-square"></i>
                        Join
                    </button>
                </form>
            </div>
            @include('layouts.dashboard.card-footer', [
                'text' => 'You can only be in 1 workspace at a time. This action cannot be undone.'
            ])
        </div>
    </div>
@endsection
