@extends('layouts.master')

@section('title', 'Create Bundle')

@section('content')
<div class="container py-2">
    <div class="card">
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
                    <input type="text" class="form-control" name="bundle_id" aria-describedby="basic-addon3" placeholder="id (com.companyname.appname)..." required="">
                </div>
                <div class="form-group">
                    <label for="bundle_name">Bundle ID Name</label>
                    <input type="text" id="bundle_name" name="bundle_name" class="form-control" placeholder="bundle id name..." required="">
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> Create</button>
                <button type="reset" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
            After creating the bundle id, create an app using that bundle id via <a href="https://appstoreconnect.apple.com/apps">App Store Connect</a>.
        </div>
    </div>
</div>
@endsection
