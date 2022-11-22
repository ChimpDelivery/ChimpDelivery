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
                <pre class="text-muted pull-right">ID: {{ Auth::user()->id }}</pre>
            </div>
            <div class="card-body">
                <form name="user-profile-form" id="user-profile-form" method="post" action="{{ route('dashboard.profile') }}">
                    @csrf
                    <p>
                        <a class="btn btn-primary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_base_settings" role="button" aria-expanded="true" aria-controls="collapse_base_settings">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                            <b>Personal Settings</b>
                        </a>
                    </p>
                    <div class="collapse show" id="collapse_base_settings">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" class="form-control shadow-sm" id="name" name="name" aria-describedby="basic-addon3" value="{{ Auth::user()->name }}" required />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control shadow-sm" id="email" name="email" aria-describedby="basic-addon3" value="{{ Auth::user()->email }}" readonly />
                        </div>
                        @if(!$isNewUser && Auth::user()->can('create api token'))
                            @livewire('create-api-token-view')
                        @endif
                    </div>
                    <br />
                    <button type="submit" class="btn btn-success font-weight-bold shadow">
                        <i class="fa fa-pencil-square-o"></i>
                        Update Profile
                    </button>
                </form>
            </div>
            <div class="card-footer text-muted">
                <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
                @if(Auth::user()->isNew())
                    You are not in any workspace.
                @else
                    API Keys can be disabled by Workspace Admins.
                @endif
            </div>
        </div>
    </div>
    @if (!$isNewUser && Auth::user()->can('create api token'))
        @livewireScripts
    @endif
@endsection
