@extends('master')

@section('title', 'User Profile')

@section('content')
    <div class="container py-2">
        <div class="card bg-dark shadow">
            <div class="card-header text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-user fa-stack-1x" aria-hidden="true"></i>
            </span>
                {{ Auth::user()->name }} Profile
                <pre class="text-muted pull-right">ID: {{ Auth::user()->id }}</pre>
            </div>
            <div class="card-body shadow-sm">
                <form name="user-profile-form" id="user-profile-form" method="post" action="{{ route('dashboard.profile') }}">
                    @csrf
                    <p>
                        <a class="btn btn-secondary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_base_settings" role="button" aria-expanded="true" aria-controls="collapse_base_settings">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                            <b>Personal Settings</b>
                        </a>
                    </p>
                    <div class="collapse show" id="collapse_base_settings">
                        <div class="form-group">
                            <label for="name" class="text-white font-weight-bold">
                                Full Name
                            </label>
                            <input type="text" class="form-control shadow-sm" id="name" name="name" aria-describedby="basic-addon3" value="{{ Auth::user()->name }}" required />
                        </div>
                        <div class="form-group">
                            <label for="email" class="text-white font-weight-bold">
                                Email
                            </label>
                            <input type="text" class="form-control shadow-sm" id="email" name="email" aria-describedby="basic-addon3" value="{{ Auth::user()->email }}" readonly />
                        </div>
                        @if ((!$isNewUser && Auth::user()->can('create api token')) || Auth::user()->hasRole('Admin_Super'))
                            @livewire('create-api-token-view')
                        @endif
                    </div>
                    <br />
                    @include('layouts.dashboard.button-success', [
                        'icon' => 'fa-pencil-square-o',
                        'name' => 'Update Profile'
                    ])
                </form>
            </div>
            @include('layouts.dashboard.card-footer', [
                'text' => Auth::user()->isNew()
                    ? '<b>You are not in any workspace.</b>'
                    : 'Workspace API Token can be disabled by <b>Workspace Admins</b>.'
            ])
        </div>
    </div>
    @if ((!$isNewUser && Auth::user()->can('create api token')) || Auth::user()->hasRole('Admin_Super'))
        @livewireScripts
    @endif
@endsection
