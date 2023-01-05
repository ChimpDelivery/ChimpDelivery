<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CreateInviteCodeView extends Component
{
    private string $createdCode = '**************';

    public function mount()
    {
        $this->createdCode = Auth::user()->workspace->inviteCodes()->first()->code ?? '';
    }

    public function createInviteCode()
    {
        $this->createdCode = Auth::user()->workspace->createInviteCode();
    }

    public function render() : View
    {
        return view('livewire.workspace-invite-code-btn');
    }
}
