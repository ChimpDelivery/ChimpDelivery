<div class="card-header text-white font-weight-bold">
    <span class="fa-stack fa-lg">
        <i class="fa fa-square-o fa-stack-2x"></i>
        <i class="fa {{ $icon }} fa-stack-1x"></i>
    </span>
    <span>{{ $text }}</span>
    @isset($additional)
        <span class="my-2 text-muted pull-right">{{ $additional }}</span>
    @endisset
</div>
