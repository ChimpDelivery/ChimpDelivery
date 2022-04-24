@foreach($appInfos as $appInfo)
    <tr>
        <th scope="row"
            class="text-center font-italic font-weight-light text-muted align-middle">
            #{{ $appInfo->id }}</th>
        <td class="text-center align-middle">
            <div class="container">
                @include('layouts.app-info')
            </div>
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
                    <i style="font-size:2em;" class="fa fa-pencil-square-o text-primary"></i>
                </button>
            </a>
        </td>
    </tr>
@endforeach
