@php ($title = isset($appInfo) ? 'Update App' : 'Create App')
@php ($formAction = isset($appInfo) ? route('update_app_info', [ 'id' => $appInfo->id ]) : route('store_app_info'))

@extends('master')

@section('title', 'Create App')

@section('content')
<div class="container py-2">
    <div class="card shadow">
        <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-database fa-stack-1x" aria-hidden="true"></i>
            </span>
            {{ $title }}
        </div>
        <div class="card-body">
            <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{ url('dashboard/store-app-info') }}" enctype="multipart/form-data">
                @csrf
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Icon</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" onchange="preview()" class="custom-file-input" id="app_icon" name="app_icon" accept="image/png">
                        <label class="custom-file-label" for="app_icon">Choose Icon...</label>
                    </div>
                </div>
                <div class="form-group">
                    <img id="app_icon_preview" src="" width="100px" height="100px" alt="..." class="img-thumbnail" hidden />
                </div>
                <div class="form-group">
                    @if (!isset($appInfo))
                        <select name="app_name" id="app_name"
                            class="form-control selectpicker show-tick shadow"
                            data-style="btn-primary" data-live-search="true" data-dropup-auto="false" data-size="10"
                            title="• Select App ({{ count($all_appstore_apps) }})" required>

                            @each('layouts.appstore.app', $all_appstore_apps, 'appstore_app')
                        </select>
                    @else
                        <label for="app_name"><i class="fa fa-apple" aria-hidden="true"></i> AppStore Name</label>
                        <input type="text" id="app_name" name="app_name" value="{{ $appInfo->app_name }}" class="form-control shadow-sm" required="" readonly>
                    @endif
                </div>
                <div class="form-group">
                    <label for="appstore_id"><i class="fa fa-apple" aria-hidden="true"></i> AppStore ID</label>
                    <input type="text" id="appstore_id" name="appstore_id" value="{{ $appInfo->appstore_id ?? '' }}" class="form-control shadow-sm" placeholder="Select app from list..." readonly>
                </div>
                <div class="form-group">
                    <label for="app_bundle"><i class="fa fa-apple" aria-hidden="true"></i> AppStore Bundle ID</label>
                    <input type="text" id="app_bundle" name="app_bundle" value="{{ $appInfo->app_bundle ?? '' }}" class="form-control shadow-sm" placeholder="Select app from list..." readonly>
                </div>
                <div class="form-group">
                    @if (!isset($appInfo))
                        @php ($githubTitle = isset($appInfo) ? $appInfo->project_name : '• Select GitHub Project (' . count($github_projects) . ')')
                        <select name="project_name"
                            class="form-control selectpicker show-tick shadow"
                            data-style="btn-primary" data-live-search="true" data-dropup-auto="false" data-size="10"
                            title="{{ $githubTitle }}" @disabled(isset($github_auth_failed) && $github_auth_failed) required>

                            @each('layouts.github.project', $github_projects, 'github_project')
                        </select>
                    @else
                        <label for="project_name"><i class="fa fa-github" aria-hidden="true"></i> Git Project</label>
                        <input type="text" id="project_name" name="project_name" value="{{ $appInfo->project_name }}" class="form-control shadow-sm" required="" readonly>
                    @endif
                    @if(isset($github_auth_failed) && $github_auth_failed)
                        <a class="badge badge-danger text-wrap">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ERROR: Github API Auth failed!
                        </a>
                    @endif
                </div>

                <div class="form-group">
                    <label for="fb_app_id"><i class="fa fa-facebook-square" aria-hidden="true"></i> Facebook App ID</label>
                    <input type="text" id="fb_app_id" name="fb_app_id" value="{{ $appInfo->fb_app_id ?? '' }}" class="form-control shadow-sm" placeholder="facebook app id...">
                </div>
                <div class="form-group">
                    <label for="fb_client_token"><i class="fa fa-facebook-square" aria-hidden="true"></i> Facebook Client Token</label>
                    <input type="text" id="fb_client_token" name="fb_client_token" value="{{ $appInfo->fb_client_token ?? '' }}" class="form-control shadow-sm" placeholder="facebook client token...">
                </div>
                <div class="form-group">
                    <label for="ga_id">GA ID</label>
                    <input type="text" id="ga_id" name="ga_id" value="{{ $appInfo->ga_id ?? '' }}" class="form-control shadow-sm" placeholder="game analytics id...">
                </div>
                <div class="form-group">
                    <label for="ga_secret">GA Secret</label>
                    <input type="text" id="ga_secret" name="ga_secret" value="{{ $appInfo->ga_secret ?? '' }}" class="form-control shadow-sm" placeholder="game analytics secret...">
                </div>
                <br/>
                @can('delete app')
                    @isset($appInfo)
                        <button class="btn btn-danger float-right font-weight-bold shadow" type="submit" onclick="return confirm('Are you sure?')" formaction="{{ route('delete_app_info', ['id' => $appInfo->id ]) }}" formmethod="post">
                            <i class="fa fa-trash"></i>
                            Delete
                        </button>
                    @endisset
                @endcan
                <button type="submit" class="btn btn-success font-weight-bold shadow" formaction="{{ $formAction }}">
                    <i class="fa {{ isset($appInfo) ? 'fa-pencil-square-o' : 'fa-plus-square' }}"></i>
                    {{ $title }}
                </button>
                @includeWhen((isset($appInfo) && Auth::user()->workspace->id === \App\Models\Workspace::INTERNAL_WS_ID), 'layouts.appinfo.create-privacy')
            </form>
        </div>
        <div class="card-footer text-muted">
            <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
            @if (!isset($appInfo))
                If you don't see the app in the list, make sure there is an app on <a href="https://appstoreconnect.apple.com/apps">App Store Connect</a>. (And at least one version that is in the "Prepare For Submission")
            @else
                AppStore and GitHub related settings can not be changed.
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    // get selected app data
    $('select[name=app_name]').change(function () {
        let selectedOption = $('option:selected', this);
        let appStoreId = selectedOption.attr('data-appstore-id');
        let appStoreBundle = selectedOption.attr('data-appstore-bundle');

        updateAppstoreFields(appStoreId, appStoreBundle);
    });

    function updateAppstoreFields(appStoreId, appStoreBundle) {
        let appstoreIdField = document.getElementById('appstore_id');
        appstoreIdField.value = appStoreId;

        let appstoreBundleField = document.getElementById('app_bundle');
        appstoreBundleField.value = appStoreBundle;
    }

    function preview() {
        document.getElementById('app_icon_preview').src = URL.createObjectURL(event.target.files[0]);
        document.getElementById('app_icon_preview').hidden = false
    }
</script>
@endsection
