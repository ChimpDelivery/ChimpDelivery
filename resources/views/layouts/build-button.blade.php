@if ($appInfo->job_exists)
    @if ($appInfo->build_status->status != 'IN_PROGRESS')
        <button id="build_button" type="button"
                class="btn text-white bg-transparent" data-toggle="modal"
                data-target="#exampleModal" data-title="{{$appInfo->id}}">
            <i class="fa fa-cloud-upload text-primary" aria-hidden="true" style="font-size:2em;"></i>
        </button>
    @else
        <a onclick="return confirm('Are you sure?')"
           href="dashboard/stop-job/{{ $appInfo->project_name }}/{{ $appInfo->build_number }}">
            <button type="button" class="btn text-white bg-transparent">
                <i style="font-size:2em;" class="fa fa-ban text-danger"></i>
            </button>
        </a>
    @endif
@else
    @include('layouts.jenkins-file-notfound')
@endif
