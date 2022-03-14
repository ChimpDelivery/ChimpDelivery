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
                            <th scope="col" class="text-center col-2">🔎 Last Build</th>
                            <th scope="col" class="text-center col-2">📲 Build</th>
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
                                        @if (!empty($appInfo->app_icon))
                                        <img src="{{ asset('images/'.$appInfo->app_icon) }}" width="100px" height="100px" alt="..." class="img-thumbnail" />
                                        @else
                                        <img src="{{ asset('Talus_icon.ico') }}" width="100px" height="100px" alt="..." class="img-thumbnail" />
                                        @endif
                                    </div>
                                    <div class="col">
                                        <a class="text-dark font-weight-bold" href="https://appstoreconnect.apple.com/apps/{{ $appInfo->appstore_id }}/testflight">
                                            {{ $appInfo->app_name }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <p>
                                    <a class="text-dark font-weight-bold" href="{{ $appInfo->latest_build_url }}">
                                        @if ($appInfo->latest_build_number != -1)
                                        {{ $appInfo->latest_build_number }}
                                        @endif
                                    </a>
                                </p>
                                <p>
                                    @if ($appInfo->latest_build_number == -1)
                                <p class="text-white bg-danger font-weight-bold rounded">
                                    <i class="fa fa-file-o" aria-hidden="true"></i>
                                        MISSING
                                    <i class="fa fa-file-o" aria-hidden="true"></i>
                                </p>
                                @endif

                                @if ($appInfo->latest_build_status == "ABORTED")
                                <p class="text-white bg-secondary font-weight-bold rounded">
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                    {{ $appInfo->latest_build_status }}
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                </p>
                                @endif

                                @if ($appInfo->latest_build_status == "BUILDING")
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="spinner-grow text-success" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="spinner-grow text-danger" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="spinner-grow text-warning" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>

                                <p class="text-dark font-weight-bold rounded">🤖 {{ $appInfo->latest_build_status }} 🤖</p>
                                @endif

                                @if ($appInfo->latest_build_status == "SUCCESS")
                                <p class="text-white bg-success font-weight-bold rounded">🤩 {{ $appInfo->latest_build_status }} 🤩</p>
                                @endif
                                </p>
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