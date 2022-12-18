@extends('master')

@section('title', 'Apps')

@section('content')
    <div class="container py-2">
        @include('layouts.jenkins.build-modal')
        <div class="card shadow bg-dark">
            <div class="card-header text-white font-weight-bold">
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
                            <tr class="shadow-sm">
                                <th style="width: 25%;" scope="col"></th>
                                <th style="width: 25%;" scope="col"></th>
                                <th style="width: 25%;" scope="col"></th>
                                <th style="width: 25%;" scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('layouts.appinfo.app-info-list')
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">{{ $appInfos->links() }}</div>
                </div>
            </div>
            @include('layouts.dashboard.card-footer', [
                'text' => '<b>Runner Limit: 2</b>' . "<b class='pull-right'>{$totalAppCount} apps</b>"
            ])
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#buildModal').on('show.bs.modal', function (event)
            {
                // Get the button that triggered the modal
                let button = $(event.relatedTarget);

                // Extract value from the custom data-* attribute
                let projectName = button.data('project');
                let prettyProjectName = projectName.slice(0, 17) + (projectName.length > 17 ? '...' : '');
                let buildUrl = button.data('build-url');

                document.getElementById('project-button-inner').innerHTML = prettyProjectName;
                document.getElementById('build-app').action = buildUrl;
            });
        });
    </script>
@endsection
