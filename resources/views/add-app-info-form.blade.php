@extends('layouts.master')

@section('title', 'Create App')

@section('content')
<div class="container py-2">
    <div class="card shadow">
        <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-plus fa-stack-1x" aria-hidden="true"></i>
            </span>
            Create App
        </div>
        <div class="card-body">
            <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{url('dashboard/store-app-info')}}" enctype="multipart/form-data">
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
                    <select name="app_name" id="app_name"
                        class="form-control selectpicker show-tick" 
                        data-style="btn-primary" data-live-search="true" data-dropup-auto="false" data-size="10"
                        title="Select App ({{ count($all_appstore_apps) }})...">

                        @foreach($all_appstore_apps as $appInfo)
                        <option data-icon="fa fa-apple" data-appstore-bundle="{{ $appInfo->app_bundle }}" data-appstore-id="{{ $appInfo->appstore_id }}">
                            {{ $appInfo->app_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="appstore_id">Appstore ID</label>
                    <input type="text" id="appstore_id" name="appstore_id" class="form-control shadow-sm" required="" placeholder="Select app from list..." readonly>
                </div>
                <div class="form-group">
                    <label for="app_bundle">App Bundle</label>
                    <input type="text" id="app_bundle" name="app_bundle" class="form-control shadow-sm" required="" placeholder="Select app from list..." readonly>
                </div>
                <div class="form-group">
                    <select name="project_name" 
                        class="form-control selectpicker show-tick" 
                        data-style="btn-primary" data-live-search="true" data-dropup-auto="false" data-size="10"
                        title="Select Github Project ({{ count($github_projects) }})..." {{ ($github_auth_failed) ? 'disabled' : '' }}>
                        
                        @foreach($github_projects as $gitProject)
                        <option data-icon="fa fa-github" data-subtext="{{ $gitProject->size }}">{{ $gitProject->name }}</option>
                        @endforeach
                    </select>
                    @if($github_auth_failed)
                    <a class="badge badge-danger text-wrap">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ERROR: Github API Auth failed!
                    </a>
                    @endif
                </div>
                <div class="form-group">
                    <label for="fb_app_id">Facebook App ID</label>
                    <input type="text" id="fb_app_id" name="fb_app_id" class="form-control shadow-sm" placeholder="facebook app id...">
                </div>
                <div class="form-group">
                    <label for="ga_id">GA ID</label>
                    <input type="text" id="ga_id" name="ga_id" class="form-control shadow-sm" placeholder="game analytics id...">
                </div>
                <div class="form-group">
                    <label for="ga_secret">GA Secret</label>
                    <input type="text" id="ga_secret" name="ga_secret" class="form-control shadow-sm" placeholder="game analytics secret...">
                </div>
                <button type="submit" class="btn btn-success font-weight-bold shadow"><i class="fa fa-plus-square"></i> Create</button>
                <button type="reset" class="btn btn-secondary font-weight-bold shadow"><i class="fa fa-refresh"></i> Reset</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
            If you don't see the app in the list, make sure there is an app on <a href="https://appstoreconnect.apple.com/apps">App Store Connect</a>. (And at least one version that is in the "Prepare For Submission")
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
