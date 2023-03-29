<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Contracts\View\View;

use App\Models\AppInfo;
use App\Actions\Api\Github\GetRepositoryBranches;

class RepositoryBranchesView extends Component
{
    public AppInfo $appInfo;

    public ?array $branches = null;

    public function mount(AppInfo $appInfo)
    {
        $this->appInfo = $appInfo;
    }

    public function FetchBranches()
    {
        $this->branches = GetRepositoryBranches::run(null, $this->appInfo)->getData()->response;
    }

    public function render() : View
    {
        return view('layouts.github.repository-branches');
    }
}
