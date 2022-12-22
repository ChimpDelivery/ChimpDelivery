<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Jantinnerezo\LivewireAlert\LivewireAlert;

use App\Models\AppInfo;
use App\Actions\Api\Ftp\CreateFBAppAds;

class FbAppAds extends Component
{
    use LivewireAlert;

    public AppInfo $appInfo;

    public function integrate()
    {
        $response = CreateFBAppAds::run(null, $this->appInfo);

        $this->alert(
            $response['success'] ? 'success' : 'error',
            $response['message']
        );
    }

    public function render()
    {
        return <<<'HTML'
            <div class="input-group-append">
                <button wire:click="integrate()"
                        class="btn alert-primary font-weight-bold"
                        type="button"
                        onclick="return confirm('FB App ID gonna be added in app-ads.txt, are you sure?') || event.stopImmediatePropagation()"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="App-Ads.txt integration">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </div>
        HTML;
    }
}
