@if($app_commit)
    <a href="{{ $app_commit->url }}" target="_blank">
        <span class="badge alert-primary">
            {{ Str::substr($app_commit->id, 0, $limits['commit_hash_length']) }}
        </span>
        <span class="pull-right">
            {{  str(Str::limit(trim($app_commit->comment), $limits['commit_length'])) }}
        </span>
    </a>
@else
    No Commit
@endif
