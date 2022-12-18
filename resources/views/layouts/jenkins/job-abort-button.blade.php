<a onclick="return confirm('Are you sure?')" href="dashboard/abort-job?id={{ $appInfo->id }}&build_number={{ $appInfo->jenkins_data->id }}">
    <i style="font-size:2em;" class="fa fa-ban text-danger"></i>
</a>
