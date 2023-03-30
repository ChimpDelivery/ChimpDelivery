<?php

namespace App\Http\Livewire\Workspace;

use Livewire\Component;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Jantinnerezo\LivewireAlert\LivewireAlert;

use App\Actions\Api\Jenkins\Post\ScanOrganization;

class ScanGithubButtonView extends Component
{
    use LivewireAlert;
    use AuthorizesRequests;

    public function scan()
    {
        $this->authorize('scan jobs');

        $response = ScanOrganization::run();
        $this->alert(
            $response['success'] ? 'success' : 'error',
            $response['message']
        );
    }

    public function render() : View
    {
        return view('livewire.workspace.scan-jenkins-button');
    }
}
