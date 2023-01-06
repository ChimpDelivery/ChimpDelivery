<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CreateApiTokenView extends Component
{
    private string $createdToken = '**************';

    public function createToken()
    {
        $this->createdToken = Auth::user()->createApiToken();
    }

    public function render() : View
    {
        return view('livewire.user-api-token-btn');
    }
}
