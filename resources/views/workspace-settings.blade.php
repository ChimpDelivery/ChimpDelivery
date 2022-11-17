@php ($title = ($isNew === true) ? 'Create Workspace' : $workspace->name . ' Workspace')

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
        </div>
        <div class="card-body">
            <form name="create-workspace-form" id="create-workspace-form" method="post" action="{{ url('dashboard/workspace-settings') }}" enctype="multipart/form-data">
                @csrf
                <p>
                    <a class="btn btn-primary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_workspace_settings" role="button" aria-expanded="true" aria-controls="collapse_workspace_settings">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        <b>Board Settings</b>
                    </a>
                </p>
                <div class="collapse show" id="collapse_workspace_settings">
                    <div class="form-group">
                        <label for="name">Workspace Name</label>
                        <input type="text" class="form-control shadow-sm" id="name" name="name" aria-describedby="basic-addon3" value="{{ ($isNew) ? '' : $workspace->name }}" required="">
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_app_store_connect_settings" role="button" aria-expanded="false" aria-controls="collapse_app_store_connect_settings">
                        <i class="fa fa-apple" aria-hidden="true"></i>
                        <b>AppStore API</b>
                    </a>
                </p>
                <div class="collapse" id="collapse_app_store_connect_settings">
                    <div class="form-group">
                        <a class="badge badge-success" href="https://appstoreconnect.apple.com/access/api" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> Get Keys</a>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Private Key</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="private_key" name="private_key" accept=".p8">
                            <label class="custom-file-label" for="private_key">Choose...</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kid">Key ID</label>
                        <input type="text" id="kid" name="kid" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->appStoreConnectSetting->kid }}">
                    </div>
                    <div class="form-group">
                        <label for="issuer_id">Issuer ID</label>
                        <input type="text" id="issuer_id" name="issuer_id" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->appStoreConnectSetting->issuer_id }}">
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_apple_settings" role="button" aria-expanded="false" aria-controls="collapse_apple_settings">
                        <i class="fa fa-apple" aria-hidden="true"></i>
                        <b>TestFlight API</b>
                    </a>
                </p>
                <div class="collapse" id="collapse_apple_settings">
                    <div class="form-group">
                        <a class="badge badge-success" href="https://appleid.apple.com/account/manage" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> Get Password</a>
                    </div>
                    <div class="form-group">
                        <label for="usermail">User Mail</label>
                        <input type="text" id="usermail" name="usermail" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->appleSetting->usermail }}">
                    </div>
                    <div class="form-group">
                        <label for="app_specific_pass">App Specific Password</label>
                        <input type="text" id="app_specific_pass" name="app_specific_pass" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->appleSetting->app_specific_pass }}">
                    </div>
                </div>

                <p>
                    <a class="btn btn-primary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_github_settings" role="button" aria-expanded="false" aria-controls="collapse_github_settings">
                        <i class="fa fa-github" aria-hidden="true"></i>
                        <b>GitHub API</b>
                    </a>
                </p>
                <div class="collapse" id="collapse_github_settings">
                    <div class="form-group">
                        <a class="badge badge-success" href="https://github.com/settings/tokens" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> Get Token</a>
                    </div>
                    <div class="form-group">
                        <label for="personal_access_token">Personal Access Token</label>
                        <input type="text" id="personal_access_token" name="personal_access_token" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->githubSetting->personal_access_token }}">
                    </div>
                    <div class="form-group">
                        <label for="organization_name">Organization Name</label>
                        <input type="text" id="organization_name" name="organization_name" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->githubSetting->organization_name }}" @readonly(!$isNew)>
                        <small class="form-text text-muted">
                            The name of the GitHub Organization that contains the projects to build.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="template_name">Template Project</label>
                        <input type="text" id="template_name" name="template_name" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->githubSetting->template_name }}">
                    </div>
                    <div class="form-group">
                        <label for="topic_name">Project Topic</label>
                        <input type="text" id="topic_name" name="topic_name" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->githubSetting->topic_name }}">
                        <small class="form-text text-muted">
                            GitHub Repository topic name to filter GitHub Projects in organization.
                        </small>
                    </div>
                </div>
                <br/>
                <button type="submit" class="btn btn-success font-weight-bold shadow">
                    <i class="fa {{ ($isNew) ? 'fa-plus-square' : 'fa-pencil-square-o' }}"></i>
                    {{ ($isNew) ? 'Create Workspace' : 'Update Workspace' }}
                </button>
                @can('scan jobs')
                    <a href="{{ route('scan-workspace-jobs') }}" type="button" class="btn btn-secondary font-weight-bold shadow pull-right">
                        <i class="fa fa-refresh"></i>
                        Scan Jenkins
                    </a>
                @endcan
            </form>
        </div>
        <div class="card-footer text-muted">
            <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
            All fields can be changed later by "Workspace Admin" except "GitHub Organization" name.
        </div>
    </div>
</div>
@livewireScripts
@endsection

@section('scripts')
<script type="text/javascript">
    $('input[type="file"]').change(function(e) {
        let fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });
</script>
@endsection
