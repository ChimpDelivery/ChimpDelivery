@extends('master')

@section('title', 'User Profile')

@section('content')
    <div class="container py-2">
        <div class="card shadow">
            <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-user fa-stack-1x" aria-hidden="true"></i>
            </span>
                {{ Auth::user()->name }} Profile
            </div>
            <div class="card-body">
                <form name="user-profile-form" id="user-profile-form" method="post" action="{{ route('dashboard.profile') }}">
                    @csrf
                    <p>
                        <a class="btn btn-primary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_base_settings" role="button" aria-expanded="true" aria-controls="collapse_base_settings">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                            <b>Base Settings</b>
                        </a>
                    </p>
                    <div class="collapse show" id="collapse_base_settings">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control shadow-sm" name="name" aria-describedby="basic-addon3" value="{{ Auth::user()->name }}" required />
                        </div>

                        @livewire('create-api-token-view')
                    </div>

                    <button type="submit" class="btn btn-success font-weight-bold shadow">
                        <i class="fa fa-pencil-square-o"></i>
                        Update
                    </button>
                </form>
            </div>
            <div class="card-footer text-muted">
                <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
                API Keys can be disabled by Workspace Admins.
            </div>
        </div>
    </div>
    @livewireScripts
@endsection
