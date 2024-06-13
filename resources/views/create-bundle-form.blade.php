@extends('master')

@section('title', 'Create Bundle ID')

@section('content')
<div class="container py-2">
    <div class="card shadow bg-dark">
        @include('layouts.dashboard.card-header', [
            'text' => 'Create Bundle ID',
            'icon' => config('icons.apple')
        ])
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
                    'icon' => 'fa-square-plus',
                    'name' => 'Create'
                ])
                <button type="reset" class="mx-2 btn btn-dark shadow font-weight-bold">
                    <i class="fa fa-refresh"></i> Reset
                </button>
            </form>
        </div>
        @include('layouts.dashboard.card-footer', [
            'text' => 'After creating the bundle id, create an app using that bundle id via <a class="font-weight-bold" href="https://appstoreconnect.apple.com/apps">App Store Connect.</a>'
        ])
    </div>
</div>
@endsection
