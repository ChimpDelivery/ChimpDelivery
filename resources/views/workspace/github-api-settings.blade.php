<p>
    <a class="btn btn-secondary btn-block text-left shadow" data-toggle="collapse" href="#collapse_github_settings" role="button" aria-expanded="false" aria-controls="collapse_github_settings">
        <i class="fa fa-github" aria-hidden="true"></i>
        <b>GitHub API</b>
    </a>
</p>
<div class="collapse" id="collapse_github_settings">
    <div class="form-group">
        <a class="badge bg-success text-white" href="https://github.com/settings/tokens" target="_blank">
            <i class="fa fa-external-link" aria-hidden="true"></i> Get Token
        </a>
    </div>
    <div class="form-group">
        <label for="personal_access_token" class="text-white font-weight-bold">
            Personal Access Token
        </label>
        <input type="text" id="personal_access_token" name="personal_access_token" class="form-control shadow-sm"
                value="{{ ($isNew) ? '' : $workspace->githubSetting->personal_access_token }}">
    </div>
    <div class="form-group">
        <label for="organization_name" class="text-white font-weight-bold">
            Organization Name
        </label>
        <input type="text" id="organization_name" name="organization_name" class="form-control shadow-sm"
                value="{{ ($isNew) ? '' : $workspace->githubSetting->organization_name }}" @readonly(!$isNew)>
        <small class="form-text text-info">
            The name of the <b>GitHub Organization</b> that contains the projects to build.
        </small>
    </div>
    <div class="card my-2 bg-dark">
        <div class="card-header btn-primary font-weight-bold">
            <i class="fa fa-cubes" aria-hidden="true"></i> Repository Types
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="public_repo" name="public_repo" @checked(!$isNew && $workspace->githubSetting->public_repo) />
                    <label for="public_repo" class="custom-control-label text-white font-weight-bold">
                        Public
                    </label>
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="private_repo" name="private_repo" @checked(!$isNew && $workspace->githubSetting->private_repo) />
                    <label for="private_repo" class="custom-control-label text-white font-weight-bold">
                        Private
                    </label>
                    <span class="badge alert-primary badge-pill">Subscription Required</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card my-2 bg-dark">
        <div class="card-header btn-primary font-weight-bold">
            <i class="fa fa-filter" aria-hidden="true"></i> Repository Filters
        </div>
        <div class="card-body">
            <!-- DISABLED_FOR_LATER
            <div class="form-group">
                <label for="template_name">Template Project</label>
                <input type="text" id="template_name" name="template_name" class="form-control shadow-sm"
                        value="{{ ($isNew) ? '' : $workspace->githubSetting->template_name }}">
            </div>
            !-->
            <div class="form-group">
                <label for="topic_name" class="text-white font-weight-bold">
                    Project Topic
                </label>
                <input type="text" id="topic_name" name="topic_name" class="form-control shadow-sm"
                        value="{{ ($isNew) ? '' : $workspace->githubSetting->topic_name }}">
                <small class="form-text text-info">
                    GitHub Repository <b>topic name</b> to filter GitHub Projects on connected account.
                </small>
            </div>
        </div>
    </div>
</div>
