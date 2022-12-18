@extends('master')

@section('title', 'Create Bundle ID')

@section('content')
<div class="container py-2">
    <div class="card shadow bg-dark">
        <div class="card-header text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-apple fa-stack-1x" aria-hidden="true"></i>
            </span>
            Create Bundle ID
        </div>
        <div class="card-body shadow-sm">
            <form name="add-add-info-form" id="create-bundle-form" method="post" action="{{ url('dashboard/store-bundle') }}">
                @csrf
                @honeypot
                <div class="form-group">
                    <label for="bundle_id" class="text-white font-weight-bold">
                        Bundle ID
                    </label>
                    <input type="text" id="bundle_id" name="bundle_id" class="form-control shadow-sm" placeholder="id (com.companyname.appname)..." required="">
                </div>
                <div class="form-group">
                    <label for="bundle_name" class="text-white font-weight-bold">
                        Name
                    </label>
                    <input type="text" id="bundle_name" name="bundle_name" class="form-control shadow-sm" placeholder="bundle id name..." required="">
                </div>
                <br />
                @include('layouts.dashboard.button-success', [
                    'icon' => 'fa-plus-square',
                    'name' => 'Create'
                ])
                <button type="reset" class="btn btn-secondary shadow font-weight-bold">
                    <i class="fa fa-refresh"></i> Reset
                </button>
            </form>
        </div>
        @include('layouts.dashboard.card-footer', [
            'text' => 'After creating the bundle id, create an app using that bundle id via <u><a class="text-white font-weight-bold" href="https://appstoreconnect.apple.com/apps">App Store Connect.</a></u>'
        ])
    </div>
</div>
@endsection
