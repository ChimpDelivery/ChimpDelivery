<button id="build_button" type="button" class="btn btn-link" data-toggle="modal"
        data-target="#buildModal"
        data-project="{{ $appInfo->project_name }}"
        data-build-url="{{ route('build-app', [ 'id' => $appInfo->id ]) }}">
    <span class="fa-stack" style="font-size: 1.5rem; text-shadow:2px 2px 2px rgb(30,30,30);">
        <i class="fa fa-square fa-stack-2x"></i>
        <i class="fa fa-cloud-upload fa-stack-1x text-white"></i>
    </span>
</button>
