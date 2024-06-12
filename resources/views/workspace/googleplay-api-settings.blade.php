<p>
    <a class="btn btn-dark text-white btn-block text-left shadow" data-toggle="collapse" href="#collapse_google_play_settings" role="button" aria-expanded="false" aria-controls="collapse_google_play_settings">
        <i class="fa-brands fa-google-play" aria-hidden="true"></i>
        <b>Google Play API</b>
    </a>
</p>
<div class="collapse" id="collapse_google_play_settings">
    <div class="form-group">
        <a class="badge bg-success text-white" href="https://console.cloud.google.com/apis/credentials" target="_blank">
            <i class="fa fa-external-link" aria-hidden="true"></i> Get Account
        </a>
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Service Account</span>
        </div>
        <div class="custom-file">
            <label class="custom-file-label" for="service_account">
                <span class="col-7 d-inline-block text-truncate text-secondary font-weight-bold">
                    {{ Str::limit($workspace->googlePlaySetting->service_account ?: 'Choose...', 128) }}
                </span>
            </label>
            <input type="file" class="custom-file-input" id="service_account" name="service_account" accept=".json">
        </div>
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Keystore File</span>
        </div>
        <div class="custom-file">
            <label class="custom-file-label" for="keystore_file">
                <span class="col-7 d-inline-block text-truncate text-secondary font-weight-bold">
                    {{ Str::limit($workspace->googlePlaySetting->keystore_file ?: 'Choose...', 128) }}
                </span>
            </label>
            <input type="file" class="custom-file-input" id="keystore_file" name="keystore_file" accept=".keystore">
        </div>
    </div>
    <div class="form-group">
        <label for="keystore_pass" class="text-white font-weight-bold">
            Keystore Pass
        </label>
        <input type="text" id="keystore_pass" name="keystore_pass" class="form-control shadow-sm" value="{{ $workspace->googlePlaySetting->keystore_pass }}">
        <small class="form-text text-info">
            Android Keystore file password in Unity3D project.
        </small>
    </div>
</div>
