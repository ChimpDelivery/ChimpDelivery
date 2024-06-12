<div class="card-header text-white font-weight-bold">
    <span>
        <i class="fa-solid {{ $icon }} fa-lg"></i> &nbsp; {{ $text }}
    </span>
    @isset($additional)
        @if(!empty($additional))
            <btn class="badge bg-success text-white float-right font-weight-bold">
                {!! $additional !!}
            </btn>
        @endif
    @endisset
</div>
