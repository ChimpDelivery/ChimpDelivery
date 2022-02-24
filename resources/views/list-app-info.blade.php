@php( $appInfos = \App\Models\AppInfo::all() )

@extends('layouts.master')

@section('title', 'Apps')

@section('content')
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
                <td class="text-center align-middle"><img src="{{ asset('images/'.$appInfo->app_icon) }}" width="100px"
                                                          height="100px" alt="..." class="img-thumbnail"/></td>
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
                    <form name="delete-app-info-form" id="delete-app-info-form" method="post"
                          action="{{ route('delete_app_info', ['id' => $appInfo->id ]) }}">
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
@endsection
