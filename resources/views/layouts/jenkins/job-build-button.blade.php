<button id="build_button" type="button" class="btn" data-toggle="modal"
        data-target="#buildModal"
        data-project="{{ $appInfo->project_name }}"
        data-build-url="{{ route('build-app', [ 'id' => $appInfo->id ]) }}">
    <i class="fa fa-cloud-upload text-primary" aria-hidden="true" style="font-size:2em;"></i>
</button>
