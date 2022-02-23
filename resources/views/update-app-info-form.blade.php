@php($appInfos = \App\Models\AppInfo::all())
@php($appInfo = \App\Models\AppInfo::find($id))

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Create App - Talus Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
    @endif
    <ul class="nav justify-content-end nav-pills">
        <li class="nav-item">
            <a class="nav-link font-weight-bold" href="/dashboard">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" href="/dashboard">Apps</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold active" href="/dashboard/add-app-info">Create App</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold font-italic disabled" href="/dashboard/ci">Build App</a>
        </li>
    </ul>
    <br/>
    <div class="card">
        <div class="card-body">
            <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{url('dashboard/update-app-info/'.$id.'/update')}}">
                @csrf
                <div class="form-group">
                    <label for="app_name">App Icon</label>
                    <input type="text" id="title" name="app_icon" class="form-control" required="" value="{{ $appInfo->app_icon }}">
                </div>
                <div class="form-group">
                    <label for="app_name">App Name</label>
                    <input type="text" id="title" name="app_name" class="form-control" required="" value="{{ $appInfo->app_name }}">
                </div>
                <div class="form-group">
                    <label for="app_bundle">App Bundle</label>
                    <input type="text" id="form-control" name="app_bundle" class="form-control" required="" value="{{ $appInfo->app_bundle }}">
                </div>
                <div class="form-group">
                    <label for="fb_app_id">Facebook App ID</label>
                    <input type="text" id="form-control" name="fb_app_id" class="form-control" required="" value="{{ $appInfo->fb_app_id }}">
                </div>
                <div class="form-group">
                    <label for="elephant_id">Elephant ID</label>
                    <input type="text" id="form-control" name="elephant_id" class="form-control" required="" value="{{ $appInfo->elephant_id }}">
                </div>
                <div class="form-group">
                    <label for="elephant_secret">Elephant Secret</label>
                    <input type="text" id="form-control" name="elephant_secret" class="form-control" required="" value="{{ $appInfo->elephant_secret }}">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>
</div>
<footer class="page-footer font-small blue fixed-bottom">
    <div class="footer-copyright text-center py-3 text-muted bg-primary">
        <span class="text-white font-weight-bold font-italic">app count: {{ count($appInfos) }}, last update: 0 days ago</span>
    </div>
</footer>
</body>
</html>
