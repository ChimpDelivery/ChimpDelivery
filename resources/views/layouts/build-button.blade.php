@if ($appInfo->job_exists)
    @if ($appInfo->build_status != 'BUILDING')
        <button id="build_button" type="button"
                class="btn text-white bg-transparent" data-toggle="modal"
                data-target="#exampleModal" data-title="{{$appInfo->id}}">
            <i style="font-size:2em;" class="fa fa-cloud-upload text-success"></i>
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
    @include('layouts.jenkinsfile-notfound')
@endif
