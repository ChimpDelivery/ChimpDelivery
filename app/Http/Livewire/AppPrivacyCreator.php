<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Contracts\View\View;

use Jantinnerezo\LivewireAlert\LivewireAlert;

use App\Models\AppInfo;
use App\Actions\Api\Ftp\CreateAppPrivacy;

class AppPrivacyCreator extends Component
{
    use LivewireAlert;

    public AppInfo $appInfo;

    public function integrate()
    {
        $response = CreateAppPrivacy::run($this->appInfo);

        $this->alert(
            $response['success'] ? 'success' : 'error',
            $response['message']
        );
    }

    public function render() : View
    {
        return view('livewire.app-privacy-btn');
    }
}
