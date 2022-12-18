@php ($title = isset($appInfo) ? 'Update' : 'Create')

@extends('master')

@section('title', $title . " App")

@section('content')
<div class="container py-2">
    <div class="card shadow bg-dark">
        <div class="card-header text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-database fa-stack-1x" aria-hidden="true"></i>
            </span>
            {{ $title }} App
        </div>
        <div class="card-body shadow-sm">
            <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{ $formAction ?? route('store_app_info') }}" enctype="multipart/form-data">
                @csrf
                @honeypot
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

                            @each('layouts.appstore.option-app', $all_appstore_apps, 'appstore_app')
                        </select>
                    @else
                        <label for="app_name" class="text-white font-weight-bold">
                            <i class="fa fa-apple" aria-hidden="true"></i> AppStore Name
                        </label>
                        <input type="text" id="app_name" name="app_name" value="{{ $appInfo->app_name }}" class="form-control shadow-sm" required="" readonly>
                    @endif
                </div>
                <div class="form-group">
                    <label for="appstore_id" class="text-white font-weight-bold">
                        <i class="fa fa-apple" aria-hidden="true"></i> AppStore ID
                    </label>
                    <input type="text" id="appstore_id" name="appstore_id" value="{{ $appInfo->appstore_id ?? '' }}" class="form-control shadow-sm" placeholder="Select app from list..." readonly>
                </div>
                <div class="form-group">
                    <label for="app_bundle" class="text-white font-weight-bold">
                        <i class="fa fa-apple" aria-hidden="true"></i> AppStore Bundle ID
                    </label>
                    <input type="text" id="app_bundle" name="app_bundle" value="{{ $appInfo->app_bundle ?? '' }}" class="form-control shadow-sm" placeholder="Select app from list..." readonly>
                </div>
                <div class="form-group">
                    @if (!isset($appInfo))
                        @php ($githubTitle = '• Select GitHub Project (' . count($github_projects) . ')')
                        <select name="project_name"
                            class="form-control selectpicker show-tick shadow"
                            data-style="btn-primary" data-live-search="true" data-dropup-auto="false" data-size="10"
                            title="{{ $githubTitle }}" @disabled(isset($github_auth_failed) && $github_auth_failed) required>

                            @each('layouts.github.option-project', $github_projects, 'github_project')
                        </select>
                    @else
                        <label class="text-white font-weight-bold" for="project_name">
                            <i class="fa fa-github" aria-hidden="true"></i> Git Project
                        </label>
                        <input type="text" id="project_name" name="project_name" value="{{ $appInfo->project_name }}" class="form-control shadow-sm" required="" readonly>
                    @endif
                    @includeWhen(isset($github_auth_failed) && $github_auth_failed, 'errors.github.auth-failed')
                </div>
                <p>
                    <a class="btn btn-primary btn-block text-left shadow" data-toggle="collapse" href="#collapse_keys" role="button" aria-expanded="true" aria-controls="collapse_keys">
                        <i class="fa fa-key" aria-hidden="true"></i>
                        <b>SDK Keys</b>
                    </a>
                </p>
                <div class="collapse" id="collapse_keys">
                    <div class="form-group row">
                        <label for="fb_app_id" class="col-md-3 col-form-label text-white font-weight-bold">
                            <i class="fa fa-facebook-square" aria-hidden="true"></i> FB App ID
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="fb_app_id" name="fb_app_id" value="{{ $appInfo->fb_app_id ?? '' }}" class="form-control shadow-sm" placeholder="facebook app id...">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fb_client_token" class="col-md-3 col-form-label text-white font-weight-bold">
                            <i class="fa fa-facebook-square" aria-hidden="true"></i> FB Client Token
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="fb_client_token" name="fb_client_token" value="{{ $appInfo->fb_client_token ?? '' }}" class="form-control shadow-sm" placeholder="facebook client token...">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ga_id" class="col-md-3 col-form-label text-white font-weight-bold">
                            GA ID
                        </label>
                        <div class="col-md-9">
                            <input type="text" id="ga_id" name="ga_id" value="{{ $appInfo->ga_id ?? '' }}" class="form-control shadow-sm" placeholder="game analytics id...">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ga_secret" class="col-md-3 col-form-label text-white font-weight-bold">GA Secret</label>
                        <div class="col-md-9">
                            <input type="text" id="ga_secret" name="ga_secret" value="{{ $appInfo->ga_secret ?? '' }}" class="form-control shadow-sm" placeholder="game analytics secret...">
                        </div>
                    </div>
                </div>
                <br/>
                @can('delete app')
                    @isset($appInfo)
                        <button class="btn btn-danger float-right font-weight-bold shadow" type="submit" onclick="return confirm('The app will be deleted, are you sure?')" formaction="{{ route('delete_app_info', ['id' => $appInfo->id ]) }}" formmethod="post">
                            <i class="fa fa-trash-o fa-lg"></i>
                        </button>
                    @endisset
                @endcan
                @include('layouts.dashboard.button-success', [
                    'icon' => isset($appInfo) ? 'fa-pencil-square-o' : 'fa-plus-square',
                    'name' => $title
                ])
                @includeWhen((isset($appInfo) && Auth::user()->workspace->id === \App\Models\Workspace::INTERNAL_WS_ID), 'layouts.appinfo.create-privacy')
            </form>
        </div>
        @php ($footerText = !isset($appInfo)
            ? "Make sure there is an app on <u><a href='https://appstoreconnect.apple.com/apps' class='text-white'>App Store Connect</a></u>. (And at least one version that is in the <u>Prepare For Submission</u>)"
            : "<b>AppStore</b> and <b>GitHub</b> related settings can not be changed.")
        @include('layouts.dashboard.card-footer', ['text' => $footerText ])
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
