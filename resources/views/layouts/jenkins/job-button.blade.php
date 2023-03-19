@if($appInfo->jenkins_status === 3200)
    @include('errors.jenkins.jenkins-down')
@else
    @if($appInfo->jenkins_status === ResponseCodes::HTTP_OK)
        @if(in_array($appInfo?->jenkins_data?->status, JobStatus::GetRunningStages()))
            @include('layouts.jenkins.job-abort-button')
        @else
            @include('layouts.jenkins.job-build-button')
        @endif
    @else
        @include('errors.jenkins.jenkins-file-notfound')
    @endif
@endif
