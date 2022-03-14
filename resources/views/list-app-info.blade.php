@extends('layouts.master')

@section('title', 'Apps')

@section('content')
<div class="container py-2">
    <div class="card">
        <div class="card-header bg-dark text-white font-weight-bold">
            📱 Apps
        </div>
        <div class="card-body">
            <div class="table-responsive-sm">
                <table class="table table-striped table-borderless table-hover">
                    <thead>
                        <tr class="text-dark text-light">
                            <th scope="col" class="text-center col-1">🆔 </th>
                            <th scope="col" class="text-center col-2">📱 App</th>
                            <th scope="col" class="text-center col-2">🔎 Status</th>
                            <th scope="col" class="text-center col-3">📲 Build</th>
                            <th scope="col" class="text-center col-2">⚙️ Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appInfos as $appInfo)
                        <tr>
                            <th scope="row" class="text-center font-italic font-weight-light text-muted align-middle">#{{ $appInfo->id }}</th>
                            <td class="text-center align-middle">
                                <div class="container">
                                    <div class="col">
                                        <img src="{{ asset('images/'.$appInfo->app_icon) }}" width="100px" height="100px" alt="..." class="img-thumbnail" />
                                    </div>
                                    <div class="col">
                                        <a class="text-dark font-weight-bold" href="https://appstoreconnect.apple.com/apps/{{ $appInfo->appstore_id }}/testflight">
                                            {{ $appInfo->app_name }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <a class="text-dark" href="{{ $appInfo->latest_build_url }}">
                                    {{ $appInfo->latest_build_number }}
                                </a>
                            </td>
                            <td class="text-center align-middle">
                                <a href="dashboard/build-app/{{$appInfo->id}}">
                                    <button class="btn text-white bg-transparent">
                                        <i style="font-size:2em;" class="fa fa-cloud-upload text-success"></i>
                                    </button>
                                </a>
                            </td>
                            <td class="text-center align-middle">
                                <a href="dashboard/update-app-info/{{$appInfo->id}}">
                                    <button class="btn text-white bg-transparent">
                                        <i style="font-size:2em;" class="fa fa-pencil text-primary"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $appInfos->links() }}
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            📌 Total app count: {{ $appInfos->count() }}
        </div>
    </div>
</div>
@endsection