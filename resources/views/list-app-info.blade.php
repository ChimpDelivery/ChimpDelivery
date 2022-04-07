@extends('layouts.master')

@section('title', 'Apps')

@section('content')
<div class="container py-2">
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Build Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tf_version">TF Version</label>
                        <input oninput="updateLink()" type="text" id="tf_version" name="tf_version" class="form-control" required="" value="{{ config('appstore.default_tf_version') }}">
                    </div>
                    <div class="form-check">
                        <input onchange="updateLink()" class="form-check-input" type="checkbox" value="" id="is_workspace">
                        <label class="form-check-label" for="is_workspace">
                            Is Workspace
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a id="build_link" href="dashboard/build-app/">
                        <button type="button" class="btn btn-primary">Build</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

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
                            <th scope="col" class="text-center col-2">⚙ Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appInfos as $appInfo)
                        <tr>
                            <th scope="row" class="text-center font-italic font-weight-light text-muted align-middle">#{{ $appInfo->id }}</th>
                            <td class="text-center align-middle">
                                <div class="container">
                                    <div class="col">
                                        @if (file_exists(public_path("images/app-icons/{$appInfo->app_icon}")) && !empty($appInfo->app_icon))
                                            <img src="{{ asset('images/app-icons/'.$appInfo->app_icon) }}" width="100px" height="100px" alt="..." class="img-thumbnail" />
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
                                    @if (config('jenkins.enabled') == false)
                                        <h6 class="text-danger font-weight-bold rounded">
                                            <i class="fa fa-power-off" aria-hidden="true"></i>
                                                JENKINS DOWN
                                            <i class="fa fa-power-off fa-flip-horizontal" aria-hidden="true"></i>
                                        </h6>
                                    @else
                                        @if ($appInfo->latest_build_number == -1)
                                            <h6 class="text-danger font-weight-bold rounded">
                                                <i class="fa fa-file-o" aria-hidden="true"></i>
                                                    MISSING
                                                <i class="fa fa-file-o fa-flip-horizontal" aria-hidden="true"></i>
                                            </h6>
                                        @endif

                                        @if ($appInfo->latest_build_number == -2)
                                            <h6 class="text-danger font-weight-bold rounded">
                                                <i class="fa fa-minus-square-o" aria-hidden="true"></i>
                                                NO BUILD
                                                <i class="fa fa-minus-square-o fa-flip-horizontal" aria-hidden="true"></i>
                                            </h6>
                                        @endif

                                        @if ($appInfo->latest_build_number != -1 && $appInfo->latest_build_number != -2)
                                            <a class="text-dark font-weight-bold" href="{{ $appInfo->latest_build_url }}">
                                                {{ $appInfo->latest_build_number }}
                                            </a>
                                        @endif

                                        @switch($appInfo->latest_build_status)
                                            @case('ABORTED')
                                                <h6 class="text-secondary font-weight-bold rounded">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                        {{ $appInfo->latest_build_status }}
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </h6>
                                                @break
                                            @case('BUILDING')
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
                                                <p class="text-muted font-weight-bold rounded">
                                                    {{ $appInfo->latest_build_status }}
                                                </p>
                                                @break
                                            @case('SUCCESS')
                                                <h6 class="text-success font-weight-bold rounded">
                                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                                        {{ $appInfo->latest_build_status }}
                                                    <i class="fa fa-thumbs-o-up fa-flip-horizontal" aria-hidden="true"></i>
                                                </h6>
                                                @break
                                            @case('FAILURE')
                                                <h6 class="text-danger font-weight-bold rounded">
                                                    <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                                        {{ $appInfo->latest_build_status }}
                                                    <i class="fa fa-thumbs-o-down fa-flip-horizontal" aria-hidden="true"></i>
                                                </h6>
                                                @break
                                        @endswitch
                                    @endif
                                </p>
                            </td>
                            <td class="text-center align-middle">
                                @if (config('jenkins.enabled'))
                                    @if ($appInfo->latest_build_status != 'BUILDING')
                                            <button id="build_button" type="button" class="btn text-white bg-transparent" data-toggle="modal" data-target="#exampleModal" data-title="{{$appInfo->id}}">
                                                <i style="font-size:2em;" class="fa fa-cloud-upload text-success"></i>
                                            </button>
                                    @else
                                        <a onclick="return confirm('Are you sure?')" href="dashboard/stop-job/{{ $appInfo->project_name }}/{{ $appInfo->latest_build_number }}">
                                            <button type="button" class="btn text-white bg-transparent">
                                                <i style="font-size:2em;" class="fa fa-ban text-danger"></i>
                                            </button>
                                        </a>
                                    @endif
                                @else
                                    <h6 class="text-danger font-weight-bold rounded">
                                        <i class="fa fa-power-off" aria-hidden="true"></i>
                                            JENKINS DOWN
                                        <i class="fa fa-power-off fa-flip-horizontal" aria-hidden="true"></i>
                                    </h6>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                <a href="dashboard/update-app-info/{{$appInfo->id}}">
                                    <button class="btn text-white bg-transparent">
                                        <i style="font-size:2em;" class="fa fa-pencil-square-o text-primary"></i>
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

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#exampleModal').on('show.bs.modal', function (event) {

                resetValues();

                // Get the button that triggered the modal
                var button = $(event.relatedTarget);

                // Extract value from the custom data-* attribute
                var appId = button.data("title");

                setCookie('target_app_id', appId, 1);

                updateLink(appId);
                // Change modal title
                // $(this).find(".modal-title").text(titleData);
            });
        });

        function resetValues() {
            document.getElementById('tf_version').value = '7.0';
            document.getElementById('is_workspace').checked = false;
        }

        function updateLink(appId) {
            console.log('app_id:' + appId);

            var tfVersion = document.getElementById('tf_version').value;
            console.log('tf_version: ' + tfVersion);

            var isWorkspace = document.getElementById('is_workspace').checked;
            console.log('is_workspace: ' + isWorkspace);

            document.getElementById('build_link').href = "dashboard/build-app/" + getCookie('target_app_id') + '/' + isWorkspace + '/' + tfVersion;
        }
    </script>
@endsection
