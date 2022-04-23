@extends('layouts.master')

@section('title', 'Apps')

@section('content')
<div class="container py-2">
    <!-- Modal_Start !-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Build Information
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="git_branch">Git Branch</label>

                        <div class="dropdown">
                            <input type="text" id="git_branch" name="git_branch" class="form-control" value="" hidden>

                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                master
                            </button>

                            <div id="dropdown-inputs" class="dropdown-menu pre-scrollable" aria-labelledby="dropdownMenuButton">
                                <input type="text" id="git_branch_name" name="git_branch_name" hidden>
                                <a class="dropdown-item" href="#">{{ __('master') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tf_version">Test-Flight Version</label>
                        <input oninput="updateLink()" type="text" id="tf_version" name="tf_version" class="form-control" required="" value="{{ config('appstore.default_tf_version') }}">
                    </div>
                    <div class="form-check">
                        <input onchange="updateLink()" class="form-check-input" type="checkbox" value="" id="is_workspace">
                        <label class="form-check-label" for="is_workspace">
                            Is Workspace
                        </label>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <a id="build_link" href="dashboard/build-app/">
                        <button type="button" class="btn btn-primary"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Build</button>
                    </a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal_End !-->

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
                                        <p>
                                            <a class="text-muted" href="{{ $appInfo->git_url }}">
                                                (Github)
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <p>
                                    @if ($appInfo->job_exists)
                                        <a class="text-dark font-weight-bold" href="{{ $appInfo->jenkins_url }}">
                                            {{ $appInfo->build_number }}
                                        </a>

                                        @switch ($appInfo->build_status)

                                            @case ('ABORTED')
                                                <h6 class="text-secondary font-weight-bold rounded">
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                    {{ $appInfo->build_status }}
                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                </h6>
                                            @break

                                            @case ('BUILDING')
                                                <div class="spinner-grow text-primary" role="status"><span class="sr-only">.</span></div>
                                                <div class="spinner-grow text-success" role="status"><span class="sr-only">.</span></div>
                                                <div class="spinner-grow text-danger" role="status"><span class="sr-only">.</span></div>
                                                <div class="spinner-grow text-warning" role="status"><span class="sr-only">.</span></div>
                                                <p class="text-muted font-weight-bold rounded">
                                                    {{ $appInfo->build_status }}
                                                    <br />
                                                    <span class="font-weight-normal font-italic text-info">
                                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                        {{ $appInfo->estimated_time }}
                                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                    </span>
                                                </p>
                                            @break

                                            @case ('SUCCESS')
                                                <h6 class="text-success font-weight-bold rounded">
                                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                                    {{ $appInfo->build_status }}
                                                    <i class="fa fa-thumbs-o-up fa-flip-horizontal" aria-hidden="true"></i>
                                                </h6>
                                            @break

                                            @case ('FAILURE')
                                                <h6 class="text-danger font-weight-bold rounded">
                                                    <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                                                    {{ $appInfo->build_status }}
                                                    <i class="fa fa-thumbs-o-down fa-flip-horizontal" aria-hidden="true"></i>
                                                </h6>
                                            @break

                                            @case ('NO_BUILD')
                                                @if ($appInfo->build_number != 1)
                                                    <h6 class="text-secondary font-weight-bold rounded">
                                                        <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                                        {{ $appInfo->build_status }}
                                                        <i class="fa fa-circle-thin" aria-hidden="true"></i>
                                                    </h6>
                                                @endif
                                            @break
                                        @endswitch

                                        @php
                                            $commitCount = count($appInfo?->change_sets);
                                            $commitHistory = '';

                                            for ($i = 0; $i < $commitCount; ++$i)
                                            {
                                                $commitHistory .= ($i + 1) . '. ' . nl2br(trim($appInfo->change_sets[$i]) . "\r\n");
                                            }
                                        @endphp

                                        @if (count($appInfo?->change_sets) > 0)
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="popover" title="Commit History ({{ $commitCount }})" data-html="true" data-content="{{ $commitHistory }}">Commits</button>
                                        @endif
                                    @else
                                        <h6 class="text-danger font-weight-bold rounded">
                                            <i class="fa fa-file-o" aria-hidden="true"></i>
                                            MISSING
                                            <i class="fa fa-file-o fa-flip-horizontal" aria-hidden="true"></i>
                                        </h6>
                                    @endif
                                </p>
                            </td>
                            <td class="text-center align-middle">
                                @if ($appInfo->job_exists)
                                    @if ($appInfo->build_status != 'BUILDING')
                                        <button id="build_button" type="button" class="btn text-white bg-transparent" data-toggle="modal" data-target="#exampleModal" data-title="{{$appInfo->id}}">
                                            <i style="font-size:2em;" class="fa fa-cloud-upload text-success"></i>
                                        </button>
                                    @else
                                        <a onclick="return confirm('Are you sure?')" href="dashboard/stop-job/{{ $appInfo->project_name }}/{{ $appInfo->build_number }}">
                                            <button type="button" class="btn text-white bg-transparent">
                                                <i style="font-size:2em;" class="fa fa-ban text-danger"></i>
                                            </button>
                                        </a>
                                    @endif
                                @else
                                    <h6 class="text-danger font-weight-bold rounded">
                                        <i class="fa fa-file-o" aria-hidden="true"></i>
                                        MISSING
                                        <i class="fa fa-file-o fa-flip-horizontal" aria-hidden="true"></i>
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
        $(function () {
            $('[data-toggle="popover"]').popover()
        })

        $(document).ready(function() {
            $('#exampleModal').on('show.bs.modal', function (event) {

                resetBuildInputs();

                // Get the button that triggered the modal
                var button = $(event.relatedTarget);

                // Extract value from the custom data-* attribute
                var appId = button.data("title");

                setCookie('target_app_id', appId, 1);

                updateLink(appId);
            });
        });

        // reset modal inputs
        function resetBuildInputs() {
            const tfVersion = {{ config('appstore.default_tf_version') }};
            document.getElementById('tf_version').value = tfVersion.toFixed(1);
            document.getElementById('is_workspace').checked = getCookie('target_is_ws');
        }

        function updateLink(appId) {
            console.log('app_id:' + appId);

            var tfVersion = document.getElementById('tf_version').value;
            console.log('tf_version: ' + tfVersion);

            var isWorkspace = document.getElementById('is_workspace').checked;
            console.log('is_workspace: ' + isWorkspace);

            var buildUrl = "dashboard/build-app/" + getCookie('target_app_id') + '/' + isWorkspace + '/' + tfVersion;
            document.getElementById('build_link').href = buildUrl;
        }
    </script>
@endsection
