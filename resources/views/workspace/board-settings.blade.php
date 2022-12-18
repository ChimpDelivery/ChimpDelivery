<p>
    <a class="btn btn-primary btn-block text-left shadow" data-toggle="collapse" href="#collapse_workspace_settings" role="button" aria-expanded="true" aria-controls="collapse_workspace_settings">
        <i class="fa fa-cog" aria-hidden="true"></i>
        <b>Board Settings</b>
    </a>
</p>
<div class="collapse show" id="collapse_workspace_settings">
    <div class="form-group">
        <label class="text-white font-weight-bold" for="name">
            Workspace Name
        </label>
        <input type="text" class="form-control shadow-sm" id="name" name="name" aria-describedby="basic-addon3"
                value="{{ $isNew ? '' : $workspace->name }}" required="">
    </div>
</div>
