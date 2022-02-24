@php( $appInfos = \App\Models\AppInfo::all() )
@php( $allBundleIds = json_decode(\App\Http\Controllers\AppStoreConnectApi::getAllBundles()->getContent(), true) )

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
                    <input type="text" id="app_name" name="app_name" class="form-control" required="" placeholder="appstore app name...">
                </div>
                <div class="form-group">
                    <div class="dropdown">
                        <input type="text" id="app_bundle" name="app_bundle" class="form-control" required="" hidden>

                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select bundle
                        </button>
                        <div class="dropdown-menu pre-scrollable" aria-labelledby="dropdownMenuButton">
                            @foreach($allBundleIds['bundle_ids'] as $bundle)
                                <a class="dropdown-item" href="#">{{ $bundle }}</a>
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
                <button type="submit" class="btn btn-primary">Add</button>
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
            $('button[data-toggle="dropdown"]').text($(this).text());

            var appBundle = document.getElementById('app_bundle');
            appBundle.value = $(this).text();
        });
    });

    function preview() {
        document.getElementById('frame').src = URL.createObjectURL(event.target.files[0]);
        document.getElementById('frame').hidden = false
    }
</script>
@endsection
