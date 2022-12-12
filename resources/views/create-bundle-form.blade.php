@extends('master')

@section('title', 'Create Bundle ID')

@section('content')
<div class="container py-2">
    <div class="card shadow">
        <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-apple fa-stack-1x" aria-hidden="true"></i>
            </span>
            Create Bundle ID
        </div>
        <div class="card-body">
            <form name="add-add-info-form" id="create-bundle-form" method="post" action="{{ url('dashboard/store-bundle') }}">
                @csrf
                <div class="form-group">
                    <label for="bundle_id">Bundle ID</label>
                    <input type="text" id="bundle_id" name="bundle_id" class="form-control shadow-sm" placeholder="id (com.companyname.appname)..." required="">
                </div>
                <div class="form-group">
                    <label for="bundle_name">Bundle ID Name</label>
                    <input type="text" id="bundle_name" name="bundle_name" class="form-control shadow-sm" placeholder="bundle id name..." required="">
                </div>
                <br />
                <button type="submit" class="btn btn-success shadow font-weight-bold"><i class="fa fa-plus-square"></i> Create</button>
                <button type="reset" class="btn btn-secondary shadow font-weight-bold"><i class="fa fa-refresh"></i> Reset</button>
            </form>
        </div>
        @include('layouts.dashboard.card-footer', [
            'text' => 'After creating the bundle id, create an app using that bundle id via <a href="https://appstoreconnect.apple.com/apps">App Store Connect</a>.'
        ])
    </div>
</div>
@endsection
