<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Actions\Workspace\CreateWorkspaceApiKey;

class CreateApiTokenView extends Component
{
    private string $createdToken = "**************";

    public function createToken()
    {
        $this->createdToken = CreateWorkspaceApiKey::run();
    }

    public function render()
    {
        return <<<'HTML'
            <div class="form-group">
                <label for="api_key">Workspace API Key</label>
                <div class="form-row">
                    <div class="col">
                        <input type="text" id="api_key" name="api_key" class="form-control shadow-sm" value="{{ $this->createdToken }}" readonly>
                        <small class="form-text text-muted">
                            Using by Unity3D projects to fetch app information when building.
                        </small>
                    </div>
                    <div class="col">
                        <button wire:click="createToken()" type="button" class="btn btn-success border border-dark font-weight-bold shadow" style="width:100%;">Generate Key</button>
                    </div>
                </div>
            </div>
        HTML;
    }
}
