@extends('master')

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
                                <th style="width: 25%;" scope="col"></th>
                                <th style="width: 25%;" scope="col"></th>
                                <th style="width: 25%;" scope="col"></th>
                                <th style="width: 25%;" scope="col"></th>
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
                <span class="badge badge-primary">
                    <i class="fa fa-bell text-white" aria-hidden="true"></i>
                </span>
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
