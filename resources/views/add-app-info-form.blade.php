@extends('layouts.master')

@section('title', 'Create App')

@section('content')
<div class="container py-2">
    <div class="card">
        <div class="card-header bg-dark text-white font-weight-bold">
            📲 Create App
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
                        <input type="text" id="app_bundle" name="app_bundle" class="form-control" value="" hidden>

                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select bundle id ({{ count($allAppInfos) }})
                        </button>

                        <div id="dropdown-inputs" class="dropdown-menu pre-scrollable" aria-labelledby="dropdownMenuButton">
                            <input type="text" class="dropdown-item bg-secondary text-white font-italic" placeholder="search..." id="search_input" onkeyup="filterFunction()">
                            @foreach($allAppInfos as $appInfo)
                            <input type="text" id="app_info_name" name="app_info_name" hidden>
                            <a class="dropdown-item" href="#" onclick="updateAppField('{{ $appInfo->app_name }}', '{{ $appInfo->appstore_id }}')">{{ $appInfo->app_bundle }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="app_name">Appstore ID</label>
                    <input type="text" id="appstore_id" name="appstore_id" class="form-control" required="" placeholder="select bundle from list..." readonly>
                </div>
                <div class="form-group">
                    <label for="app_name">App Name</label>
                    <input type="text" id="app_name" name="app_name" class="form-control" required="" placeholder="select bundle from list..." readonly>
                </div>
                <div class="form-group">
                    <label for="project_name">Git Project Name</label>
                    <input type="text" id="project_name" name="project_name" class="form-control" required="" placeholder="for example Oh-My-Luggage...">
                </div>
                <div class="form-group">
                    <label for="fb_app_id">Facebook App ID</label>
                    <input type="text" id="fb_app_id" name="fb_app_id" class="form-control" placeholder="facebook app id...">
                </div>
                <div class="form-group">
                    <label for="elephant_id">Elephant ID</label>
                    <input type="text" id="elephant_id" name="elephant_id" class="form-control" placeholder="elephant id...">
                </div>
                <div class="form-group">
                    <label for="elephant_secret">Elephant Secret</label>
                    <input type="text" id="elephant_secret" name="elephant_secret" class="form-control" placeholder="elephant secret...">
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> Create </button>
                <button type="reset" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            📌 If you don't see the bundle id in the list, make sure there is an app using that bundle id on <a href="https://appstoreconnect.apple.com/apps">App Store Connect</a>.
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {

        $('.dropdown-menu a').click(function() {
            var bundleName = $(this).text();

            // update dropdown
            $('button[data-toggle="dropdown"]').text(bundleName);

            // update hidden bundle input
            var appBundle = document.getElementById('app_bundle');
            appBundle.value = bundleName;
        });
    });

    function updateAppField(appName, appstoreId) {
        var appNameField = document.getElementById('app_name');
        appNameField.value = appName;

        var appstoreIdField = document.getElementById('appstore_id')
        appstoreIdField.value = appstoreId;

        var projectNameField = document.getElementById('project_name')
        projectNameField.value = appName.replace(/[^a-zA-Z0-9]/g, '')
    }

    function preview() {
        document.getElementById('app_icon_preview').src = URL.createObjectURL(event.target.files[0]);
        document.getElementById('app_icon_preview').hidden = false
    }

    function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("search_input");
        filter = input.value.toUpperCase();
        div = document.getElementById("dropdown-inputs");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }
    }
</script>
@endsection
