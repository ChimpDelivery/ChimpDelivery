<div class="input-group mb-3">
    <label for="api_key" class="text-white font-weight-bold">
        Workspace API Token
    </label>
    <div class="input-group">
        <input type="text" id="api_key" name="api_key" class="form-control shadow-sm" value="{{ $this->createdToken }}" readonly>
        <div class="input-group-append">
            <button wire:click="createToken" type="button"
                    onclick="confirm('Are you sure? Generating new API Token revokes all existing tokens of user.') || event.stopImmediatePropagation()"
                    class="btn btn-success font-weight-bold shadow"
                    style="width:100%;">
                Generate
            </button>
        </div>
    </div>
    <small class="form-text text-info">
        Workspace API Tokens are used by <b>Unity3D</b> projects to fetch app information.
    </small>
</div>
