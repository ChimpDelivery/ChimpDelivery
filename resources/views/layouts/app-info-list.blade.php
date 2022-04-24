@foreach($appInfos as $appInfo)
    <tr>
        <th scope="row" class="text-center font-italic font-weight-normal text-muted align-middle d-none d-sm-table-cell">#{{ $appInfo->id }}</th>
        <td class="text-center align-middle">
            @include('layouts.app-info')
        </td>
        <td class="text-center align-middle">
            @if ($appInfo->job_exists)
                @if ($appInfo->build_status == 'BUILDING')
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
                    <span style="font-size:2em;">⚙</span>
                </button>
            </a>
        </td>
    </tr>
@endforeach
