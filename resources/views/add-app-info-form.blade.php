@php( $appInfos = \App\Models\AppInfo::all() )
@php( $allAppInfos = \App\Http\Controllers\AppStoreConnectApi::getAppDictionary() )

@extends('layouts.master')

@section('title', 'Create App')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{url('dashboard/store-app-info')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="app_icon">App Icon</label>
                    <input type="file" onchange="preview()" id="app_icon" name="app_icon" class="form-control form-control-file" accept="image/png">
                </div>
                <div class="form-group">
                    <img id="frame" src="" width="100px" height="100px" alt="..." class="img-thumbnail" hidden />
                </div>
                <div class="form-group">
                    <label for="app_name">App Name</label>
                    <input type="text" id="app_name" name="app_name" class="form-control" required="" placeholder="select bundle from list..." readonly>
                </div>
                <div class="form-group">
                    <div class="dropdown">
                        <input type="text" id="app_bundle" name="app_bundle" class="form-control" value="" hidden>

                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select bundle
                        </button>

                        <div id="dropdown-inputs" class="dropdown-menu pre-scrollable" aria-labelledby="dropdownMenuButton">
                            <input type="text" class="dropdown-item bg-secondary text-white font-italic" placeholder="search..." id="search_input" onkeyup="filterFunction()">
                            @foreach($allAppInfos as $appInfo)
                                <input type="text" id="app_info_name" name="app_info_name" hidden>
                                <a class="dropdown-item" href="#" onclick="updateAppNameField('{{$appInfo[1]}}')">{{ $appInfo[0] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fb_app_id">Facebook App ID</label>
                    <input type="text" id="form-control" name="fb_app_id" class="form-control" required="" placeholder="facebook app id...">
                </div>
                <div class="form-group">
                    <label for="elephant_id">Elephant ID</label>
                    <input type="text" id="form-control" name="elephant_id" class="form-control" required="" placeholder="elephant id...">
                </div>
                <div class="form-group">
                    <label for="elephant_secret">Elephant Secret</label>
                    <input type="text" id="form-control" name="elephant_secret" class="form-control" required="" placeholder="elephant secret...">
                </div>
                <button type="submit" class="btn btn-success">Create App</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {

        $('.dropdown-menu a').click(function () {
            var bundleName = $(this).text();

            // update dropdown
            $('button[data-toggle="dropdown"]').text(bundleName);

            // update hidden bundle input
            var appBundle = document.getElementById('app_bundle');
            appBundle.value = bundleName;
        });
    });

    function updateAppNameField(appName) {
        var appNameField = document.getElementById('app_name');
        appNameField.value = appName;
    }

    function preview() {
        document.getElementById('frame').src = URL.createObjectURL(event.target.files[0]);
        document.getElementById('frame').hidden = false
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
