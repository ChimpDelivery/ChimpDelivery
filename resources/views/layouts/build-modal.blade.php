<form name="build-app" id="build-app" method="post" action="{{ url('dashboard/build-app') }}">
    @csrf
    <div class="modal fade" id="buildModal" tabindex="-1" role="dialog" aria-labelledby="build-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-between align-items-center">
                    <h5 class="modal-title" id="build-modal-label">
                        Build Project
                    </h5>
                    <h5>
                        <button id="project-button" type="button" class="btn badge badge-pill alert-primary shadow-sm" data-dismiss="modal" aria-label="Close">
                            <span id="project-button-inner" aria-hidden="true">&times;</span>
                        </button>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select name="platform" id="platform"
                            class="shadow-sm form-control selectpicker show-tick"
                            data-style="btn-primary" data-live-search="false" data-dropup-auto="false" data-size="10"
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
                            <button class="btn btn-block btn-primary font-weight-bold shadow-sm" type="button" data-toggle="collapse" data-target="#store_build_number_collapse" aria-expanded="false" aria-controls="store_build_number_collapse">
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
                    <button type="submit" class="btn btn-success font-weight-bold shadow">
                        <i class="fa fa-cloud-upload" aria-hidden="true"></i> Build
                    </button>
                    <button type="button" class="btn btn-secondary font-weight-bold shadow" data-dismiss="modal">
                        <i class="fa fa-window-close" aria-hidden="true"></i> Close
                    </button>
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
