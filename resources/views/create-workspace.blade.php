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
            <form name="create-workspace-form" id="create-workspace-form" method="post" action="{{ url('dashboard/store-workspace') }}">
                @csrf
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-apple" aria-hidden="true"></i>
                    General
                </div>
                <div class="form-group">
                    <label for="name">Workspace Name</label>
                    <input type="text" class="form-control" name="name" aria-describedby="basic-addon3" value="" required="">
                </div>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-apple" aria-hidden="true"></i>
                    App Store Connect Settings (to create Appstore Connect API Keys: <a href="https://appstoreconnect.apple.com/access/api">Click Here</a>)
                </div>
                <div class="form-group">
                    <label for="appstore_private_key">Private Key</label>
                    <input type="text" class="form-control" name="appstore_private_key" aria-describedby="basic-addon3" value="">
                </div>
                <div class="form-group">
                    <label for="appstore_issuer_id">Issuer ID</label>
                    <input type="text" id="appstore_issuer_id" name="appstore_issuer_id" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="appstore_kid">Key ID</label>
                    <input type="text" id="appstore_kid" name="appstore_kid" class="form-control" value="">
                </div>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-apple" aria-hidden="true"></i>
                    Apple Settings (to create App Specific Password: <a href="https://appleid.apple.com/account/manage">Click Here</a>)
                </div>
                <div class="form-group">
                    <label for="apple_usermail">User Mail</label>
                    <input type="text" id="apple_usermail" name="apple_usermail" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="apple_app_pass">App Specific Password</label>
                    <input type="text" id="apple_app_pass" name="apple_app_pass" class="form-control" value="">
                </div>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-github" aria-hidden="true"></i>
                    Github Settings (to create Github Personal Access Token: <a href="https://github.com/settings/tokens">Click Here</a>)
                </div>
                <div class="form-group">
                    <label for="github_org_name">Organization Name</label>
                    <input type="text" id="github_org_name" name="github_org_name" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="github_access_token">Personal Access Token</label>
                    <input type="text" id="github_access_token" name="github_access_token" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="github_template">Template Project</label>
                    <input type="text" id="github_template" name="github_template" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="github_topic">Project Topic</label>
                    <input type="text" id="github_topic" name="github_topic" class="form-control" value="">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-plus-square"></i> Create</button>
                <button type="reset" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            <i class="fa fa-apple" aria-hidden="true"></i>
        </div>
    </div>
</div>
@endsection
