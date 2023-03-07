@if(isset($last_build->stop_details))
    @if (!empty($last_build->stop_details->output))
        <span class="badge bg-warning text-white">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        </span>
        {{ Str::limit($last_build->stop_details->output, $text_limits['stop_msg_length']) }}
        <hr class="my-2">
    @endif
@endif
