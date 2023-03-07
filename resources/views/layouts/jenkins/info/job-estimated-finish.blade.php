@if ($last_build?->status == 'IN_PROGRESS')
    @if (isset($last_build->estimated_time))
        Average Finish: <span class="text-primary font-weight-bold">{{ $last_build->estimated_time }}</span>
        <hr class="my-2">
    @endif
@endif
