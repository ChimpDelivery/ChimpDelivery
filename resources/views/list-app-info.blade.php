@php( $appInfos = \App\Models\AppInfo::all() )

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Talus Studio - Add App Info</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid bg-dark">
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
    @endif
    <div class="card">
        <div class="card-header text-center font-weight-bold">
            <h4 class="font-weight-bold">Talus Studio - App Data (Count: {{ count($appInfos) }})</h4>
        </div>
        <div class="card-body">
            @csrf
            <div class="container">
                <div class="row row-cols-7">
                    <div class="col-md-1"><span class="text-dark">#</span></div>
                    <div class="col bg-dark"><span class="text-white font-weight-bold">App Icon</span></div>
                    <div class="col bg-danger"><span class="text-white font-weight-bold">App Name</span></div>
                    <div class="col bg-success"><span class="text-white font-weight-bold">App Bundle</span></div>
                    <div class="col bg-primary"><span class="text-white font-weight-bold">Facebook App ID</span></div>
                    <div class="col bg-warning"><span class="text-white font-weight-bold">Elephant ID</span></div>
                    <div class="col bg-secondary"><span class="text-white font-weight-bold">Elephant Secret</span></div>
                </div>
                @foreach($appInfos as $appInfo)
                <div class="row row-cols-7">
                    <div class="col-md-1">{{ $appInfo->id }}</div>
                    <div class="col">{{ $appInfo->app_icon }}</div>
                    <div class="col">{{ $appInfo->app_name }}</div>
                    <div class="col">{{ $appInfo->app_bundle}}</div>
                    <div class="col">{{ $appInfo->fb_app_id }}</div>
                    <div class="col">{{ $appInfo->elephant_id }}</div>
                    <div class="col">{{ $appInfo->elephant_secret }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</body>
</html>
