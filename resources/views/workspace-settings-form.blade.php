@extends('layouts.master')

@section('title', 'Create Workspace')

@section('content')
<div class="container py-2">
    <div class="card">
        <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-globe fa-stack-1x" aria-hidden="true"></i>
            </span>
            Create Workspace
        </div>
        <div class="card-body">
            <form name="create-workspace-form" id="create-workspace-form" method="post" action="{{ url('dashboard/workspace-settings') }}">
                @csrf
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-apple" aria-hidden="true"></i>
                    <b>General Settings</b>
                </div>
                <div class="form-group">
                    <label for="name">Workspace Name</label>
                    <input type="text" class="form-control" name="name" aria-describedby="basic-addon3" value="{{ $workspace->name }}" disabled>
                </div>
                <br/>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-apple" aria-hidden="true"></i>
                    <b>App Store Connect Settings</b> (to create Appstore Connect API Keys: <a href="https://appstoreconnect.apple.com/access/api">Click Here</a>)
                </div>
                <div class="form-group">
                    <label for="private_key">Private Key</label>
                    <input type="text" class="form-control" name="private_key" aria-describedby="basic-addon3" value="{{ $workspace->app_store_connect_setting->private_key }}">
                </div>
                <div class="form-group">
                    <label for="issuer_id">Issuer ID</label>
                    <input type="text" id="issuer_id" name="issuer_id" class="form-control" value="{{ $workspace->app_store_connect_setting->issuer_id }}">
                </div>
                <div class="form-group">
                    <label for="kid">Key ID</label>
                    <input type="text" id="kid" name="kid" class="form-control" value="{{ $workspace->app_store_connect_setting->kid }}">
                </div>
                <br/>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-apple" aria-hidden="true"></i>
                    <b>Apple Settings</b> (to create App Specific Password: <a href="https://appleid.apple.com/account/manage">Click Here</a>)
                </div>
                <div class="form-group">
                    <label for="usermail">User Mail</label>
                    <input type="text" id="usermail" name="usermail" class="form-control" value="{{ $workspace->apple_setting->usermail }}">
                </div>
                <div class="form-group">
                    <label for="app_specific_pass">App Specific Password</label>
                    <input type="text" id="app_specific_pass" name="app_specific_pass" class="form-control" value="{{ $workspace->apple_setting->app_specific_pass }}">
                </div>
                <br/>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-github" aria-hidden="true"></i>
                    <b>Github Settings</b> (to create Github Personal Access Token: <a href="https://github.com/settings/tokens">Click Here</a>)
                </div>
                <div class="form-group">
                    <label for="personal_access_token">Personal Access Token</label>
                    <input type="text" id="personal_access_token" name="personal_access_token" class="form-control" value=""{{ $workspace->github_setting->personal_access_token }}>
                </div>
                <div class="form-group">
                    <label for="organization_name">Organization Name</label>
                    <input type="text" id="organization_name" name="organization_name" class="form-control" value="{{ $workspace->github_setting->organization_name }}">
                </div>
                <div class="form-group">
                    <label for="template_name">Template Project</label>
                    <input type="text" id="template_name" name="template_name" class="form-control" value="{{ $workspace->github_setting->template_name }}">
                </div>
                <div class="form-group">
                    <label for="topic_name">Project Topic</label>
                    <input type="text" id="topic_name" name="topic_name" class="form-control" value="{{ $workspace->github_setting->topic_name }}">
                </div>
                <br/>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-github" aria-hidden="true"></i>
                    <b>Dashboard Settings</b>
                </div>
                <div class="form-group">
                    <label for="api_key">Api Key (used by <b>Unity3D</b> Projects to retrieve App Infos)</label>
                    <input type="text" id="api_key" name="api_key" class="form-control" value="">
                </div>
                <br/>
                <button type="submit" class="btn btn-success"><i class="fa fa-pencil-square-o"></i> Update</button>
                <button type="reset" class="btn btn-secondary ml-2"><i class="fa fa-refresh"></i> Reset</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
            Fields can be updated later by Workspace Admin(s).
        </div>
    </div>
</div>
@endsection
