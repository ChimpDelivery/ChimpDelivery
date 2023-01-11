@extends('master')

@section('title', 'User Profile')

@section('content')
    <div class="container py-2">
        <div class="card bg-dark shadow">
            @include('layouts.dashboard.card-header', [
                'text' => "{$user->name} Profile",
                'icon' => 'fa-user',
                'additional' => "ID: {$user->id}"
            ])
            <div class="card-body shadow-sm">
                <form name="user-profile-form" id="user-profile-form" method="post" action="{{ route('dashboard.profile') }}">
                    @csrf
                    @honeypot
                    <p>
                        <a class="btn btn-primary btn-block text-left shadow"
                            role="button" data-toggle="collapse" href="#collapse_base_settings"
                            aria-expanded="true" aria-controls="collapse_base_settings">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                            <b>Personal Settings</b>
                        </a>
                    </p>
                    <div class="collapse show" id="collapse_base_settings">
                        <div class="form-group">
                            <img class="img-thumbnail rounded-circle" alt="" width="75" height="75" src="{{ $user->gravatar }}" />
                        </div>
                        <div class="form-group">
                            <label for="name" class="text-white font-weight-bold">
                                Full Name
                            </label>
                            <input type="text" class="form-control shadow-sm"
                                    id="name" name="name" aria-describedby="basic-addon3"
                                    value="{{ $user->name }}" required />
                        </div>
                        <div class="form-group">
                            <label for="email" class="text-white font-weight-bold">
                                Email
                            </label>
                            <input type="text" class="form-control shadow-sm"
                                    id="email" name="email" aria-describedby="basic-addon3"
                                    value="{{ $user->email }}" readonly />
                        </div>
                        @hasanyrole('User_Workspace|Admin_Workspace|Admin_Super')
                            @can('create api token')
                                @livewire('create-api-token-view')
                            @endcan
                        @endhasrole
                    </div>
                    <br />
                    @include('layouts.dashboard.button-success', [
                        'icon' => 'fa-pencil-square-o',
                        'name' => 'Update Profile'
                    ])
                </form>
            </div>
            @include('layouts.dashboard.card-footer', [
                'text' => $user->isNew()
                    ? '<b>You are not in any workspace.</b>'
                    : 'Workspace API Token can be disabled by <b>Workspace Admin(s)</b>.'
            ])
        </div>
    </div>
@endsection
