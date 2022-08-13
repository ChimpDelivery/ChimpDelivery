@extends('layouts.master')

@section('title', 'Update App')

@section('content')
<div class="container py-2">
    <div class="card">
        <div class="card-header bg-dark text-white font-weight-bold">
            <span class="fa-stack fa-lg">
                <i class="fa fa-square-o fa-stack-2x"></i>
                <i class="fa fa-apple fa-stack-1x"></i>
            </span>
            {{ $appInfo->app_name }}
        </div>
        <div class="card-body">
            <form name="add-add-info-form" id="add-app-info-form" method="post" action="{{url('dashboard/update-app-info?id=' . $appInfo->id)}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="app_icon">App Icon</label>
                    <input type="file" onchange="previewAppIcon()" id="app_icon" name="app_icon" class="form-control form-control-file" accept="image/png">
                    <br />
                    <img id="app_icon_preview" src="{{ asset('images/app-icons/'.$appInfo->app_icon) }}" width="100px" height="100px" alt="..." class="img-thumbnail" />
                </div>
                <div class="form-group">
                    <label for="appstore_id">Appstore ID</label>
                    <input type="text" id="appstore_id" name="appstore_id" class="form-control" required="" value="{{ $appInfo->appstore_id }}" readonly>
                </div>
                <div class="form-group">
                    <label for="app_name">App Name</label>
                    <input type="text" id="app_name" name="app_name" class="form-control" required="" value="{{ $appInfo->app_name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="app_bundle">App Bundle</label>
                    <input type="text" id="app_bundle" name="app_bundle" class="form-control" required="" value="{{ $appInfo->app_bundle }}" readonly>
                </div>
                <div class="form-group">
                    <label for="project_name">Github Project</label>
                    <input type="text" id="project_name" name="project_name" class="form-control" required="" value="{{ $appInfo->project_name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="fb_app_id">Facebook App ID</label>
                    <input type="text" id="fb_app_id" name="fb_app_id" class="form-control" value="{{ $appInfo->fb_app_id }}">
                </div>
                <div class="form-group">
                    <label for="ga_id">GA ID</label>
                    <input type="text" id="ga_id" name="ga_id" class="form-control" value="{{ $appInfo->ga_id }}">
                </div>
                <div class="form-group">
                    <label for="ga_secret">GA Secret</label>
                    <input type="text" id="ga_secret" name="ga_secret" class="form-control" value="{{ $appInfo->ga_secret }}">
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="form_confirmation" required="">
                        <label class="form-check-label" for="form_confirmation">
                            I have reviewed and approved the information.
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-check-square"></i> Apply</button>
                <button type="reset" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</button>
                <button class="btn btn-danger float-right" type="submit" onclick="return confirm('Are you sure?')" formaction="{{ route('delete_app_info', ['id' => $appInfo->id ]) }}" formmethod="post">
                    <i class="fa fa-trash"></i>
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    function previewAppIcon() {
        document.getElementById('app_icon_preview').src = URL.createObjectURL(event.target.files[0]);
    }
</script>
@endsection
