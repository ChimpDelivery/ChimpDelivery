@foreach($appInfos as $appInfo)
    <tr>
        <th scope="row" class="text-center font-weight-normal text-muted align-middle d-none d-sm-table-cell">#{{ $appInfo->id }}</th>
        <td class="text-center align-middle">
            @include('layouts.app-info')
        </td>
        <td class="text-center align-middle">
            @if ($appInfo->job_exists)
                @if ($appInfo->build_status->status == 'BUILDING')
                    @include('layouts.build-progress-bar')
                @endif

                @include('layouts.build-details-button')
            @else
                @include('layouts.jenkinsfile-notfound')
            @endif
        </td>
        <td class="text-center align-middle">
            @include('layouts.build-button')
        </td>
        <td class="text-center align-middle">
            <a href="dashboard/update-app-info/{{ $appInfo->id }}">
                <button class="btn text-white bg-transparent">
                    <i class="fa fa-pencil-square-o text-primary" aria-hidden="true" style="font-size:2em;"></i>
                </button>
            </a>
        </td>
    </tr>
@endforeach
