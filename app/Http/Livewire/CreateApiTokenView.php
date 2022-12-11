<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Actions\Dashboard\User\CreateUserApiKey;

class CreateApiTokenView extends Component
{
    private string $createdToken = "**************";

    public function createToken()
    {
        $this->createdToken = CreateUserApiKey::run();
    }

    public function render()
    {
        return <<<'HTML'
            <div class="input-group mb-3">
                <label for="api_key">Workspace Api Token</label>
                <div class="input-group">
                    <input type="text" id="api_key" name="api_key" class="form-control shadow-sm" value="{{ $this->createdToken }}" readonly>
                    <div class="input-group-append">
                        <button onclick="confirm('Are you sure? Generating new API Token revokes all existing tokens of user.') || event.stopImmediatePropagation()" wire:click="createToken()" type="button" class="btn btn-success border border-dark font-weight-bold shadow" style="width:100%;">Generate</button>
                    </div>
                </div>
                <small class="form-text text-muted">
                    Workspace API Tokens are used by <span class="badge badge-primary">Unity3D</span> projects to fetch app information when building.
                </small>
            </div>
        HTML;
    }
}
