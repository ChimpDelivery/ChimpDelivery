<div class="container">
    <a tabindex="0"
        class="btn btn-sm"
        role="button"
        title=""
        data-trigger="focus"
        data-toggle="popover"
        data-html="true"
        data-placement="bottom"
        data-content="">
        <img alt="..." src="{{ config('jenkins.host') }}/job/{{ config('jenkins.ws') }}/job/{{ $appInfo->project_name }}/job/master/badge/icon?style=plastic">
    </a>
</div>
