@if($appInfo->jenkins_status == 3200)
    @include('errors.jenkins.jenkins-down')
@else
    @if($appInfo->jenkins_status == 200)
        @if($appInfo?->jenkins_data?->status != 'IN_PROGRESS')
            <button id="build_button" type="button" class="btn text-white bg-transparent" data-toggle="modal"
                    data-target="#buildModal" data-title="{{ $appInfo->id }}" data-project="{{ $appInfo->project_name }}">
                <i class="fa fa-cloud-upload text-primary" aria-hidden="true" style="font-size:2em;"></i>
            </button>
        @else
            <a onclick="return confirm('Are you sure?')" href="dashboard/stop-job?id={{ $appInfo->id }}&build_number={{ $appInfo->jenkins_data->id }}">
                <button type="button" class="btn text-white bg-transparent">
                    <i style="font-size:2em;" class="fa fa-ban text-danger"></i>
                </button>
            </a>
        @endif
    @else
        @include('errors.jenkins.jenkins-file-notfound')
    @endif
@endif
