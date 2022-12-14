<br />
<div class="container">
    @if($errors->any())
    <div class="alert alert-danger shadow alert-dismissible fade show">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="text-wrap">{!! $error !!}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
</div>
