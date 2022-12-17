<p>
    <a class="btn btn-primary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_apple_settings" role="button" aria-expanded="false" aria-controls="collapse_apple_settings">
        <i class="fa fa-apple" aria-hidden="true"></i>
        <b>TestFlight API</b>
    </a>
</p>
<div class="collapse" id="collapse_apple_settings">
    <div class="form-group">
        <a class="badge bg-success text-white" href="https://appleid.apple.com/account/manage" target="_blank">
            <i class="fa fa-external-link" aria-hidden="true"></i> Get Password
        </a>
    </div>
    <div class="form-group">
        <label for="usermail">User Mail</label>
        <input type="text" id="usermail" name="usermail" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->appleSetting->usermail }}">
    </div>
    <div class="form-group">
        <label for="app_specific_pass">App Specific Password</label>
        <input type="text" id="app_specific_pass" name="app_specific_pass" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->appleSetting->app_specific_pass }}">
    </div>
</div>
