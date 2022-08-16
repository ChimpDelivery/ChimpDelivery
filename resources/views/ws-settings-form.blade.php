@extends('layouts.master')

@section('title', 'Workspace Settings')

@section('content')
<div class="container py-2">
    <div class="card">
        <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-cog fa-stack-1x" aria-hidden="true"></i>
            </span>
            {{ Auth::user()->workspace->name }} - Workspace Settings
        </div>
        <div class="card-body">
            <form name="ws-settings-form" id="ws-settings-form" method="post" action="{{ url('dashboard/ws-settings') }}">
                @csrf

                <div class="form-group">
                    <label for="appstore_private_key">Appstore - Private Key</label>
                    <input type="text" class="form-control" name="appstore_private_key" aria-describedby="basic-addon3" placeholder="{{ Auth::user()->workspace->appstore_private_key }} " required="">
                </div>

                <div class="form-group">
                    <label for="appstore_issuer_id">Appstore - Issuer ID</label>
                    <input type="text" id="appstore_issuer_id" name="appstore_issuer_id" class="form-control" placeholder="{{ Auth::user()->workspace->appstore_issuer_id }} " required="">
                </div>

                <div class="form-group">
                    <label for="appstore_kid">Appstore - KID</label>
                    <input type="text" id="appstore_kid" name="appstore_kid" class="form-control" placeholder="{{ Auth::user()->workspace->appstore_kid }} " required="">
                </div>

                <div class="form-group">
                    <label for="github_org_name">Github - Organization Name</label>
                    <input type="text" id="github_org_name" name="github_org_name" class="form-control" placeholder="{{ Auth::user()->workspace->github_org_name }} " required="">
                </div>

                <div class="form-group">
                    <label for="github_access_token">Github - Access Token</label>
                    <input type="text" id="github_access_token" name="github_access_token" class="form-control" placeholder="{{ Auth::user()->workspace->github_access_token }} " required="">
                </div>

                <div class="form-group">
                    <label for="github_template">Github - Template Project</label>
                    <input type="text" id="github_template" name="github_template" class="form-control" placeholder="{{ Auth::user()->workspace->github_template }} " required="">
                </div>

                <div class="form-group">
                    <label for="github_topic">Github - Project Topic</label>
                    <input type="text" id="github_topic" name="github_topic" class="form-control" placeholder="{{ Auth::user()->workspace->github_topic }} " required="">
                </div>

                <button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> Update</button>
                <button type="reset" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
            Workspace Settings - Footer
        </div>
    </div>
</div>
@endsection
