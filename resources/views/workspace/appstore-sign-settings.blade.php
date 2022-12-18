<p>
    <a class="btn btn-primary btn-block text-left shadow" data-toggle="collapse" href="#collapse_app_store_signing" role="button" aria-expanded="false" aria-controls="collapse_app_store_connect_settings">
        <i class="fa fa-apple" aria-hidden="true"></i>
        <b>AppStore App Signing</b>
    </a>
</p>
<div class="collapse" id="collapse_app_store_signing">
    <div class="form-group">
        <a class="badge bg-success text-white" href="https://developer.apple.com/account/resources/certificates/list" target="_blank">
            <i class="fa fa-external-link" aria-hidden="true"></i> Get Certificate
        </a>
        <a class="badge bg-success text-white" href="https://developer.apple.com/account/resources/profiles/list" target="_blank">
            <i class="fa fa-external-link" aria-hidden="true"></i> Get Profile
        </a>
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Certificate</span>
        </div>
        <div class="custom-file shadow-sm">
            <label class="custom-file-label" for="cert">
                <span class="col-7 d-inline-block text-truncate text-secondary font-weight-bold">
                    {{ $cert_label ?? 'Choose...' }}
                </span>
            </label>
            <input type="file" class="custom-file-input" id="cert" name="cert" accept=".p12">
        </div>
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Profile</span>
        </div>
        <div class="custom-file shadow-sm">
            <label class="custom-file-label" for="provision_profile">
                <span class="col-7 d-inline-block text-truncate text-secondary font-weight-bold">
                    {{ $provision_label ?? 'Choose...' }}
                </span>
            </label>
            <input type="file" class="custom-file-input" id="provision_profile" name="provision_profile" accept=".mobileprovision">
        </div>
    </div>
    <div class="alert alert-warning w-100 alert-dismissible fade show border border-warning">
        <span class="badge bg-warning">
            <i class="fa fa-bell text-white" aria-hidden="true"></i>
        </span>
        <small>
            Only <a class="font-weight-bold alert-warning" href="https://developer.apple.com/library/archive/qa/qa1713/_index.html">WildCard Profiles</a> supported for now!
        </small>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
