<div class="modal fade" id="buildModal" tabindex="-1" role="dialog" aria-labelledby="build-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="build-modal-label">
                    Build Project
                </h5>
                <button id="project-button" type="button" class="close" data-dismiss="modal" aria-label="Project Name">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="dropdownMenuButton">Store</label>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            Appstore
                        </button>

                        <div id="dropdown-inputs" class="dropdown-menu pre-scrollable" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#Appstore">Appstore</a>
                            <a class="dropdown-item" href="#GooglePlay">GooglePlay</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="store_version">Store Version</label>
                    <input oninput="updateLink()" type="text" id="store_version" name="store_version"
                            class="form-control" required="" value="1.0">
                </div>
                <div class="form-group">
                    <input type="text" id="store_custom_version" name="store_custom_version" class="form-control" value="" hidden>
                    <p>
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#store_build_version_collapse" aria-expanded="false" aria-controls="store_build_version_collapse">
                            Custom Build Number
                        </button>
                    </p>
                    <div class="collapse" id="store_build_version_collapse">
                        <div class="form-group">
                            <label for="store_build_version">Store Build Number</label>
                            <input oninput="updateLink()" type="text" id="store_build_version" name="store_build_version"
                                    class="form-control" required="" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <a id="build_link" href="dashboard/build-app/">
                    <button type="button" class="btn btn-primary">
                        <i class="fa fa-cloud-upload" aria-hidden="true"></i> Build
                    </button>
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-window-close" aria-hidden="true"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#buildModal').on('shown.bs.modal', function () {
        let projectName = getCookie('target_project_name');
        let prettyProjectName = projectName.substr(0, 14) + (projectName.length > 14 ? "..." : "");
        document.getElementById('project-button').innerHTML = prettyProjectName;
    });

    $('#store_build_version_collapse').on('shown.bs.collapse', function () {

        console.log('custom_build_version modal shown!');
        document.getElementById('store_custom_version').value = 'true';

        updateLink(getCookie('target_app_id'));
    });

    $('#store_build_version_collapse').on('hidden.bs.collapse', function () {

        console.log('custom_build_version modal hidden!');
        document.getElementById('store_custom_version').value = 'false';

        updateLink(getCookie('target_app_id'));
    });
</script>
