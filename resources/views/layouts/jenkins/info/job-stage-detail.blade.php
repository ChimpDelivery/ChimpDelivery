@if(isset($last_build->stop_details))
    @if (!empty($last_build->stop_details->output))
        <span class="badge bg-danger text-white">
            <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        </span>
        {{ Str::limit($last_build->stop_details->output, $text_limits['stop_msg_length']) }}
        <hr class="my-2">
    @endif
@endif
