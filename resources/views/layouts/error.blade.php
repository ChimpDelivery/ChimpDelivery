<br />
<div class="container">
    @if($errors->any())
    <div class="alert alert-danger shadow">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="text-wrap">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
