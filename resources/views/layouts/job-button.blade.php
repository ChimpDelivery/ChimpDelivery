@php use App\Actions\Api\Jenkins\JobStatus; @endphp

@if($appInfo->jenkins_status == 3200)
    @include('errors.jenkins.jenkins-down')
@else
    @if($appInfo->jenkins_status == 200)
        @if(in_array($appInfo?->jenkins_data?->status, JobStatus::GetRunningStages()))
            @include('layouts.abort-button')
        @else
            @include('layouts.build-button')
        @endif
    @else
        @include('errors.jenkins.jenkins-file-notfound')
    @endif
@endif
