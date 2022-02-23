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
<div class="container mt-4">
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
            Talus Studio - Generate App Data
        </div>
        <div class="card-body">
            <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{url('dashboard/store-app-info')}}">
                @csrf
                <div class="form-group">
                    <label for="app_name">App Icon</label>
                    <input type="text" id="title" name="app_icon" class="form-control" required="" placeholder="App Icon path...">
                </div>
                <div class="form-group">
                    <label for="app_name">App Name</label>
                    <input type="text" id="title" name="app_name" class="form-control" required="" placeholder="AppStore app name...">
                </div>
                <div class="form-group">
                    <label for="app_bundle">App Bundle</label>
                    <input type="text" id="form-control" name="app_bundle" class="form-control" required="" placeholder="AppStore bundle identifier...">
                </div>
                <div class="form-group">
                    <label for="fb_app_id">Facebook App ID</label>
                    <input type="text" id="form-control" name="fb_app_id" class="form-control" required="" placeholder="Facebook app id...">
                </div>
                <div class="form-group">
                    <label for="elephant_id">Elephant ID</label>
                    <input type="text" id="form-control" name="elephant_id" class="form-control" required="" placeholder="Elephant ID...">
                </div>
                <div class="form-group">
                    <label for="elephant_secret">Elephant Secret</label>
                    <input type="text" id="form-control" name="elephant_secret" class="form-control" required="" placeholder="Elephant Secret...">
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
