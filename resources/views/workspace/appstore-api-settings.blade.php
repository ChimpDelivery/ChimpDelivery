<p>
    <a class="btn btn-dark text-white btn-block text-left shadow" data-toggle="collapse" href="#collapse_app_store_connect_settings" role="button" aria-expanded="false" aria-controls="collapse_app_store_connect_settings">
        <i class="fa-brands fa-apple" aria-hidden="true"></i>
        <b>AppStore API</b>
    </a>
</p>
<div class="collapse" id="collapse_app_store_connect_settings">
    <div class="form-group">
        <a class="badge bg-success text-white" href="https://appstoreconnect.apple.com/access/api" target="_blank">
            <i class="fa fa-external-link" aria-hidden="true"></i> Get Keys
        </a>
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Private Key</span>
        </div>
        <div class="custom-file">
            <label class="custom-file-label" for="private_key">
                <span class="col-7 d-inline-block text-truncate text-secondary font-weight-bold">
                    {{ Str::limit($workspace->appStoreConnectSetting->private_key ?: 'Choose...', 128) }}
                </span>
            </label>
            <input type="file" class="custom-file-input" id="private_key" name="private_key" accept=".p8">
        </div>
    </div>
    <div class="form-group">
        <label for="kid" class="text-white font-weight-bold">
            Key ID
        </label>
        <input type="text" id="kid" name="kid" class="form-control shadow-sm" value="{{ $workspace->appStoreConnectSetting->kid }}">
    </div>
    <div class="form-group">
        <label for="issuer_id" class="text-white font-weight-bold">
            Issuer ID
        </label>
        <input type="text" id="issuer_id" name="issuer_id" class="form-control shadow-sm" value="{{ $workspace->appStoreConnectSetting->issuer_id }}">
    </div>
</div>
