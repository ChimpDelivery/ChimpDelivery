<form name="build-app" id="build-app" method="post" action="{{ url('dashboard/build-app') }}">
    @csrf
    @honeypot
    <div class="modal fade" id="buildModal" tabindex="-1" role="dialog" aria-labelledby="build-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-between align-items-center">
                    <h5>
                        <span class="modal-title text-dark font-weight-bold" id="build-modal-label">
                            <i class="fa fa-cube" aria-hidden="true"></i> Build Settings
                        </span>
                    </h5>
                    <h5>
                        <button id="project-button" type="button" class="btn badge badge-pill bg-primary shadow-sm text-white font-weight-bold" data-dismiss="modal" aria-label="Close">
                            <span id="project-button-inner" aria-hidden="true">&times;</span>
                        </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select name="platform" id="platform"
                            class="shadow-sm form-control selectpicker show-tick"
                            data-style="btn-secondary" data-live-search="false" data-dropup-auto="false" data-size="10"
                            title="Select platform..." required>

                            <option data-icon="fa fa-apple" value="Appstore">App Store</option>
                            <option data-icon="fa fa-google" value="GooglePlay">Google Play</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="store_version">Store Version</label>
                        <input type="text" id="store_version" name="store_version" class="form-control shadow-sm" required="" value="1.0">
                    </div>
                    <div class="form-group">
                        <input type="text" id="store_custom_version" name="store_custom_version" class="form-control" value="false" hidden>
                        <p>
                            <button class="btn btn-block btn-secondary font-weight-bold shadow-sm" type="button" data-toggle="collapse" data-target="#store_build_number_collapse" aria-expanded="false" aria-controls="store_build_number_collapse">
                                Custom Build Number
                            </button>
                        </p>
                        <div class="collapse" id="store_build_number_collapse">
                            <div class="form-group">
                                <label for="store_build_number">Store Build Number</label>
                                <input type="text" id="store_build_number" name="store_build_number" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="install_backend" name="install_backend" />
                            <label class="custom-control-label" for="install_backend">Install Backend</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    @include('layouts.dashboard.button-success', [
                        'icon' => 'fa fa-cloud-upload',
                        'name' => 'Build'
                    ])
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    let buildCollapse = $('#store_build_number_collapse');
    buildCollapse.on('shown.bs.collapse', function ()
    {
        document.getElementById('store_custom_version').value = 'true';
    });

    buildCollapse.on('hidden.bs.collapse', function ()
    {
        document.getElementById('store_custom_version').value = 'false';
    });
</script>
