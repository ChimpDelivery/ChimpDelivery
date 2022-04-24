<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Build Information
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="dropdownMenuButton">Git Branch</label>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            master
                        </button>

                        <div id="dropdown-inputs" class="dropdown-menu pre-scrollable" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">master</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tf_version">Test-Flight Version</label>
                    <input oninput="updateLink()" type="text" id="tf_version" name="tf_version"
                           class="form-control" required="" value="{{ config('appstore.default_tf_version') }}">
                </div>
                <div class="form-check">
                    <input onchange="updateLink()" class="form-check-input" type="checkbox"
                           value=""
                           id="is_workspace">
                    <label class="form-check-label" for="is_workspace">
                        Is Workspace
                    </label>
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
