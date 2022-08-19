@extends('layouts.master')

@section('title', 'Apps')

@section('content')
    <div class="container py-2">
        @include('layouts.build-modal')
        <div class="card shadow">
            <div class="card-header bg-dark text-white font-weight-bold">
                <span class="fa-stack fa-lg">
                    <i class="fa fa-square-o fa-stack-2x"></i>
                    <i class="fa fa-database fa-stack-1x"></i>
                </span>
                Apps
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless table-hover">
                        <thead>
                        <tr class="text-dark text-light">
                            <th scope="col" class="text-center col-sm-2 d-none d-sm-table-cell" data-bs-toggle="tooltip" data-bs-placement="top" title="ID">
                            </th>
                            <th scope="col" class="text-center col-sm-2" data-bs-toggle="tooltip" data-bs-placement="top" title="App">
                            </th>
                            <th scope="col" class="text-center col-sm-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Latest Build Status">
                            </th>
                            <th scope="col" class="text-center col-sm-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Build App">
                            </th>
                            <th scope="col" class="text-center col-sm-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Update App">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            @include('layouts.app-info-list')
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">{{ $appInfos->links() }}</div>
                </div>
            </div>
            <div class="card-footer text-muted">
                <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
                <span>Total app count: {{ $totalAppCount }}</span>
                <span class="float-right">Current builds: {{ $currentBuildCount }} </span>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="popover"]').popover()
        })

        $('.popover-dismiss').popover({
            trigger: 'focus'
        })

        $(document).ready(function () {

            $('#dropdown-inputs a').on('click', function() {

                var platform = event.target.getAttribute("href");
                platform = platform.substr(1);

                //
                document.getElementById('dropdownMenuButton').innerHTML = platform;
                console.log("Selected platform:" + platform);

                updateLink();
            });

            $('#buildModal').on('show.bs.modal', function (event) {

                // Get the button that triggered the modal
                var button = $(event.relatedTarget);

                // Extract value from the custom data-* attribute
                var appId = button.data("title");
                var projectName = button.data("project");

                setCookie('target_app_id', appId, 1);
                setCookie('target_project_name', projectName, 1);

                updateLink(appId);
            });
        });

        function updateLink(appId) {
            if (appId == null) {
                console.log('app_id: ' + getCookie('target_app_id'))
            } else {
                console.log('app_id:' + appId);
            }

            var platform = document.getElementById('dropdownMenuButton').innerHTML.trim();
            console.log('platform:' + platform);

            var storeVersion = document.getElementById('store_version').value;
            console.log('store_version:' + storeVersion);

            var storeCustomVersion = document.getElementById('store_custom_version').value === 'true';
            console.log('store_custom_version:' + storeCustomVersion);

            var storeBuildNumber = document.getElementById('store_build_version').value;
            console.log('store_build_number:' + storeBuildNumber)

            var buildUrl = "dashboard/build-app/?id=" + getCookie('target_app_id') + '&platform=' + platform + '&storeVersion=' + storeVersion + '&storeCustomVersion=' + storeCustomVersion + '&storeBuildNumber=' + storeBuildNumber;
            document.getElementById('build_link').href = buildUrl;
        }
    </script>
@endsection
