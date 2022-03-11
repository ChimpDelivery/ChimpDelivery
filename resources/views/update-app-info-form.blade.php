@extends('layouts.master')

@section('title', 'Update App')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{url('dashboard/update-app-info/'.$appInfo->id.'/update')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="app_icon">App Icon</label>
                    <input type="file" onchange="previewAppIcon()" id="app_icon" name="app_icon" class="form-control form-control-file" accept="image/png">
                    <br />
                    <img id="frame" src="{{ asset('images/'.$appInfo->app_icon) }}" width="100px" height="100px" alt="..." class="img-thumbnail" />
                </div>
                <div class="form-group">
                    <label for="app_bundle">Appstore ID</label>
                    <input type="text" id="form-control" name="appstore_id" class="form-control" required="" value="{{ $appInfo->appstore_id }}" readonly>
                </div>
                <div class="form-group">
                    <label for="app_name">App Name</label>
                    <input type="text" id="title" name="app_name" class="form-control" required="" value="{{ $appInfo->app_name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="app_bundle">App Bundle</label>
                    <input type="text" id="form-control" name="app_bundle" class="form-control" required="" value="{{ $appInfo->app_bundle }}" readonly>
                </div>
                <div class="form-group">
                    <label for="fb_app_id">Facebook App ID</label>
                    <input type="text" id="form-control" name="fb_app_id" class="form-control" value="{{ $appInfo->fb_app_id }}">
                </div>
                <div class="form-group">
                    <label for="elephant_id">Elephant ID</label>
                    <input type="text" id="form-control" name="elephant_id" class="form-control" value="{{ $appInfo->elephant_id }}">
                </div>
                <div class="form-group">
                    <label for="elephant_secret">Elephant Secret</label>
                    <input type="text" id="form-control" name="elephant_secret" class="form-control" value="{{ $appInfo->elephant_secret }}">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>

            <form class="float-right" name="delete-app-info-form" id="delete-app-info-form" method="post" action="{{ route('delete_app_info', ['id' => $appInfo->id ]) }}">
                @csrf
                <a href="dashboard/delete-app-info/{{$appInfo->id}}">
                    <button class="btn text-white bg-danger" onclick="return confirm('Are you sure?')">
                        <i class="fa fa-trash"></i>
                        Delete
                    </button>
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    function previewAppIcon() {
        document.getElementById('frame').src = URL.createObjectURL(event.target.files[0]);
    }
</script>
@endsection