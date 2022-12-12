@php ($title = ($isNew) ? 'Create Workspace' : "{$workspace->name} Workspace")

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
            @if(!$isNew)
                <pre class="text-muted pull-right">ID: {{ $workspace->id }}</pre>
            @endif
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
                            <label class="custom-file-label" for="private_key">
                                {{ ($isNew) ? '' : Str::substr($workspace->appStoreConnectSetting->private_key, 0, 10) }}...
                            </label>
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
                    <a class="btn btn-primary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_app_store_signing" role="button" aria-expanded="false" aria-controls="collapse_app_store_connect_settings">
                        <i class="fa fa-apple" aria-hidden="true"></i>
                        <b>AppStore App Signing</b>
                    </a>
                </p>
                <div class="collapse" id="collapse_app_store_signing">
                    <div class="form-group">
                        <a class="badge badge-success" href="https://developer.apple.com/account/resources/certificates/list" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> Get Certificate</a>
                        <a class="badge badge-success" href="https://developer.apple.com/account/resources/profiles/list" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> Get Profile</a>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Certificate</span>
                        </div>
                        <div class="custom-file shadow-sm">
                            <label class="custom-file-label text-truncate" for="cert">
                                <span class="d-inline-block text-truncate" style="max-width: 7rem;">
                                    {{ $cert_label }}
                                </span>
                            </label>
                            <input type="file" class="custom-file-input" id="cert" name="cert" accept=".p12">
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Profile</span>
                        </div>
                        <div class="custom-file shadow-sm">
                            <label class="custom-file-label" for="provision_profile">
                                <span class="d-inline-block text-truncate" style="max-width: 7rem;">
                                    {{ $provision_label }}
                                </span>
                            </label>
                            <input type="file" class="custom-file-input" id="provision_profile" name="provision_profile" accept=".mobileprovision">
                        </div>
                    </div>
                    <div class="alert alert-warning w-100  alert-dismissible fade show">
                            <span class="badge badge-warning">
                                <i class="fa fa-bell text-white" aria-hidden="true"></i>
                            </span>
                        <small>
                            Only '<a class="font-weight-bold" href="https://developer.apple.com/library/archive/qa/qa1713/_index.html">WildCard Profiles</a>' supported for now.
                        </small>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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
                    <div class="card my-2">
                        <div class="card-header alert-primary font-weight-bold">
                            <i class="fa fa-cubes" aria-hidden="true"></i> Repository Types
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="public_repo" name="public_repo" @checked(!$isNew && $workspace->githubSetting->public_repo) />
                                    <label class="custom-control-label" for="public_repo">Public Repository</label>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="private_repo" name="private_repo" @checked(!$isNew && $workspace->githubSetting->private_repo) />
                                    <label class="custom-control-label" for="private_repo">Private Repository</label>
                                    <span class="badge alert-warning badge-pill">Subscription Required</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card my-2">
                        <div class="card-header alert-primary font-weight-bold">
                            <i class="fa fa-filter" aria-hidden="true"></i> Repository Filters
                        </div>
                        <div class="card-body">
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
                    </div>
                </div>
                <br/>
                <button type="submit" class="btn btn-success font-weight-bold shadow" @if(!$isNew) onclick="return confirm('Workspace settings will be updated, are you sure?')" @endif>
                    <i class="fa {{ ($isNew) ? 'fa-plus-square' : 'fa-pencil-square-o' }}"></i>
                    {{ ($isNew) ? 'Create Workspace' : 'Update' }}
                </button>
                @can('scan jobs')
                    <button type="submit" formaction="{{ route('scan-workspace-jobs') }}" formmethod="post" class="btn btn-secondary font-weight-bold shadow pull-right">
                        <i class="fa fa-refresh"></i>
                        Scan Jenkins
                    </button>
                @endcan
            </form>
        </div>
        <div class="card-footer text-muted">
            <span class="badge badge-primary">
                <i class="fa fa-bell text-white" aria-hidden="true"></i>
            </span>
            All fields can be changed later by "Workspace Admin" except "GitHub Organization" name.
        </div>
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
