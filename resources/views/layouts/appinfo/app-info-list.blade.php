@foreach($appInfos as $appInfo)
<tr>
    <th scope="row" class="text-center font-weight-normal text-muted align-middle">
        @include('layouts.appinfo.app-info')
    </th>
    <td class="text-center align-middle">
        @switch($appInfo->jenkins_status)
            @case(3200)
                @include('errors.jenkins.jenkins-down')
            @break
            @case(200)
                @include('layouts.last-build-button')
            @break
            @default
                @include('errors.jenkins.jenkins-file-notfound')
            @break
        @endswitch
    </td>
    <td class="text-center align-middle">
        @include('layouts.job-button')
    </td>
    <td class="text-center align-middle">
        @include('layouts.appinfo.update-button')
    </td>
</tr>
@endforeach
