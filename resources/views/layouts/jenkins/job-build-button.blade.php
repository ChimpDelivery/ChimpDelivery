<button id="build_button" type="button" class="btn" data-toggle="modal"
        data-target="#buildModal"
        data-project="{{ $appInfo->project_name }}"
        data-build-url="{{ route('build-app', [ 'id' => $appInfo->id ]) }}">
    <span class="fa-stack fa-lg">
        <i class="fa fa-square fa-stack-2x"></i>
        <i class="fa fa-cloud-upload fa-stack-1x text-white"></i>
    </span>
</button>
