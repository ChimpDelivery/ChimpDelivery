<p>
    <a class="btn btn-dark btn-block text-left shadow" data-toggle="collapse" href="#collapse_unity_settings" role="button" aria-expanded="false" aria-controls="collapse_unity_settings">
        <i class="{{ config('icons.unity') }}" aria-hidden="true"></i>
        <b>Unity3D Build</b>
    </a>
</p>
<div class="collapse" id="collapse_unity_settings">
    <div class="form-group">
        <a class="badge bg-success text-white" href="https://support.unity.com/hc/en-us/articles/209933966-How-do-I-find-my-license-serial-number" target="_blank">
            <i class="fa fa-external-link" aria-hidden="true"></i> Get Serial
        </a>
    </div>
        <div class="form-group">
        <label for="unity_serial" class="text-white font-weight-bold">
            Serial
        </label>
        <input type="text" id="unity_serial" name="unity_serial" class="form-control shadow-sm" value="{{ $workspace->unitySetting->serial }}">
    </div>
    <div class="form-group">
        <label for="unity_username" class="text-white font-weight-bold">
            Username
        </label>
        <input type="text" id="unity_username" name="unity_username" class="form-control shadow-sm" value="{{ $workspace->unitySetting->username }}">
    </div>
    <div class="form-group">
        <label for="unity_password" class="text-white font-weight-bold">
            Password
        </label>
        <input type="text" id="unity_password" name="unity_password" class="form-control shadow-sm" value="{{ $workspace->unitySetting->password }}">
    </div>
</div>
