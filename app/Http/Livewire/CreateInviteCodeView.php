<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CreateInviteCodeView extends Component
{
    private string $createdCode = '**************';

    public function mount()
    {
        $this->createdCode = auth()->user()->workspace->inviteCodes()->first()->code;
    }

    public function createInviteCode()
    {
        $this->createdCode = auth()->user()->workspace->createInviteCode();
    }

    public function render()
    {
        return <<<'HTML'
            <div class="input-group mb-3">
                <label for="api_key" class="text-white font-weight-bold">
                    Invite Code
                </label>
                <div class="input-group">
                    <input type="text" id="api_key" name="api_key" class="form-control shadow-sm" value="{{ $this->createdCode }}" readonly>
                    <div class="input-group-append">
                        <button wire:click="createInviteCode"
                                onclick="confirm('Are you sure?\nGenerating new Invite code revokes all existing codes of Workspace.') || event.stopImmediatePropagation()"
                                type="button"
                                class="btn btn-success font-weight-bold shadow"
                                style="width:100%;">
                            Generate
                        </button>
                    </div>
                </div>
            </div>
        HTML;
    }
}
