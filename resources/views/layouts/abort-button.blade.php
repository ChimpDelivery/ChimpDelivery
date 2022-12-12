<a onclick="return confirm('Are you sure?')" href="dashboard/abort-job?id={{ $appInfo->id }}&build_number={{ $appInfo->jenkins_data->id }}">
    <button type="button" class="btn text-danger">
        <i style="font-size:2em;" class="fa fa-ban"></i>
    </button>
</a>
