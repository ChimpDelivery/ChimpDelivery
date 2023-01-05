<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Contracts\View\View;

use Jantinnerezo\LivewireAlert\LivewireAlert;

use App\Models\AppInfo;
use App\Actions\Api\Ftp\CreateFBAppAds;

class FbAppAds extends Component
{
    use LivewireAlert;

    public AppInfo $appInfo;

    public function integrate()
    {
        $response = CreateFBAppAds::run($this->appInfo);

        $this->alert(
            $response['success'] ? 'success' : 'error',
            $response['message']
        );
    }

    public function render() : View
    {
        return view('livewire.app-fb-adds-btn');
    }
}
