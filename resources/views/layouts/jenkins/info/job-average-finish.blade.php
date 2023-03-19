@if ($last_build?->status == JobStatus::IN_PROGRESS->value)
    @if (isset($last_build->estimated_time))
        Average Finish: <span class="text-primary font-weight-bold">{{ $last_build->estimated_time }}</span>
        <hr class="my-2">
    @endif
@endif
