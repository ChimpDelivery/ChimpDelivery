<div class="card-header text-white font-weight-bold">
    <span class="fa-stack fa-lg">
        <i class="fa fa-square-o fa-stack-2x"></i>
        <i class="fa {{ $icon }} fa-stack-1x"></i>
    </span>
    <span>{{ $text }}</span>
    @isset($additional)
        @if(!empty($additional))
            <btn class="badge bg-success my-2 text-white pull-right font-weight-bold">
                {!! $additional !!}
            </btn>
        @endif
    @endisset
</div>
