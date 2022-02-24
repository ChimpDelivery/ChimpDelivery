<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - Talus Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
</div>

<section class="page-content">
    @yield('content')
</section>

<footer class="page-footer font-small blue fixed-bottom">
    <div class="footer-copyright text-center py-3 text-muted bg-primary">
        <span class="text-white font-weight-bold font-italic">app count: {{ count($appInfos) }}, last update: 0 days ago</span>
    </div>
</footer>
</body>
</html>
