@php ($title = $isNew == true ? 'Create' : 'Update')

@extends('layouts.master')

@section('title', $title . ' Workspace')

@section('content')
<div class="container py-2">
    <div class="card">
        <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-globe fa-stack-1x" aria-hidden="true"></i>
            </span>
            {{ $title }} Workspace
        </div>
        <div class="card-body">
            <form name="create-workspace-form" id="create-workspace-form" method="post" action="{{ url('dashboard/workspace-settings') }}" enctype="multipart/form-data">
                @csrf
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-apple" aria-hidden="true"></i>
                    <b>Board Settings</b>
                </div>
                <div class="form-group">
                    <label for="name">Workspace Name</label>
                    <input type="text" class="form-control" name="name" aria-describedby="basic-addon3" value="{{ ($isNew) ? '' : $workspace->name }}" required="">
                </div>
                <div class="form-group">
                    <label for="api_key">Workspace API Key</label>
                    <input type="text" id="api_key" name="api_key" class="form-control" value="{{ ($isNew) ? '' : $workspace->api_key }}">
                </div>
                <br/>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-apple" aria-hidden="true"></i>
                    <b>App Store Connect Auth</b>
                    <a class="float-right badge badge-success text-nowrap" href="https://appstoreconnect.apple.com/access/api"><i class="fa fa-external-link" aria-hidden="true"></i> Get Keys</a>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Private Key</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="private_key" name="private_key" accept=".p8">
                        <label class="custom-file-label" for="private_key">Choose file</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="issuer_id">Issuer ID</label>
                    <input type="text" id="issuer_id" name="issuer_id" class="form-control" value="{{ ($isNew) ? '' : $workspace->appStoreConnectSetting->issuer_id }}">
                </div>
                <div class="form-group">
                    <label for="kid">Key ID</label>
                    <input type="text" id="kid" name="kid" class="form-control" value="{{ ($isNew) ? '' : $workspace->appStoreConnectSetting->kid }}">
                </div>
                <br/>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-apple" aria-hidden="true"></i>
                    <b>TestFlight Auth</b>
                    <a class="float-right badge badge-success text-nowrap" href="https://appleid.apple.com/account/manage"><i class="fa fa-external-link" aria-hidden="true"></i> Get Password</a>
                </div>
                <div class="form-group">
                    <label for="usermail">User Mail</label>
                    <input type="text" id="usermail" name="usermail" class="form-control" value="{{ ($isNew) ? '' : $workspace->appleSetting->usermail }}">
                </div>
                <div class="form-group">
                    <label for="app_specific_pass">App Specific Password</label>
                    <input type="text" id="app_specific_pass" name="app_specific_pass" class="form-control" value="{{ ($isNew) ? '' : $workspace->appleSetting->app_specific_pass }}">
                </div>
                <br/>
                <div class="alert alert-primary" role="alert">
                    <i class="fa fa-github" aria-hidden="true"></i>
                    <b>Github Auth</b>
                    <a class="float-right badge badge-success text-nowrap" href="https://github.com/settings/tokens"><i class="fa fa-external-link" aria-hidden="true"></i> Get Token</a>
                </div>
                <div class="form-group">
                    <label for="personal_access_token">Personal Access Token</label>
                    <input type="text" id="personal_access_token" name="personal_access_token" class="form-control" value="{{ ($isNew) ? '' : $workspace->githubSetting->personal_access_token }}">
                </div>
                <div class="form-group">
                    <label for="organization_name">Organization Name</label>
                    <input type="text" id="organization_name" name="organization_name" class="form-control" value="{{ ($isNew) ? '' : $workspace->githubSetting->organization_name }}">
                </div>
                <div class="form-group">
                    <label for="template_name">Template Project</label>
                    <input type="text" id="template_name" name="template_name" class="form-control" value="{{ ($isNew) ? '' : $workspace->githubSetting->template_name }}">
                </div>
                <div class="form-group">
                    <label for="topic_name">Project Topic</label>
                    <input type="text" id="topic_name" name="topic_name" class="form-control" value="{{ ($isNew) ? '' : $workspace->githubSetting->topic_name }}">
                </div>
                <br/>
                <button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> {{ $title }}</button>
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

@section('scripts')
<script type="text/javascript">
    $('input[type="file"]').change(function(e) {
        let fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });
</script>
@endsection
