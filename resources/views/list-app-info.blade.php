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
            $('#buildModal').on('show.bs.modal', function (event) {

                // Get the button that triggered the modal
                let button = $(event.relatedTarget);

                // Extract value from the custom data-* attribute
                let appId = button.data('title');
                let projectName = button.data('project');

                setCookie('target_project_name', projectName, 1);

                document.getElementById('build-app').action = '/dashboard/build-app?id=' + appId;
            });
        });
    </script>
@endsection
