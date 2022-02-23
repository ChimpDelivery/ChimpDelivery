@php( $appInfos = \App\Models\AppInfo::all() )

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Apps - Talus Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="container-fluid">
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
            <a class="nav-link font-weight-bold active" href="/dashboard">Apps</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" href="/dashboard/add-app-info">Create App</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold font-italic disabled" href="/dashboard/ci">Build App</a>
        </li>
    </ul>
    <br/>
    <div class="table-responsive-sm">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col" class="text-center">App Icon</th>
                <th scope="col" class="text-center">App Name</th>
                <th scope="col" class="text-center">App Bundle</th>
                <th scope="col" class="text-center">Facebook App ID</th>
                <th scope="col" class="text-center">Elephant ID</th>
                <th scope="col" class="text-center">Elephant Secret</th>
                <th scope="col" class="text-center font-italic">Edit App</th>
                <th scope="col" class="text-center font-italic">Delete App</th>
            </tr>
            </thead>
            <tbody>
            @foreach($appInfos as $appInfo)
                <tr>
                    <th scope="row" class="text-center align-middle">{{ $appInfo->id }}</th>
                    <td class="text-center align-middle"><img src="{{ asset('images/'.$appInfo->app_icon) }}" width="100px" height="100px" alt="..." class="img-thumbnail"/></td>
                    <td class="text-center align-middle">{{ $appInfo->app_name }}</td>
                    <td class="text-center align-middle">{{ $appInfo->app_bundle}}</td>
                    <td class="text-center align-middle">{{ $appInfo->fb_app_id }}</td>
                    <td class="text-center align-middle">{{ $appInfo->elephant_id }}</td>
                    <td class="text-center align-middle">{{ $appInfo->elephant_secret }}</td>
                    <td class="text-center align-middle">
                        <a href="dashboard/update-app-info/{{$appInfo->id}}">
                            <button class="btn text-white bg-warning">
                                <i class="fa fa-pencil"></i>
                            </button>
                        </a>
                    </td>
                    <td class="text-center align-middle">
                        <form name="delete-app-info-form" id="delete-app-info-form" method="post" action="{{ route('delete_app_info', ['id' => $appInfo->id ]) }}">
                        @csrf
                            <a href="dashboard/delete-app-info/{{$appInfo->id}}">
                                <button class="btn text-white bg-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </a>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

<footer class="page-footer font-small blue fixed-bottom">
    <div class="footer-copyright text-center py-3 text-muted bg-primary">
        <span class="text-white font-weight-bold font-italic">app count: {{ count($appInfos) }}, last update: 0 days ago</span>
    </div>
</footer>
</div>
</body>
</html>
