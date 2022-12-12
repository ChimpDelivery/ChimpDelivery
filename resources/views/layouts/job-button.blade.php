@php use App\Actions\Api\Jenkins\JobStatus; @endphp

@if($appInfo->jenkins_status == 3200)
    @include('errors.jenkins.jenkins-down')
@else
    @if($appInfo->jenkins_status == 200)
        @if($appInfo?->jenkins_data?->status != JobStatus::IN_PROGRESS->value)
            @include('layouts.build-button')
        @else
            @include('layouts.abort-button')
        @endif
    @else
        @include('errors.jenkins.jenkins-file-notfound')
    @endif
@endif
