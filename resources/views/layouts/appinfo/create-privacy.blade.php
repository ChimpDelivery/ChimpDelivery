@if(isset($appInfo))
    <button class="btn btn-primary font-weight-bold text-white shadow" type="submit"
            onclick="return confirm('Privacy2 file will be created in talusstudio.com, are you sure?')"
            formaction="{{ route('create_privacy', ['id' => $appInfo->id]) }}"
            formmethod="post">
        <i class="fa fa-user-secret"></i>
        Privacy2
    </button>
@endif
