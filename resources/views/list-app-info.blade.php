@php( $appInfos = \App\Models\AppInfo::paginate(10) )

@extends('layouts.master')

@section('title', 'Apps')

@section('content')
<div class="table-responsive-sm">
    <table class="table table-striped table-hover">
        <thead>
        <tr class="bg-primary text-light">
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center">App Icon</th>
            <th scope="col" class="text-center">Appstore ID</th>
            <th scope="col" class="text-center">App Name</th>
            <th scope="col" class="text-center">Bundle ID</th>
            <th scope="col" class="text-center font-italic">Build App</th>
            <th scope="col" class="text-center font-italic">Edit App</th>
            <th scope="col" class="text-center font-italic">Delete App</th>
        </tr>
        </thead>
        <tbody>
        @foreach($appInfos as $appInfo)
            <tr>
                <th scope="row" class="text-center align-middle">{{ $appInfo->id }}</th>
                <td class="text-center align-middle">
                    <img src="{{ asset('images/'.$appInfo->app_icon) }}" width="100px" height="100px" alt="..." class="img-thumbnail"/>
                </td>
                <td class="text-center align-middle">
                    <a href="https://appstoreconnect.apple.com/apps/{{$appInfo->appstore_id}}/testflight">
                        {{ $appInfo->appstore_id }}
                    </a>
                </td>
                <td class="text-center align-middle">{{ $appInfo->app_name }}</td>
                <td class="text-center align-middle">{{ $appInfo->app_bundle}}</td>
                <td class="text-center align-middle">
                    <a href="dashboard/build-app/{{$appInfo->id}}">
                        <button class="btn text-white bg-success">
                            <i class="fa fa-plus-square"></i>
                        </button>
                    </a>
                </td>
                <td class="text-center align-middle">
                    <a href="dashboard/update-app-info/{{$appInfo->id}}">
                        <button class="btn text-white bg-warning">
                            <i class="fa fa-pencil"></i>
                        </button>
                    </a>
                </td>
                <td class="text-center align-middle">
                    <form name="delete-app-info-form" id="delete-app-info-form" method="post"
                          action="{{ route('delete_app_info', ['id' => $appInfo->id ]) }}">
                        @csrf
                        <a href="dashboard/delete-app-info/{{$appInfo->id}}">
                            <button class="btn text-white bg-danger" onclick="return confirm('Are you sure?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </a>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $appInfos->links() }}
    </div>
</div>
@endsection
