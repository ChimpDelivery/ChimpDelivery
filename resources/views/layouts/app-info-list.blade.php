@foreach($appInfos as $appInfo)
<tr>
    <th scope="row" class="text-center font-weight-normal text-muted align-middle">
        @include('layouts.app-info')
    </th>
    <td class="text-center align-middle">
        @if($appInfo->jenkins_status == 3200)
            @include('errors.jenkins.jenkins-down')
        @else
            @if($appInfo->jenkins_status == 200)
                @include('layouts.build-details-button')
            @else
                @include('errors.jenkins.jenkins-file-notfound')
            @endif
        @endif
    </td>
    <td class="text-center align-middle">
        @include('layouts.build-button')
    </td>
    <td class="text-center align-middle">
        @include('layouts.update-button')
    </td>
</tr>
@endforeach
