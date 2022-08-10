@if($appInfo->job_exists)
    @if($appInfo->build_status?->status != 'IN_PROGRESS')
        <button id="build_button" type="button" class="btn text-white bg-transparent" data-toggle="modal"
                data-target="#buildModal" data-title="{{ $appInfo->id }}" data-project="{{ $appInfo->app_name }}">
            <i class="fa fa-cloud-upload text-primary" aria-hidden="true" style="font-size:2em;"></i>
        </button>
    @else
        <a onclick="return confirm('Are you sure?')" href="dashboard/stop-job?projectName={{ $appInfo->project_name }}&buildNumber={{ $appInfo->build_number }}">
            <button type="button" class="btn text-white bg-transparent">
                <i style="font-size:2em;" class="fa fa-ban text-danger"></i>
            </button>
        </a>
    @endif
@else
    @include('layouts.jenkins-file-notfound')
@endif
