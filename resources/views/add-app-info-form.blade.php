@extends('layouts.master')

@section('title', 'Create App')

@section('content')
<div class="container py-2">
    <div class="card">
        <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-plus fa-stack-1x" aria-hidden="true"></i>
            </span>
            Create App
        </div>
        <div class="card-body">
            <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{url('dashboard/store-app-info')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="app_icon">App Icon</label>
                    <input type="file" onchange="preview()" id="app_icon" name="app_icon" class="form-control form-control-file" accept="image/png">
                </div>
                <div class="form-group">
                    <img id="app_icon_preview" src="" width="100px" height="100px" alt="..." class="img-thumbnail" hidden />
                </div>
                <div class="form-group">
                    <div class="dropdown">
                        <input type="text" id="app_name" name="app_name" class="form-control" value="" hidden>

                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-apple" aria-hidden="true"></i> Select App ({{ count($allAppInfos) }})
                        </button>

                        <div id="dropdown-inputs" class="dropdown-menu pre-scrollable" aria-labelledby="dropdownMenuButton">
                            <input type="text" class="dropdown-item bg-secondary text-white font-italic" placeholder="search..." id="bundle_search_input" onkeyup="filterFunction('bundle_search_input', 'dropdown-inputs')">
                            @foreach($allAppInfos as $appInfo)
                                <a class="dropdown-item" href="#" onclick="updateAppField('{{ $appInfo->app_bundle }}', '{{ $appInfo->appstore_id }}')">{{ $appInfo->app_name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="appstore_id">Appstore ID</label>
                    <input type="text" id="appstore_id" name="appstore_id" class="form-control" required="" placeholder="Select app from list..." readonly>
                </div>
                <div class="form-group">
                    <label for="app_bundle">App Bundle</label>
                    <input type="text" id="app_bundle" name="app_bundle" class="form-control" required="" placeholder="Select app from list..." readonly>
                </div>
                <div class="form-group">
                    <input type="text" id="project_name" name="project_name" class="form-control" value="" hidden>

                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButtonGitProject" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-github" aria-hidden="true"></i> Select/Create Project ({{ count($allGitProjects) }})
                    </button>

                    <div id="dropdown-inputs-git-project" class="dropdown-menu pre-scrollable" aria-labelledby="dropdownMenuButtonGitProject">
                        <input type="text" class="dropdown-item bg-secondary text-white font-italic" placeholder="search or create..." id="git_search_input" onkeyup="filterFunction('git_search_input', 'dropdown-inputs-git-project')">
                        @foreach($allGitProjects as $gitProject)
                            <a class="dropdown-item" href="#">{{ $gitProject->name }} ({{ $gitProject->size }})</a>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="fb_app_id">Facebook App ID</label>
                    <input type="text" id="fb_app_id" name="fb_app_id" class="form-control" placeholder="facebook app id...">
                </div>
                <div class="form-group">
                    <label for="ga_id">GA ID</label>
                    <input type="text" id="ga_id" name="ga_id" class="form-control" placeholder="game analytics id...">
                </div>
                <div class="form-group">
                    <label for="ga_secret">GA Secret</label>
                    <input type="text" id="ga_secret" name="ga_secret" class="form-control" placeholder="game analytics secret...">
                </div>

                <input type="text" id="workspace_id" name="workspace_id" class="form-control" value="{{ Auth::user()->workspace->id }}" hidden>

                <button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> Create </button>
                <button type="reset" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
            If you don't see the app in the list, make sure there is an app on <a href="https://appstoreconnect.apple.com/apps">App Store Connect</a>. (And at least one version that is in the "Prepare For Submission")
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {

        $('#dropdown-inputs a').click(function() {
            let appName = $(this).text();

            // update button text.
            $('button[id="dropdownMenuButton"]').text(appName);

            // update hidden app name input.
            let appNameField = document.getElementById('app_name');
            appNameField.value = appName;
        });

        $('#dropdown-inputs-git-project a').click(function () {
            let gitProjectName = $(this).text().split('(');

            // update dropdown without size info
            $('button[id="dropdownMenuButtonGitProject"]').text(gitProjectName[0]);

            // update hidden git project field
            let gitField = document.getElementById('project_name')
            gitField.value = gitProjectName[0];
        });
    });

    function updateAppField(appBundleId, appstoreId) {
        console.log('updating app field...');

        let appBundleField = document.getElementById('app_bundle');
        appBundleField.value = appBundleId;

        let appstoreIdField = document.getElementById('appstore_id')
        appstoreIdField.value = appstoreId;
    }

    function preview() {
        document.getElementById('app_icon_preview').src = URL.createObjectURL(event.target.files[0]);
        document.getElementById('app_icon_preview').hidden = false
    }

    function filterFunction(searchInputId, dropdownId) {
        var input, filter, ul, li, a, i;
        input = document.getElementById(searchInputId);
        filter = input.value.toUpperCase();
        div = document.getElementById(dropdownId);
        a = div.getElementsByTagName("a");

        let list = [];

        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;

            if (searchInputId === 'git_search_input') {
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    list.push(true);
                }
            }

            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }

        if (searchInputId === 'git_search_input' && list.length === 0) {
            $('button[id="dropdownMenuButtonGitProject"]').text(input.value);

            let gitField = document.getElementById('project_name')
            gitField.value = input.value;
        }
    }
</script>
@endsection
