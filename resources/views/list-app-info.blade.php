@extends('layouts.master')

@section('title', 'Apps')

@section('content')
    <div class="container py-2">
        @include('layouts.build-modal')
        <div class="card">
            <div class="card-header bg-dark text-white font-weight-bold">📱 Apps</div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table class="table table-striped table-borderless table-hover">
                        <thead>
                            <tr class="text-dark text-light">
                                <th scope="col" class="text-center col-1">🆔</th>
                                <th scope="col" class="text-center col-2">📱 App</th>
                                <th scope="col" class="text-center col-2">🔎 Last Build</th>
                                <th scope="col" class="text-center col-2">📲 Build</th>
                                <th scope="col" class="text-center col-2">⚙ Edit</th>
                            </tr>
                        </thead>
                        <tbody>@include('layouts.app-info-list')</tbody>
                    </table>
                    <div class="d-flex justify-content-center">{{ $appInfos->links() }}</div>
                </div>
            </div>
            <div class="card-footer text-muted">📌 Total app count: {{ $appInfos->count() }}</div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="popover"]').popover()
        })

        $(document).ready(function () {
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
