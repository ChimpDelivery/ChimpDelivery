<p>
    <a class="btn btn-dark btn-block text-left shadow" data-toggle="collapse" href="#collapse_github_settings" role="button" aria-expanded="false" aria-controls="collapse_github_settings">
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
                value="{{ $workspace->githubSetting->personal_access_token }}">
    </div>
    @if (!$isNew && $workspace_github_orgs->status() == ResponseCodes::HTTP_OK)
    <div class="form-group">
        <label for="organization_name" class="text-white font-weight-bold">
            Organization
        </label>
        <div class="input-group">
            <select name="organization_name"
                    class="form-control selectpicker show-tick shadow"
                    data-style="btn-primary" data-live-search="true"
                    data-dropup-auto="false" data-size="10"
                    data-html="true"
                    title="{{ $workspace->githubSetting->organization_name ?: '➤ Select Organization' }}">
                @if (empty($workspace->githubSetting->organization_name))
                    @each('layouts.github.option-organization', $workspace_github_orgs->getData()->response, 'organization')
                @else
                    <option selected value="{{ $workspace->githubSetting->organization_name }}">
                        {{ $workspace->githubSetting->organization_name }}
                    </option>
                @endif
            </select>
        </div>
        <small class="form-text text-info">
            The name of the <b>GitHub Organization</b> that contains the projects to build. <b>Once this setting is set, it cannot be changed</b>.
        </small>
    </div>
    <div class="card my-2 bg-dark">
        <div class="card-header font-weight-bold">
            <i class="fa fa-cubes" aria-hidden="true"></i> Repository Types
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="public_repo" name="public_repo" @checked($workspace->githubSetting->public_repo) />
                    <label for="public_repo" class="custom-control-label text-white font-weight-bold">
                        Public
                    </label>
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="private_repo" name="private_repo" @checked($workspace->githubSetting->private_repo) />
                    <label for="private_repo" class="custom-control-label text-white font-weight-bold">
                        Private
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="card my-2 bg-dark">
        <div class="card-header font-weight-bold">
            <i class="fa fa-filter" aria-hidden="true"></i> Repository Filters
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="topic_name" class="text-white font-weight-bold">
                    Project Topic
                </label>
                <input type="text" id="topic_name" name="topic_name" class="form-control shadow-sm"
                        value="{{ $workspace->githubSetting->topic_name }}">
                <small class="form-text text-info">
                    GitHub Repository <b>topic name</b> to filter GitHub Projects on connected account.
                </small>
            </div>
        </div>
    </div>
    @endif
</div>
