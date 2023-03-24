<div>
    <a id="build_button" type="button" class="btn btn-link" data-toggle="modal" data-target="#buildModal{{ $appInfo->id }}">
        <span class="fa-stack" style="font-size: 1.5rem; text-shadow:2px 2px 2px rgb(30,30,30);">
            <i class="fa fa-square fa-stack-2x"></i>
            <i class="fa fa-cloud-upload fa-stack-1x text-white"></i>
        </span>
    </a>

    <form wire:submit.prevent="submit" name="build-app" id="build-app">
        @csrf
        @honeypot
        <div class="modal fade" id="buildModal{{ $appInfo->id }}" tabindex="-1" role="dialog" aria-labelledby="build-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-between align-items-center">
                        <h5>
                            <span class="modal-title text-dark font-weight-bold" id="build-modal-label">
                                <i class="fa fa-cube" aria-hidden="true"></i> Build App
                            </span>
                        </h5>
                        <h5>
                            <button id="project-button" type="button" class="btn badge badge-pill bg-primary shadow-sm text-white font-weight-bold" data-dismiss="modal" aria-label="Close">
                                <span id="project-button-inner" aria-hidden="true">{{ $appInfo->project_name }}</span>
                            </button>
                        </h5>
                    </div>
                    <div class="modal-body">
                        @feature(\App\Features\BranchBasedBuild::class)
                        <div class="form-group">
                            @livewire('repository-branches-view', [ 'appInfo' => $appInfo ])
                        </div>
                        @endfeature
                        <div class="form-group">
                            @include('layouts.delivery.target-platforms')
                        </div>
                        <div class="form-group">
                            <label for="store_version">Store Version</label>
                            <input type="text" id="store_version" name="store_version"
                                   wire:model.defer="storeVersion"
                                   class="form-control shadow-sm" required="" value="1.0">
                        </div>
                        <div class="form-group">
                            <input type="text" id="store_custom_version" name="store_custom_version" class="form-control" value="false"
                                   wire:model.defer="storeCustomVersion"
                                   hidden>
                            <p>
                                <button class="btn btn-block btn-secondary font-weight-bold shadow-sm" type="button" data-toggle="collapse" data-target="#store_build_number_collapse" aria-expanded="false" aria-controls="store_build_number_collapse">
                                    Custom Build Number
                                </button>
                            </p>
                            <div class="collapse" id="store_build_number_collapse">
                                <div class="form-group">
                                    <label for="store_build_number">Store Build Number</label>
                                    <input type="text" id="store_build_number" name="store_build_number"
                                           wire:model.defer="storeBuildNumber"
                                           class="form-control" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success font-weight-bold shadow">
                            <i class="fa fa-cloud-upload"></i>
                            Build
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
