<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Contracts\View\View;

use App\Models\AppInfo;

class BuildButtonView extends Component
{
    public AppInfo $appInfo;

    public $branch;
    public $platform;
    public $storeVersion;
    public $storeCustomVersion;
    public $storeBuildNumber;

    public function mount(AppInfo $appInfo)
    {
        $this->appInfo = $appInfo;
    }

    public function submit()
    {
        dd ($this->appInfo->id);
        dd (to_route('build-app', [
            'id' => $this->appInfo->id,
            'platform' => $this->platform,
            'store_version' => $this->storeVersion,
            'store_custom_version' => $this->storeCustomVersion,
            'store_build_number' => $this->storeBuildNumber,
        ]));
    }

    public function render() : View
    {
        return view('livewire.build-button');
    }
}
