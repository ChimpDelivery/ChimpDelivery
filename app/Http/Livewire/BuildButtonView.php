<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Contracts\View\View;

use Jantinnerezo\LivewireAlert\LivewireAlert;

use App\Models\AppInfo;

class BuildButtonView extends Component
{
    use LivewireAlert;

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

    public function submit() : void
    {
        $this->alert('success', $this->appInfo->id);
    }

    public function render() : View
    {
        return view('livewire.build-button');
    }
}
