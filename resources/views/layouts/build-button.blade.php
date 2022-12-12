@php use App\Actions\Api\Jenkins\JobStatus; @endphp

@if($appInfo->jenkins_status == 3200)
    @include('errors.jenkins.jenkins-down')
@else
    @if($appInfo->jenkins_status == 200)
        @if($appInfo?->jenkins_data?->status != JobStatus::IN_PROGRESS->value)
            <button id="build_button" type="button" class="btn" data-toggle="modal"
                    data-target="#buildModal"
                    data-project="{{ $appInfo->project_name }}"
                    data-build-url="{{ route('build-app', [ 'id' => $appInfo->id ]) }}">
                <i class="fa fa-cloud-upload text-primary" aria-hidden="true" style="font-size:2em;"></i>
            </button>
        @else
            <a onclick="return confirm('Are you sure?')" href="dashboard/abort-job?id={{ $appInfo->id }}&build_number={{ $appInfo->jenkins_data->id }}">
                <button type="button" class="btn text-danger">
                    <i style="font-size:2em;" class="fa fa-ban"></i>
                </button>
            </a>
        @endif
    @else
        @include('errors.jenkins.jenkins-file-notfound')
    @endif
@endif
