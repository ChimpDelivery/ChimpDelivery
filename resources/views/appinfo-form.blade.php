@php ($title = isset($appInfo) ? 'Update' : 'Create')

@extends('master')

@section('title', $title . " App")

@section('content')
    <div class="container py-2">
        <div class="card shadow bg-dark">
            @include('layouts.dashboard.card-header', [
                'text' => "{$title} App",
                'icon' => 'fa-database'
            ])
            <div class="card-body shadow-sm">
                <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{ $formAction ?? route('store_app_info') }}" enctype="multipart/form-data">
                    @csrf
                    @honeypot
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Icon</span>
                        </div>
                        <div class="custom-file">
                            <label class="custom-file-label" for="app_icon">
                            <span class="col-8 d-inline-block text-truncate text-secondary font-weight-bold">
                                {{ $appInfo->app_icon ?? 'Choose Icon...' }}
                            </span>
                            </label>
                            <input type="file" onchange="preview()" class="custom-file-input" id="app_icon" name="app_icon" accept="image/png">
                        </div>
                    </div>
                    <div class="form-group">
                        <img id="app_icon_preview" src="" width="100px" height="100px" alt="..." class="img-thumbnail" hidden />
                    </div>
                    <div class="form-group row">
                        @if (!isset($appInfo))
                            <div class="input-group col-md-12">
                                <select name="app_name" id="app_name"
                                        class="form-control selectpicker show-tick shadow"
                                        data-style="btn-primary" data-live-search="true" data-dropup-auto="false" data-size="10"
                                        title="➤ Select App ({{ count($all_appstore_apps) }})" required>

                                    @each('layouts.appstore.option-app', $all_appstore_apps, 'appstore_app')
                                </select>
                            </div>
                        @else
                            <label for="app_name" class="col-md-3 col-form-label text-white font-weight-bold">
                                <i class="fa fa-apple" aria-hidden="true"></i> App Name
                            </label>
                            <div class="input-group col-md-9">
                                <input class="form-control shadow-sm"  type="text" id="app_name" name="app_name" value="{{ $appInfo->app_name }}" required="" readonly>
                                @if (auth()->user()->isInternal())
                                    @livewire('app-privacy-creator', ['appInfo' => $appInfo])
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label for="appstore_id" class="col-md-3 col-form-label text-white font-weight-bold">
                            <i class="fa fa-apple" aria-hidden="true"></i> App ID
                        </label>
                        <div class="input-group col-md-9">
                            <input type="text" id="appstore_id" name="appstore_id" value="{{ $appInfo->appstore_id ?? '' }}" class="form-control shadow-sm" placeholder="Select app from list..." readonly>
                            @includeWhen(isset($appInfo), 'layouts.dashboard.copy-clipboard-button', ['input' => 'appstore_id'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="app_bundle" class="col-md-3 col-form-label text-white font-weight-bold">
                            <i class="fa fa-apple" aria-hidden="true"></i> App Bundle ID
                        </label>
                        <div class="input-group col-md-9">
                            <input type="text" id="app_bundle" name="app_bundle" value="{{ $appInfo->app_bundle ?? '' }}" class="form-control shadow-sm" placeholder="Select app from list..." readonly>
                            @includeWhen(isset($appInfo), 'layouts.dashboard.copy-clipboard-button', ['input' => 'app_bundle'])
                        </div>
                    </div>
                    <div class="form-group row">
                        @if (!isset($appInfo))
                            @php ($githubTitle = '➤ Select Project Source (' . count($github_projects) . ')')
                            <div class="input-group col-md-12">
                                <select name="project_name"
                                        class="form-control selectpicker show-tick shadow"
                                        data-style="btn-primary" data-live-search="true" data-dropup-auto="false" data-size="10"
                                        title="{{ $githubTitle }}" @disabled(isset($github_auth_failed) && $github_auth_failed) required>

                                    @each('layouts.github.option-project', $github_projects, 'github_project')
                                </select>
                            </div>
                        @else
                            <label for="project_name" class="col-md-3 col-form-label text-white font-weight-bold">
                                <i class="fa fa-code-fork" aria-hidden="true"></i> Project Source
                            </label>
                            <div class="input-group col-md-9">
                                <input type="text" id="project_name" name="project_name" value="{{ $appInfo->project_name }}" class="form-control shadow-sm" required="" readonly>
                                @include('layouts.dashboard.copy-clipboard-button', ['input' => 'project_name'])
                            </div>
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
                            <div class="input-group col-md-9">
                                <input type="text" id="fb_app_id" name="fb_app_id" value="{{ $appInfo->fb_app_id ?? '' }}" class="form-control shadow-sm" placeholder="facebook app id...">
                                @if(isset($appInfo) && auth()->user()->isInternal() && !empty($appInfo->fb_app_id))
                                    @livewire('fb-app-ads', [ 'appInfo' => $appInfo ])
                                @endif
                                @includeWhen(isset($appInfo), 'layouts.dashboard.copy-clipboard-button', ['input' => 'fb_app_id'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fb_client_token" class="col-md-3 col-form-label text-white font-weight-bold">
                                <i class="fa fa-facebook-square" aria-hidden="true"></i> FB Client Token
                            </label>
                            <div class="input-group col-md-9">
                                <input type="text" id="fb_client_token" name="fb_client_token" value="{{ $appInfo->fb_client_token ?? '' }}" class="form-control shadow-sm" placeholder="facebook client token...">
                                @includeWhen(isset($appInfo), 'layouts.dashboard.copy-clipboard-button', ['input' => 'fb_client_token'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ga_id" class="col-md-3 col-form-label text-white font-weight-bold">
                                GA ID
                            </label>
                            <div class="input-group col-md-9">
                                <input type="text" id="ga_id" name="ga_id" value="{{ $appInfo->ga_id ?? '' }}" class="form-control shadow-sm" placeholder="game analytics id...">
                                @includeWhen(isset($appInfo), 'layouts.dashboard.copy-clipboard-button', ['input' => 'ga_id'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ga_secret" class="col-md-3 col-form-label text-white font-weight-bold">GA Secret</label>
                            <div class="input-group col-md-9">
                                <input type="text" id="ga_secret" name="ga_secret" value="{{ $appInfo->ga_secret ?? '' }}" class="form-control shadow-sm" placeholder="game analytics secret...">
                                @includeWhen(isset($appInfo), 'layouts.dashboard.copy-clipboard-button', ['input' => 'ga_secret'])
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
                        'icon' => isset($appInfo) ? 'fa-pencil-square-o' : 'fa-check-square-o',
                        'name' => $title
                    ])
                </form>
            </div>
            @php ($footerText = !isset($appInfo)
                ? "Make sure there is an app on <a href='https://appstoreconnect.apple.com/apps'>App Store Connect</a> with <b>Prepare For Submission</b> state."
                : "<b>AppStore</b> and <b>GitHub</b> related settings can not be changed.")
            @include('layouts.dashboard.card-footer', ['text' => $footerText ])
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/views/appinfo-form.js') }}"></script>
@endsection
